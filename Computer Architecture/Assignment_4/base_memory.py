from abc import ABC, abstractmethod
from time import time
from prettytable import PrettyTable

def first_in_first_out(table):
        """Called when the are no empty blocks. Returns the index"""
        min_order = table[0].fifo_order
        min_index = 0
        for index, table_line in enumerate(table):
            if table_line.fifo_order < min_order:
                min_order = table_line.fifo_order
                min_index = index

        return min_index

def least_recently_used(table):
    actual_time = time()
    max_diff = 0
    max_diff_count = 0
    max_index = 0
    for index, table_line in enumerate(table):
        difference = actual_time - table_line.last_access
        if difference > max_diff:
            max_diff = difference
            max_index = index
            max_diff_count = 0

        max_diff_count += int(max_diff == difference)

    return max_index


def least_frequently_used(table):
    min_access = 0
    min_access_list = []
    min_index = 0
    for index, table_line in enumerate(table):

        count = table_line.access_counter
        if count > min_access:
            min_access = count
            min_index = index
            min_access_list = []                

        if min_access == count:
            min_access_list.append(table_line)            

    if len(min_access_list) > 1:
        # Desempate!
        to_remove_index = first_in_first_out(min_access_list)
        to_remove = min_access_list[to_remove_index]
        return table.index(to_remove)
    
    return min_index

REPLACE_DICT =  {"LRU" : least_recently_used,
                 "LFU" : least_frequently_used,
                 "LIFO": first_in_first_out}

class AddressException(Exception):
    pass

class TableEntry:
    """Made to reuse code between cache and tlb tables entries"""
    def __init__(self, tag):
        self.tag = tag
        self.valid = True
        self.last_access = time()
        self.access_counter = 0

    def access(self):
        self.last_access = time()
        self.access_counter += 1
        
    def invalidate(self):
        self.valid = False


class BaseMemory:
    def __init__(self, replace_crit, correspondance, write_speed, read_speed):
        self.replace_criteria = replace_crit
        self.correspondance = correspondance
        self.write_speed = write_speed
        self.read_speed = read_speed
        self.hit_counter = 0
        self.miss_counter = 0
        self.read_counter = 0
        self.write_counter = 0


class BaseCache(BaseMemory, ABC):

    def __init__(self, replace_crit, correspondance, 
                 write_speed, read_speed, table_row_class):
            
        super().__init__(replace_crit, correspondance, 
                         write_speed, read_speed)
    
        self.next_replace = REPLACE_DICT[replace_crit]
        self.table_row_class = table_row_class
        self.table = []

    def read(self, search_param):
        self.read_counter += 1
        return self.abstract_read(search_param)

    def write(self, *search_params):
        self.write_counter += 1
        self.abstract_write(*search_params)

    @abstractmethod
    def table_section_from_parameter(self, *param):
        pass

    @abstractmethod
    def table_row_args_from_parameter(self, *param):
        pass

    @abstractmethod
    def index_mapping_from_parameter(self, index, *param):
        """Should return the table index where to store the write"""
        pass

    @abstractmethod
    def id_from_parameter(self, param):
        pass
    
    @abstractmethod    
    def print_table(self):
        pass
    

    def abstract_read(self, search_param):
        """Receives a memory direction. Returns True or False if exists"""
        
        #print("Search", search_param)
        section = self.table_section_from_parameter(search_param)
        tag = self.id_from_parameter(search_param)

        for table_row in section:
            # Assuming that if first word in a block is valid, the other is too
            if table_row is None:
                continue

            if table_row.tag == tag and table_row.valid:
                table_row.access()
                return True
        
        return False

    def abstract_write(self, *params):
        """Receives a memory direction and writes te corresponding block"""        
        section = self.table_section_from_parameter(*params)
        row_args = self.table_row_args_from_parameter(*params)
        new_row = self.table_row_class(*row_args)
        # IMPORTANTE! ASUMO QUE UN BLOQUE INVALIDO ES IGUAL A UNO VACIO!
        # TO DO: PREGUNTAR EN ISSUE
        found_index = False
        for index, table_row in enumerate(section.copy()):    
            if table_row is None or not table_row.valid:
                found_index = True
                overwrite_index = index
                break

        if not found_index:
            overwrite_index = self.next_replace(section)

        replace_index = self.index_mapping_from_parameter(overwrite_index, *params)


        old_line = self.table[replace_index]
        #print(f"{self.__class__.__name__} Replace -> Index: {replace_index}")

        # Perfom the actual write
        self.table[replace_index] = new_row
            
    def mark_hit(self):
        self.hit_counter += 1
        
    def mark_miss(self):
        self.miss_counter += 1
            
    def __repr__(self):        
        return self.__class__.__name__ + ": " + str(self.table)