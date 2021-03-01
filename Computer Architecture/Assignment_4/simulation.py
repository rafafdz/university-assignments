import json
from collections import deque
from cache import Cache
from memory import Memory
from disk import Disk 
from tlb import TLB
from program import Program
from utilities import decimal_to_binary, binary_to_decimal
from math import log
import sys

CACHE_PARAMS = ["PoliticaReemplazo", "FuncionCorrespondencia", "N", "Tamano",
                 "Lectura", "Escritura"]

TLB_PARAMS = ["PoliticaReemplazo", "Lineas", "Lectura", "Escritura"]

MEM_PARAMS = ["PoliticaReemplazo", "Tamano", "TamanoPaginas", 
              "Lectura", "Escritura"]


def get_param_list(dictionary, params):
    return [dictionary[param] for param in params]


class Simulation:
    def __init__(self, filename, print_to):
        self.filename = filename
        self.cache = None
        self.tlb = None
        self.memory = None
        self.disk = None
        self.running = None
        self.page_table_size = None
        self.virtual_size = None
        self.programs = []
        self.curr_program = None
        self.curr_stats = None
        self.program_map = {}
        self.stats = {}
        self.bits_address = None
        self.bits_page = None
        self.print = print_to

    def read_file(self):
        """Populate the simulation attrubutes"""
        with open(self.filename, encoding='utf-8') as file:
            json_file = json.load(file)

        cache_dict = json_file["Cache"]
        cache_params = get_param_list(cache_dict, CACHE_PARAMS)
        self.cache = Cache(*cache_params)

        tlb_dict = json_file["TLB"]
        tlb_params = get_param_list(tlb_dict, TLB_PARAMS)
        self.tlb = TLB(*tlb_params)

        memory_dict = json_file["MemoriaFisica"]
        memory_params = get_param_list(memory_dict, MEM_PARAMS)
        self.memory = Memory(*memory_params)

        disk_write = self.memory.write_speed * 10
        disk_read = self.memory.read_speed * 10
        self.disk = Disk(disk_write, disk_read)
        
        self.page_table_size = self.memory.frames * 2 
        self.virtual_size = self.memory.size * 2
        self.bits_address = int(log(self.virtual_size, 2))
        self.bits_page = int(log(self.page_table_size,  2))
        
        programs = json_file["Programas"]
        for program_dic in programs:
            program_parameters = get_param_list(program_dic, ["Nombre", "Secuencia"])
            new_program = Program(*program_parameters, self.page_table_size)
            self.programs.append(new_program)
            self.program_map[new_program.id] = new_program
            self.stats[new_program.id] = ProgramStats()
    
    def simulate(self):
        self.running = deque(self.programs)
        
        while self.running:
            program = self.running.popleft()
            self.switch_context(program)
            
            next_access = self.curr_program.next_direction()
            has_directions = True
            while next_access != -1 and has_directions:
                # input()
                # print("\n" * 3)
                # print(f"Acceso a memoria virtual: {next_access}")
                self.process_access(next_access)
                if not self.curr_program.has_next_direction():
                    has_directions = False
                else:
                    next_access = self.curr_program.next_direction()
                
            #print("Cambio de contexto")
            if self.curr_program.has_next_direction():
                self.running.append(self.curr_program)
        
    def switch_context(self, program):
        # print()
        # print(f"#################################")
        # print(f"# Cambio de contexto a {program.name} id:{program.id}")
        # print(f"################################\n")
        
        self.curr_program = program
        self.curr_stats = self.stats[program.id]
        self.tlb.invalidate_entries()
        #self.cache.invalidate_entries()
        
    def process_access(self, virtual_access):
        memory_address = self.process_virtual_access(virtual_access)
        self.process_memory_access(memory_address)                
        
        if self.print:
            print("\n" * 3)
            self.tlb.print_table()
            self.curr_program.print_page_table()
            self.cache.print_table()
            self.memory.print_table(self.program_map)
            self.disk.print_table(self.program_map)
            

    def process_virtual_access(self, virtual_access):
        program = self.curr_program
        
        binary_address = decimal_to_binary(virtual_access, self.bits_address)
        
        page_binary = binary_address[:self.bits_page]
        offset_binary = binary_address[self.bits_page:]
        
        page_decimal = binary_to_decimal(page_binary)
        offset_decimal = binary_to_decimal(offset_binary)
        
        # print(f"Pagina {page_decimal} | Offset: {offset_decimal}")
        
        if self.tlb.read(page_decimal):
            self.curr_stats.mark_tlb_hit()
            frame = self.tlb.get_element(page_decimal)
            # print(f"TLB Hit: {page_decimal} -> {frame}")
            return self.memory_address_from_frame_offset(frame, offset_decimal)
        
        # In case not in TLB
        self.curr_stats.mark_tlb_miss()
        page_table = program.page_table

        if page_table.map_present(page_decimal):
            self.curr_stats.mark_page_hit() 
            mapped_frame = page_table.get_frame(page_decimal)
            # print(f"Page Table hit: {page_decimal} -> {mapped_frame}")
            self.tlb.write(page_decimal, mapped_frame)
            self.curr_stats.mark_tlb_write()
            return self.memory_address_from_frame_offset(mapped_frame, offset_decimal)
        
        self.curr_stats.mark_page_fault()
        # Request allocation
        
        if page_table.is_in_disk(page_decimal):
            # print("Page table in Disk")
            self.curr_stats.mark_swap_in()
            self.disk.pop(program.pid, page_decimal)
            page_table.mark_not_disk(page_decimal)
        
        # Store in memory the program variables
        frame, previous = self.memory.request_allocation(program.id, page_decimal)
        self.curr_stats.mark_memory_write()
        
        if previous is not None:
            ## Invalidar direcciones de cache!
            self.cache_invalidate_frame(frame)
            # print(f"Invalidating cache directions associated with frame {frame}")            
            pid, program_page = previous
            prev_program = self.program_map[pid]
            prev_program.page_table.invalidate(frame)
            # print(f"Invalidating {prev_program.name} entry of Page Table")            
            self.curr_stats.mark_swap_out()
            self.store_in_disk(pid, program_page)
            # print(f"Swaping out from frame {frame} and storing {self.program_map[pid].name}:{pid}, Pagina {program_page}")
        
        # Write page_table
        page_table.add_entry(page_decimal, frame)
        self.tlb.write(page_decimal, frame)
        self.curr_stats.mark_tlb_write()
        
        return self.memory_address_from_frame_offset(frame, offset_decimal)
    
    
    def cache_invalidate_frame(self, frame):
        min_range = self.memory.page_size * frame 
        max_range = self.memory.page_size * (frame + 1)
        self.cache.invalidate_range(min_range, max_range)
        
    def process_memory_access(self, memory_access):
        memory_access_bin = decimal_to_binary(memory_access, self.bits_address - 1)
        if self.cache.read(memory_access_bin):
            #self.curr_stats.mark_memory_write()
            # Happy path!
            # print("Cache Hit!")
            self.curr_stats.mark_cache_hit()
            return

        self.curr_stats.mark_cache_miss()
        self.curr_stats.mark_memory_read() # Aumenta read count
        self.cache.write(memory_access_bin)
        self.curr_stats.mark_cache_write()
        
    def memory_address_from_frame_offset(self, frame, offset):
        frame_bin = decimal_to_binary(frame, self.bits_page)
        offset_bin = decimal_to_binary(offset, self.bits_address - self.bits_page)
        
        return binary_to_decimal(frame_bin + offset_bin)
        
    def store_in_disk(self, pid, program_page):
        """Performs a swap out from memory"""
        program_out = self.program_map[pid]
        program_out.page_table.mark_disk(program_page)
        self.disk.push(pid, program_page)
    
    def write_output(self, output_file):
        
        with open(output_file, "w", encoding="utf-8") as file:
            #file.write("Programa,HRT,HRC,PF,SO,SI,ST,TT,CT,MT\n")
        
            for program in self.programs:
                stats = self.stats[program.id]
                tlb_read = stats.tlb_hit_count + stats.tlb_miss_count
                cache_read = stats.cache_hit_count + stats.cache_miss_count
                
                hrt = stats.tlb_hit_count / tlb_read
                hrc = stats.cache_hit_count / cache_read 
                
                pf = stats.page_fault
                so = stats.swap_out_count
                si = stats.swap_in_count
                st = (so * (self.disk.write_speed + self.memory.read_speed) +
                    si * (self.disk.read_speed + self.memory.write_speed))
                
                tt = (tlb_read * self.tlb.read_speed + 
                    stats.tlb_write_count * self.tlb.write_speed)
                ct = (cache_read * self.cache.read_speed + 
                    stats.cache_write_count * self.cache.write_speed)
                mt = (stats.memory_read_count * self.memory.read_speed +
                    stats.memory_write_count * self.memory.write_speed)
                
                line_list = [hrt, hrc, pf, so, si, st, tt, ct, mt]
                line = ",".join([program.name] + [str(x) for x in line_list]) + "\n"
                
                file.write(line)
    
class ProgramStats:
    def __init__(self):
        self.tlb_hit_count = 0
        self.tlb_miss_count = 0
        self.cache_hit_count = 0
        self.cache_miss_count = 0
        self.swap_in_count = 0
        self.swap_out_count = 0
        self.tlb_write_count = 0
        self.cache_write_count = 0
        self.memory_read_count = 0
        self.memory_write_count = 0
        self.page_fault = 0
        self.page_hit = 0
        
    def mark_tlb_hit(self):
        self.tlb_hit_count += 1

    def mark_tlb_miss(self):
        self.tlb_miss_count += 1

    def mark_cache_hit(self):
        self.cache_hit_count += 1

    def mark_cache_miss(self):
        self.cache_miss_count += 1

    def mark_swap_in(self):
        self.swap_in_count += 1

    def mark_swap_out(self):
        self.swap_out_count += 1

    def mark_tlb_write(self):
        self.tlb_write_count += 1

    def mark_cache_write(self):
        self.cache_write_count += 1

    def mark_memory_read(self):
        self.memory_read_count += 1

    def mark_memory_write(self):
        self.memory_write_count += 1
        
    def mark_page_fault(self):
        self.page_fault += 1
        
    def mark_page_hit(self):
        self.page_hit += 1


if __name__ == "__main__":
    # for x in range(1, 8):
    #     print("######### Corriendo", x)
    #     sim = Simulation(f"Archivos_T04/test{x}.json")
    #     sim.read_file()
    #     sim.simulate()
    
    if len(sys.argv) > 4:
        print("Arguments not valid")
        sys.exit(1)
        
    to_print = len(sys.argv) == 4

    from_file = sys.argv[1]
    to_file = sys.argv[2]

    sim = Simulation(from_file, to_print)
    sim.read_file()
    sim.simulate()
    sim.write_output(to_file)