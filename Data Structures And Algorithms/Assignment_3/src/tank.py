class HorizontalTile:
    
    def __init__(self, x, y):
        self.filled = False
        self.x = x
        self.y = y
        self.width = 0
    
class Tank:
    
    def __init__(self, _id, y):
        self.id = _id
        self.y = y
        self.level = None
        # List of lists that store horizontal Tiles
        self.tiles_row = []
        self.height = 0
        self.min_x = None
        self.max_x = None
        self.domain = None
        
        
    def add_horizontal_line(self, x, y, new_tile):
        tile_index = y - self.y
        if len(self.tiles_row) <= tile_index:
            self.height += 1
            self.tiles_row.append([])
        
        row = self.tiles_row[-1]
        row.append(new_tile)
        
        
    def clear_level(self):
        self.set_level(None)
        
    def get_width_at_y(self, y):
        tile_index = y - self.y
        tiles = self.tiles_row[tile_index]
        width_sum = 0
        for tile in tiles:
            width_sum += tile.width
        return width_sum
    
    def get_height_at_x(self, x):
        height_sum = 0
        for tiles in self.tiles_row:
            for tile in tiles:
                if tile.x  <= x <= tile.x + tile.width - 1:
                    height_sum += 1
        return height_sum
        
        
    def finish_tank_creation(self):
        self.min_y = self.y
        self.max_y = self.y + len(self.tiles_row) - 1
        
        self.min_x = min([tile.x for row_tiles in self.tiles_row 
                         for tile in row_tiles])
        
        self.max_x = max([tile.x + tile.width - 1 for row_tiles in self.tiles_row 
                          for tile in row_tiles])
        
        self.domain = {i for i in range(self.height + 1)}
        
    
    def set_level(self, level):
        self.level = level
        
    def __repr__(self):
        return (f"Tank(ID: {self.id}, Lvl: {self.level}, X: [{self.min_x},"
                f"{self.max_x}], Y: [{self.min_y}, {self.max_y}])")
