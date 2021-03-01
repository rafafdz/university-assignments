%%%%%% Instancia tarea %%%%%%
% #const x_max = 5.
% #const y_max = 5.
% color(verde, 3, 1).
% color(verde, 4, r1).
% color(azul, 5, 1).
% color(azul, 5, 3).
% color(blanco, 5, 4).
% color(blanco, 4, 5).
% color(amarillo, 3, 3).
% color(amarillo, 3, 4).
% color(morado, 3, 5).
% color(morado, 2, 4).


%%%%%%% Instancia Bonus %%%%%%
% #const x_max = 15.
% #const y_max = 15.

% color(blanco, 1, 4).
% color(blanco, 15, 10).
% color(verde_oscuro, 2, 8).
% color(verde_oscuro, 5, 2).
% color(cafe_claro, 1, 9).
% color(cafe_claro, 2, 10).
% color(rosado_claro, 1, 14).
% color(rosado_claro, 2, 9).
% color(morado, 1, 15).
% color(morado, 8, 9).
% color(azul_claro, 2, 7).
% color(azul_claro, 4, 3).
% color(naranjo, 3, 8).
% color(naranjo, 10, 13).
% color(celeste, 3, 14).
% color(celeste, 10, 5).
% color(rojo, 3, 15).
% color(rojo, 12, 15).
% color(cafe_oscuro, 5, 7).
% color(cafe_oscuro, 6, 5).
% color(rosado_oscuro, 5, 15).
% color(rosado_oscuro, 7, 15).
% color(turquesa, 6, 4).
% color(turquesa, 14, 14).
% color(turquesa, 6, 4).
% color(turquesa, 14, 14).
% color(azul, 7, 12).
% color(azul, 11, 13).
% color(amarillo, 8, 8).
% color(amarillo, 9, 7).
% color(verde, 9, 13).
% color(verde, 12, 14).
% color(gris, 12, 8).
% color(gris, 13, 3).

% columna(1..x_max).
% fila(1..y_max).

opcion_color(X) :- color(X, Y, Z).
nodo_conectado(Y, X) :- camino(C, Y, X, Y2, X2).
camino(C, Y1, X1, Y2, X2) :- camino(C, Y2, X2, Y1, X1).

mas_2_caminos(Y, X) :- camino(C1, Y + 1, X, Y, X), camino(C2, Y, X + 1, Y, X), camino(C3, Y, X - 1, Y, X).
mas_2_caminos(Y, X) :- camino(C1, Y + 1, X, Y, X), camino(C2, Y - 1, X, Y, X), camino(C3, Y, X + 1, Y, X).
mas_2_caminos(Y, X) :- camino(C1, Y, X - 1, Y, X), camino(C2, Y, X + 1, Y, X), camino(C3, Y - 1, X, Y, X).
mas_2_caminos(Y, X) :- camino(C1, Y + 1, X, Y, X), camino(C2, Y - 1, X, Y, X), camino(C3, Y, X - 1, Y, X).

extremo_color(C, Y1, X1, Y2, X2) :- camino(C, Y1, X1, Y2, X2), color(C, Y2, X2).
extremo_color(C, Y1, X1, Y2, X2) :- camino(C, Y1, X1, Y, X), extremo_color(C, Y, X, Y2, X2).



usado_otro_color(C1, Y1, X1, Y2, X2) :- opcion_color(C1),opcion_color(C2), C1 != C2, camino(C2, Y1, X1, Y2, X2).

% Generamos todas las combinaciones posibles 
1{ camino(C, Y, X, Y + 1, X) : fila(Y), fila(Y + 1), columna(X), not usado_otro_color(C, Y, X, Y + 1, X);
camino(C, Y, X, Y, X + 1) : fila(Y), columna(X), columna(X + 1), not usado_otro_color(C, Y, X, Y, X + 1)} :- opcion_color(C).


% Quitar caminos que no conducen a un color
:- camino(C, Y1, X1, Y, X), not extremo_color(C, Y1, X1, Y2, X2), color(C, Y2, X2).


% Quitar soluciones que empiezan y terminan en el mismo lugar
% :- camino(C, Y1, X1, Y1, X1).


% Quitar soluciones con caminos superpuestos
:- camino(C1, Y1, X1, Y2, X2), camino(C2, Y1, X1, Y2, X2), C1 != C2.


% Quitar soluciones con caminos que se bifurcan
:- mas_2_caminos(Y, X), nodo_conectado(Y, X).


% Que se use todos los espacios vacios

:- not nodo_conectado(Y, X), fila(Y), columna(X).


% Quitar soluciones con caminos infactibles
% :- camino(C, Y1, X1, Y2, X2), | Y1 - Y2 | > 1.

% Los escribo de nuevo porque no se si existe el OR!
% Quitar soluciones con caminos infactibles
% :- camino(C, Y1, X1, Y2, X2), | X1 - X2 | > 1.

% Quitar caminos en diagonal 
% :- camino(C, Y1, X1, Y2, X2), | X1 - X2 | == | Y1 - Y2 |.

% De cada color solo puede estar conectado un camino!
:- color(C, X, Y),camino(C1, X, Y, X2, Y2),camino(C2, X, Y, X3, Y3), par(X2,Y2) != par(X3, Y3).

#show camino/5.
