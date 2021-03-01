#include "passenger.h"

#include <stdio.h>
#include <stdlib.h>

Passenger* CreatePassenger(int id, enum Type type) {
  Passenger* passenger = malloc(sizeof(Passenger));
  passenger->id = id;
  passenger->type = type;
  return passenger;
}

void DestroyPassenger(Passenger* passenger) { free(passenger); }

void PrintPassenger(Passenger* passenger) { printf("%i", passenger->type); }