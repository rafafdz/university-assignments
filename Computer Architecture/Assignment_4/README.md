# Tarea 4 :computer: :computer: 

Para esta tarea hice los siguientes supuestos:
* Como en la issue #194 se dijo que a lo más se lee y se escribe una vez en cada componente por cada acceso a una dirección de memoria, no conté los accesos a las tablas de páginas de cada programa como lectura de memoria principal, ya que esta última siempre será leída al obtener la dirección física.
* Se asume que todos los accesos a memoria que se entregan en los archivos son de **Lectura**, por lo que al acceder a la memoria principal se toma en cuenta como lectura y no escritura. (A excepción cuando se pide comparar con *Write-Through*, que en ese caso se escribe también en memoria).

### Archivo a Ejecutar:
```simulation.py```

### Feature extra:
Para hacer más fácil el proceso de verificar si mi simulación funciona correctamente, agregué la funcionalidad de mostrar los estados de cada componente en la terminal usando unas lindas **tablas**.
Para activar esta feature, simplemente hay que agregar el argumento **--show-simulation** a los argumentos de la línea de comandos. Ejemplo: ```python3 simulation.py test1.json out.csv --show-simulation```

