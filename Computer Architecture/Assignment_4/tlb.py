from base_memory import BaseCache, TableEntry
from time import time
from prettytable import PrettyTable

class TLBTableBlock (TableEntry):
    
    FIFO_COUNTER = 0

    def __init__(self, page, frame):
        super().__init__(page)
        self.frame = frame
        self.fifo_order = TLBTableBlock.FIFO_COUNTER
        TLBTableBlock.FIFO_COUNTER += 1

class TLB(BaseCache):

    def __init__(self, replace_crit, lines, write_speed, read_speed):

        super().__init__(replace_crit, "FA", write_speed,
                         read_speed, TLBTableBlock)
        
        self.lines = lines
        self.table = [None for _ in range(self.lines)]

    def table_section_from_parameter(self, *params):
        return self.table
    
    def table_row_args_from_parameter(self, *params):
        # The parameters are just bypassed: page and frame
        return params
    
    def index_mapping_from_parameter(self, index, *params):
        return index

    def id_from_parameter(self, param):
        return param
    
    def invalidate_entries(self):
        for table_line in self.table:
            if table_line is not None:
                table_line.invalidate()
        
    def get_element(self, param):
        section = self.table_section_from_parameter(param)
        tag = self.id_from_parameter(param)
        
        for table_row in section:
            if table_row.tag == tag:
                return table_row.frame
            
    def print_table(self):
        table = PrettyTable()
        table.title = f"TLB Table - {self.replace_criteria}"
        
        table.field_names = ["Index", "Page", "Frame", "Valid", "Order", "Access", "Time"]
                
        for index, entry in enumerate(self.table):
            if entry is None:
                continue
            
            table.add_row([index, entry.tag, entry.frame, int(entry.valid),
                           entry.fifo_order, entry.access_counter, entry.last_access])
            
        print(table)
        




    