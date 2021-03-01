#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <stdbool.h>

#include "terminal.h"
#include "events.h"

/** Retorna true si ambos strings son iguales */
bool string_equals(char* string1, char* string2)
{
  return !strcmp(string1, string2);
}

void simulate(char* filename)
{
  // Abrimos el archivo
  FILE* file = fopen(filename, "r");

  // Leemos la cantidad de terminales
  int terminal_count;
  fscanf(file, "%i", &terminal_count);

  // TODO: Inicializar estructura principal
  // printf("#terminales: %i\n", terminal_count);

  Terminal* terminals[terminal_count];
 
  for (int term = 0; term < terminal_count; term++)
  {
    int gate_count;
    fscanf(file, "%i", &gate_count);
    // TODO: Inicializar terminal
    // printf("terminal #%i: %i puertas\n", term, gate_count);

    terminals[term] = CreateTerminal(term, gate_count);
  }

  char command[32];

  while(true)
  {
    fscanf(file, "%s", command);

    if(string_equals(command, "END"))
    {
      break;
    }
    else if(string_equals(command, "INGRESO"))
    {
      int terminal, passenger_id, priority;

      fscanf(file, "%i %i %i", &terminal, &passenger_id, &priority);

      // TODO: Procesar ingreso
      // printf("Ingreso de pasajero %i a terminal %i con prioridad %i\n", passenger_id, terminal, priority);
      
      Terminal* curr_terminal = terminals[terminal];
      Entry(curr_terminal, passenger_id, priority);
    }
    else if(string_equals(command, "ABORDAJE"))
    {
      int terminal, gate;
      fscanf(file, "%i %i", &terminal, &gate);

      // TODO: Procesar abordaje
      // printf("Inicio del abordaje en el terminal %i puerta %i\n", terminal, gate);
      Terminal* curr_terminal = terminals[terminal];
      Abord(curr_terminal, gate);
    }
    else if(string_equals(command, "CIERRE"))
    {
      int terminal, gate;
      fscanf(file, "%i %i", &terminal, &gate);

      // TODO: Procesar cierre de puerta
      // printf("Cierre de puerta %i en terminal %i\n", gate, terminal);
      Terminal* curr_terminal = terminals[terminal];
      Close(curr_terminal, gate);
    }
    else if(string_equals(command, "CLAUSURA"))
    {
      int term_out, term_in;
      fscanf(file, "%i %i", &term_out, &term_in);

      // TODO: Procesar clausura de terminal
      // printf("Clausura de terminal %i, todos los pasajeros proceder al terminal %i\n", term_out, term_in);
      Terminal* terminal1 = terminals[term_out];
      Terminal* terminal2 = terminals[term_in];
      Closure(terminal1, terminal2);
    }
    else if(string_equals(command, "LASER"))
    {
      int terminal, gate, index;
      fscanf(file, "%i %i %i", &terminal, &gate, &index);

      // TODO: Procesar laser
      // printf("Laser perdido impacta a la persona en indice %i de la fila para la puerta %i del terminal %i\n", index, gate, terminal);
      Terminal* curr_termianl = terminals[terminal];
      Laser(curr_termianl, gate, index);
    }
  }

  fclose(file);
  printf("TITANIC LOG\n");
    // TODO: Liberar recursos e imprimir los pasajeros que siguen a bordo
  for (int term_idx = 0; term_idx < terminal_count; term_idx++) {
    Terminal* curr_terminal = terminals[term_idx];
    PrintReport(curr_terminal);
    DestroyTerminal(curr_terminal);
  }
  printf("END LOG\n");
}

  int main(int argc, char* argv[]) {
    // Este programa recibe dos parámetros:
    //  argv[0] = el programa en sí
    //  argv[1] = la ruta al archivo de input
    if (argc != 2) {
      printf("Cantidad de parámetros incorrecta\n");
      printf("Uso correcto: %s PATH_TO_INPUT\n", argv[0]);
      return 1;
    }

    simulate(argv[1]);

    return 0;
}
