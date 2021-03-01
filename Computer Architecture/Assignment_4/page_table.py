from prettytable import PrettyTable

class TableEntry:
    def __init__(self, frame):
        self.frame = frame
        self.valid = True
        self.disk = False
        
    def __repr__(self):
        return f"Marco: {self.frame}, valido: {self.valid}, disco: {self.disk}"
    
class PageTable:
    def __init__(self, lines):
        self.table = {}
        self.lines = lines
        
    def map_present(self, page_number):
        if page_number not in self.table:
            return False # Page fault!
        
        entry = self.table[page_number]
        
        if not entry.valid or entry.disk:
            return False # Page Fault!

        return True
    
    def get_frame(self, page_number):
        return self.table[page_number].frame
    
    def add_entry(self, page_number, frame_number):
        if page_number in self.table or len(self.table) >= self.lines:
            print("PRECAUCION! ERROR AL AGREGAR A TABLA DE PAGINAS")
            return
        
        new_line = TableEntry(frame_number)
        self.table[page_number] = new_line
        
        
    def is_in_disk(self, frame_number):
        if frame_number not in self.table:
            return False
        
        return self.table[frame_number].disk
           
    def invalidate(self, frame_number):
        for entry in self.table.values():
            if entry.frame == frame_number:
                entry.valid = False
    
    def mark_disk(self, page_number):
        self.table[page_number].disk = True
        
    def mark_not_disk(self, page_number):
        self.table[page_number].disk = False
        
    def mark_memory(self, page_number):
        self.table[page_number].disk = False
        
        
    def print_table(self, pid, program_name):
        table = PrettyTable()
        table.title = f"Page Table of {program_name} : {pid}"
        table.field_names = ["Page", "Frame", "Valid", "Disk"]
        table.sortby = "Page"
        for page, line in self.table.items():
            table.add_row([page, line.frame, int(line.valid), int(line.disk)])            
             
        # for _ in range(self.lines - len(self.table)):
        #     table.add_row(["   " for _ in range(4)]) # Add empty line!
        print(table)
        
        
    def __repr__(self):
        tuplas = list(self.table.items())
        tuplas.sort(key=lambda tup: tup[0])
        return str(tuplas)