from tank import HorizontalTile, Tank
from watcher.watcher import Watcher
from collections import deque

DEBUG = False


class Puzzle:
    
    def __init__(self, width, height, num_rows, num_cols, tank_count, matrix):
        self.width = width
        self.height = height
        self.num_rows = num_rows
        self.num_cols = num_cols
        self.matrix = matrix
        self.tank_count = tank_count
        self.tanks = {}
        self.horizontal_tiles_cols = [[] for _ in range(self.width)]
        self.horizontal_tiles_rows = [[] for _ in range(self.height)]
        self.unnasigned = deque()
        
        self.current_sum_cols = [0 for _ in range(self.width)]
        self.current_sum_rows = [0 for _ in range(self.height)]
        
        self.tanks_by_cols = [set() for _ in range(self.width)]
        self.tanks_by_rows = [set() for _ in range(self.height)]
        
        
        self.undos = 0
        
    def generate(self):
        # create appropiate data structures
        for row_num, ids_row in enumerate(self.matrix):
            curr_id = ids_row[0]
            curr_tile = HorizontalTile(0, row_num)
            for col_num, _id in enumerate(ids_row):                                
                if _id != curr_id: # When tile complete
                    self._add_tile_to_tank(curr_id, curr_tile, col_num, row_num)
                    self.horizontal_tiles_rows[row_num].append(curr_tile)
                    curr_id = _id
                    curr_tile = HorizontalTile(col_num, row_num)
                    
                self.horizontal_tiles_cols[col_num].append(curr_tile)
                curr_tile.width += 1
        
            # add last tile!
            self._add_tile_to_tank(curr_id, curr_tile, col_num, row_num)
            self.horizontal_tiles_rows[row_num].append(curr_tile)
                
        for tank in self.tanks.values():
            tank.finish_tank_creation()
            for row in range(tank.min_y, tank.max_y + 1):
                self.tanks_by_rows[row].add(tank)
                
            for col in range(tank.min_x, tank.max_x + 1):
                self.tanks_by_cols[col].add(tank)
                    
    def _add_tile_to_tank(self, tank_id, tile, x, y):
        if tank_id not in self.tanks:
            # Add new tank!
            self.tanks[tank_id] = Tank(tank_id, y)
        
        tank = self.tanks[tank_id]
        tank.add_horizontal_line(x, y, tile)
        
    def solve(self):
        if DEBUG:
            self._intialize_watcher()
            self._set_watcher_current_state()
            Watcher.update()
            input()
        
        self.unnasigned = list(self.tanks.values())
        
        self.unnasigned.reverse()       
        
        status = self._solve_recursive()

        if not status:
            return None
        return self._gen_output()
    
    def _solve_recursive(self, depth=0):
        if not self.unnasigned:
            return self.check_solution_full()
        
        tank = self.get_next_unnasigned()
        for level in self.get_possible_values(tank):
            # print("  " * depth + f"Tanque{tank.id} nivel {level}")
            mod_cols, mod_rows = self.assign(tank, level)
            # Asumiendo que los valores son crecientes en level!
            if not self._is_valid(tank, mod_cols, mod_rows):
                # print("not valid! bye")
                self.unnasign(tank, level)
                self.undos += 1
                continue                

            # input()
            if self._solve_recursive(depth + 1):
                return True
            
            
            self.unnasign(tank, level)
            self.undos += 1
            
        tank.clear_level()
        self.unnassign(tank)
        return False
        
    def get_next_unnasigned(self):
        return self.unnasigned.pop()
    
    def unnassign(self, tank):
        self.unnasigned.append(tank)
        
        
    def assign(self, tank, level):
        mod_cols = set()
        mod_rows = set()
        
        
        tank.set_level(level)
        if level == 0:
            return mod_cols, mod_rows
        
        
        for tiles in tank.tiles_row[tank.height - level:]:
            for tile in tiles:
                tile.filled = True
                for col in range(tile.x, tile.x + tile.width):
                    mod_cols.add(col)
                    self.current_sum_cols[col] += 1
                    
                mod_rows.add(tile.y)
                self.current_sum_rows[tile.y] += tile.width
                
        return mod_cols, mod_rows
    
    def unnasign(self, tank, level):
        tank.clear_level()
        for tiles in tank.tiles_row[tank.height - level:]:
            for tile in tiles:
                tile.filled = False
                for col in range(tile.x, tile.x + tile.width):
                    self.current_sum_cols[col] -= 1

                self.current_sum_rows[tile.y] -= tile.width
    
    # Should return in increasing order!
    def get_possible_values(self, tank):
        for i in range(tank.height, -1, -1):
            yield i
    
    # Check if the puzzle is is still valid after setting a level to a tank
    def _is_valid(self, tank, mod_cols, mod_rows):
        for row_idx in mod_rows:
            row_sum = self.current_sum_rows[row_idx]
            target = self.num_rows[row_idx]
            
            if self.all_row_assigned(row_idx) and row_sum != target:
                return False
            
            if row_sum > target:
                return False
            
        for col_idx in mod_cols:
            col_sum = self.current_sum_cols[col_idx]
            
            if self.all_col_assigned(col_idx) and col_sum != self.num_cols[col_idx]:
                return False
            
            
            if col_sum > self.num_cols[col_idx]:
                return False
            
            
        for row_idx in range(tank.min_y, tank.max_y + 1):
            row_sum = self.current_sum_rows[row_idx]
            target = self.num_rows[row_idx]
            
            if row_sum == target:
                continue
            
            all_assigned = self.all_row_assigned(row_idx)
            if all_assigned:
                return False
                        
            tanks_unnasigned = self.get_unassigned_row(row_idx)
            width = sum([tank.get_width_at_y(row_idx) for tank in tanks_unnasigned])
            # print(f"Fila con un tanque: {row_idx}")
            
            if len(tanks_unnasigned) == 1 and row_sum + width != target:
                return False
            
            
            if row_sum + width < target:
                return False

            
        for col_idx in range(tank.min_x, tank.max_x + 1):
            col_sum = self.current_sum_cols[col_idx]
            target = self.num_cols[col_idx]
            
            if col_sum == target:
                continue

            all_assigned = self.all_col_assigned(col_idx)
            if all_assigned:
                return False

            tanks_unnasigned = self.get_unassigned_col(col_idx)
            height = sum([tank.get_height_at_x(col_idx) for tank in tanks_unnasigned])
            # # print(f"Columna con un tanque: {col_idx}")
            
            if col_sum + height < target:
                return False
        
        return True
            
    def all_row_assigned(self, row_num):
        for tank in self.tanks_by_rows[row_num]:
            if tank.level is None:
                return False
        return True
    
    def all_col_assigned(self, col_num):
        for tank in self.tanks_by_cols[col_num]:
            if tank.level is None:
                return False
        return True
        
    def get_unassigned_col(self, col_num):
        ans = set()
        for tank in self.tanks_by_cols[col_num]:
            if tank.level is None:
                ans.add(tank)
        return ans
    
    def get_unassigned_row(self, row_num):
        ans = set()
        for tank in self.tanks_by_rows[row_num]:
            if tank.level is None:
                ans.add(tank)
        return ans
        
            
    def check_solution_col(self, col_num):
        return self.num_cols[col_num] == self.current_sum_cols[col_num]
        
    def check_solution_row(self, row_num):
        return self.num_rows[row_num] == self.current_sum_rows[row_num]
        
    def check_solution_full(self):
        for row in range(self.height):
            if not self.check_solution_row(row):
                return False
        
        for col in range(self.width):
            if not self.check_solution_col(col):
                return False
        return True
        
        
    def _gen_output(self):
        ans = [[None] * self.width for i in range(self.height)]
        
        for tank in self.tanks.values():
            for row_tiles in tank.tiles_row:
                for tile in row_tiles:
                    # print(f"Tile x: [{tile.x}, {tile.x + tile.width}], Tile y: {tile.y}")
                    for i in range(tile.x, tile.x + tile.width):
                        # Invert for gettin 1 on light matter
                        ans[tile.y][i] = int(not tile.filled)
                        # ans[tile.y][i] = tank.id
        
        return ans
    
    
    def _intialize_watcher(self):
        Watcher.open(self.height, self.width, 640)
        for row_num, number in enumerate(self.num_rows):
            Watcher.set_row_number(True, row_num, number)
        
        for col_num, number in enumerate(self.num_cols):
            Watcher.set_col_number(True, col_num, number) 
        
        for tank in self.tanks.values():
            for row_tiles in tank.tiles_row:
                for tile in row_tiles:
                    for i in range(tile.x, tile.x + tile.width):
                        Watcher.set_cell_tank(tile.y, i, tank.id)
            
    
    def _set_watcher_current_state(self):
        for tank in self.tanks.values():
            for row_tiles in tank.tiles_row:
                for tile in row_tiles:
                    for i in range(tile.x, tile.x + tile.width):
                        Watcher.set_cell_matter(tile.y, i, tile.filled)
                        
        for row_num, number in enumerate(self.current_sum_rows):
            Watcher.set_row_number(False, row_num, number)
        
        for col_num, number in enumerate(self.current_sum_cols):
            Watcher.set_col_number(False, col_num, number) 
