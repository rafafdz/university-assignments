import sys
from puzzle import Puzzle

from time import time



OUT_FILE = "output.txt"

debug = len(sys.argv) > 1



for i in range(20):

    IN_FILE = f"tests/dificil_{i}.txt"
    print("Resolviendo: ", IN_FILE)
    
    start_time = time()
    # Leemos el archivo de input
    with open(IN_FILE, "r") as puzzle_file:
        height      = int(puzzle_file.readline().strip())
        width       = int(puzzle_file.readline().strip())
        col_numbers = [int(x) for x in puzzle_file.readline().strip().split(" ")]  
        row_numbers = [int(x) for x in puzzle_file.readline().strip().split(" ")]
        tank_count  = int(puzzle_file.readline().strip())

        matrix = [[int(x) for x in puzzle_file.readline().strip().split(" ")] 
                for y in range(height)]


    # for row in matrix:
    #     print(row)

    print("Col numbers:", col_numbers)

    puzzle = Puzzle(width, height, row_numbers, col_numbers, tank_count, matrix)
    puzzle.generate()

    print("#" * 30)

    ans = puzzle.solve()
    for line in ans:
        # print(line)
        pass
    print("Undos: ", puzzle.undos)
    
    print(f"Resuelto en {round(time() - start_time, 4)} segundos")

    # Agregamos cada celda al estanque que le corresponde
    
                
        
        
        
        

