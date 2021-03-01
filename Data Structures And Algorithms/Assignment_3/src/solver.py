from puzzle import Puzzle
from watcher.watcher import Watcher
import sys


if len(sys.argv) != 3:
  print(f"Modo de uso: python3.6+ {sys.argv[0]} input_file output_file")
  print("Donde")
  print("\tinput_file es un archivo del test a resolver")
  print("\toutput_file es el archivo donde el programa guardará el output")
  exit(1)

# El primer parámetro es el archivo de input
input_file = sys.argv[1]

# El segundo parámetro es el archivo de output
output_file = sys.argv[2]

with open(input_file, "r") as puzzle_file:
  height      = int(puzzle_file.readline().strip())
  width       = int(puzzle_file.readline().strip())
  col_numbers = [int(x) for x in puzzle_file.readline().strip().split(" ")]  
  row_numbers = [int(x) for x in puzzle_file.readline().strip().split(" ")]
  tank_count  = int(puzzle_file.readline().strip())

  matrix = [[int(x) for x in puzzle_file.readline().strip().split(" ")] 
          for y in range(height)]

  puzzle = Puzzle(width, height, row_numbers, col_numbers, tank_count, matrix)
  puzzle.generate()

  ans = puzzle.solve()

  if ans is None:
    print("El puzzle no tiene solucion.")
    exit(1)

  with open(output_file, "w", encoding="utf-8") as file:
    for line in ans:
      file.write(" ".join([str(num) for num in line]) + "\n")