from base_memory import BaseMemory
from collections import deque
from prettytable import PrettyTable

class Memory(BaseMemory):

    def __init__(self, replace_crit, size, page_size,
                 write_speed, read_speed):

        super().__init__(replace_crit, "FA", write_speed, read_speed)
        self.size = size
        self.page_size = page_size
        self.blocks = self.size / 2
        self.frames = int(self.size / self.page_size)
        self.allocations = deque()

    def request_allocation(self, pid, program_page):
        """Returns the physical frame that has been mapped 
           and the program that is going out of memory > Swap
        """
        # When There is free space
        if len(self.allocations) < self.frames:    
            index = len(self.allocations)
            allocation_tuple = (pid, program_page, index)
            self.allocations.append(allocation_tuple)
            #print(f"Escribiendo programa al final de Memoria: {index}")
            return index, None

        if self.replace_criteria == "FIFO":
            removed = self.allocations.popleft()
        else: # Case when it is LIFO
            removed = self.allocations.pop()
        
        allocated_frame = removed[2] # Get index assignated from previous
        allocation_tuple = (pid, program_page, allocated_frame)
        self.allocations.append(allocation_tuple)
        
        previous_program = (removed[0], removed[1]) # Return only pid and page

        #print(f"Memory Replace: {removed[0]} -> {pid} en Marco {allocated_frame}")
        return allocated_frame, previous_program
    
    def print_table(self, program_map):
        table = PrettyTable()
        table.title = "Memory Table"
        table.field_names = ["Frame", "PID", "Program", "Page"]
        table.sortby = "Frame"
        for pid, page, index in self.allocations:
            table.add_row([index, pid, program_map[pid].name, page])
        print(table)
        

    def __repr__(self):
        sorted_deque = list(self.allocations)
        sorted_deque.sort(key=lambda tup: tup[2])
        return str(sorted_deque)
