#include "door.h"

#include <stdio.h>
#include <stdlib.h>

#include "passenger.h"

Door* CreateDoor(int door_id) {
  Door* door = malloc(sizeof(Door));
  door->id = door_id;
  door->queue = CreateQueue();
  door->pod_count = 0;
  door->open = true;
  return door;
}

void DestroyDoor(Door* door) {
  DestroyQueue(door->queue);
  free(door);
}

int GetQueueLength(Door* door) { return GetLength(door->queue); }

void InsertPassengerDoor(Door* door, Passenger* passenger) {
  InsertPassengerQueue(door->queue, passenger);
}

void AbordDoor(Door* door, int** ids_arr) {
  int passen_waiting = GetQueueLength(door);
  if (passen_waiting < 8) {
    fprintf(stderr,
            "Errror while abording on door %d. Only "
            "%d passengers.\n",
            door->id, passen_waiting);
    exit(1);
  }
  // Asumme that there are 8 people at least
  for (int i = 0; i < 8; i++) {
    Passenger* removing = PopFirstQueue(door->queue);
    (*ids_arr)[i] = removing->id;
    DestroyPassenger(removing);
  }
  door->pod_count++;
}

void CloseDoor(Door* door, Passenger*** passen_arr, int* out_size) {
  PopAllQueue(door->queue, passen_arr, out_size);
  door->open = false;
}