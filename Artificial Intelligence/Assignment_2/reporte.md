# Reporte Tarea 2

## Explicacion Heurística

La heurística alternativa funciona de la siguiente manera: En un estado cualquiera, para cada numero que no esté en la posición donde debería estar en el estado final, se calculan el mínimo de movimientos verticales para posicionarlo en su fila correspondiente y el mínimo de movimientos horizontales para posicionarlo en su columna correspondiente. Se suman estos valores y finalmente se entrega la suma para cada una de las posiciones en el estado del tablero. De esta forma, se obtiene una estimación de los pasos necesarios para completar el puzzle.


## Resultados con Problema 7

Todos los algoritmos fueron configurados con TimeBound = 20 segs

### A* con Heuristica basica: No Termina

### A* con Heuristica alternativa: 1.53 segs

### RWA* con Heuristica basica: No termina

### RWA* con Heuristica basica: 12.52 segs
