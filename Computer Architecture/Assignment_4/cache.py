from base_memory import BaseCache, TableEntry
from utilities import decimal_to_binary, binary_to_decimal
from math import log
from time import time
from prettytable import PrettyTable

class CacheTableBlock(TableEntry):

    FIFO_COUNTER = 0
    
    def __init__(self, tag, section):
        super().__init__(tag)
        self.section = section # Will be 0 for FA
        self.fifo_order = CacheTableBlock.FIFO_COUNTER
        CacheTableBlock.FIFO_COUNTER += 1

    def __repr__(self):
        mem_dir = f"Tag {self.tag}, section {self.section}"
        return f"Tag: {self.tag}, Valid: {self.valid}, Dirs: ({mem_dir})"


class Cache(BaseCache):

    def __init__(self, replace_crit, correspondance, nway_size, size,
                 write_speed, read_speed):

        super().__init__(replace_crit,  correspondance, 
                         write_speed, read_speed, CacheTableBlock)
        self.size = size
        self.blocks = self.size // 2
        
        simulated_fa = self.correspondance == "NWA" and nway_size == 1
        if self.correspondance == "FA" or simulated_fa:
            self.sections = 1
            self.nway_size = self.blocks
        else:
            self.sections = self.blocks / nway_size
            self.nway_size = nway_size

        self.bits_sections = int(log(self.sections, 2))
        self.table = [None for _ in range(self.blocks)]


    def _address_split(self, address):
        """Return section id and tag from memory address"""
        if self.correspondance == "FA" or self.bits_sections == 0:
            section_number = 0
            tag = address[: -1]
        else:
            section_binary = address[- self.bits_sections -1 : -1]
            section_number = binary_to_decimal(section_binary)
            tag = address[: -self.bits_sections]

        return tag, section_number

    def table_section_from_parameter(self, param):
        _, section_number = self._address_split(param)
        index_start = section_number * self.nway_size
        index_finish = (section_number + 1) * self.nway_size 
        return self.table[index_start : index_finish + 1]

    def table_row_args_from_parameter(self, param):
        return self._address_split(param)

    def index_mapping_from_parameter(self, index, param):
        _, section_number = self._address_split(param)
        return section_number * self.nway_size + index
    
    def id_from_parameter(self, param):
        tag, _ = self._address_split(param)
        return tag
    
    def invalidate_range(self, range_min, range_max):
        for entry in self.table:
            if entry is None:
                continue
            
            section_binary = decimal_to_binary(entry.section, self.bits_sections)
            dir_decimal = binary_to_decimal(section_binary + entry.tag + "0")
            if range_min <= dir_decimal < range_max:
                entry.valid = False
            
    
    def invalidate_entries(self): # Duplicated code!
        for table_line in self.table:
            if table_line is not None:
                table_line.invalidate()
                
                
    def print_table(self):
        table = PrettyTable()
        table.title = (f"Cache Table {self.correspondance} {self.nway_size} - " +
                       f"{self.replace_criteria}")
        
        table.field_names = ["Index", "Section", "Valid", "Tag", "Direction",
                             "Order", "Access", "Time"]
        
        table.align["Tag"] = "l"
                
        for index, entry in enumerate(self.table):
            if entry is None:
                continue
            
            tag = f"{entry.tag} ({binary_to_decimal(entry.tag + '0')})"
            direction = binary_to_decimal(decimal_to_binary(entry.section) + entry.tag + "0")
            
            table.add_row([index, entry.section, int(entry.valid), tag, direction,
                           entry.fifo_order, entry.access_counter, entry.last_access])
            
        print(table)

        # for _ in range(self.lines - len(self.table)):
        #     table.add_row(["   " for _ in range(4)]) # Add empty line!