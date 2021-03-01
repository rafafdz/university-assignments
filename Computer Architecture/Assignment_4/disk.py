from prettytable import PrettyTable

class Disk:

    def __init__(self, write_speed, read_speed):
        self.write_speed = write_speed
        self.read_speed = read_speed
        self.page_storage = set()
        self.read_counter = 0
        self.write_counter = 0

    def is_stored(self, pid, page_number):
        return (pid, page_number) in self.page_storage

    def read(self):
        self.read_counter += 1

    def push(self, pid, page_number):
        self.page_storage.add((pid, page_number))
        
    def pop(self, pid, page_number):
        self.page_storage.remove((pid, page_number))
        
        
    def print_table(self, program_map):
        table = PrettyTable()
        table.title = "Disk Table"
        table.field_names = ["PID", "Program", "Page"]
        table.sortby = "PID"
        for pid, page in self.page_storage:
            table.add_row([pid, program_map[pid].name, page])
             
        # for _ in range(self.lines - len(self.table)):
        #     table.add_row(["   " for _ in range(4)]) # Add empty line!
        print(table)
        
        

    def __repr__(self):
        return f"Disco: {self.page_storage}"