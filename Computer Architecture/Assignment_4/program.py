from collections import deque
from page_table import PageTable

class Program:
    
    PROGRAM_ID = 0

    def __init__(self, name, sequence, table_lines):
        self.name = name
        self.sequence = deque(sequence)
        self.remaining = self.sequence.copy()
        self.id = Program.PROGRAM_ID
        self.page_table = PageTable(table_lines)
        Program.PROGRAM_ID += 1
        
    def has_next_direction(self):
        return bool(self.remaining)
    
    def next_direction(self):
        return self.remaining.popleft()
    
    def print_page_table(self):
        self.page_table.print_table(self.id, self.name)