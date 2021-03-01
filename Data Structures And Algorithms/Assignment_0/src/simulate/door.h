#pragma once

#include <stdbool.h>

#include "queue.h"

typedef struct door {
  Queue* queue;
  int pod_count;
  int id;
  bool open;
} Door;

Door* CreateDoor(int door_id);

void DestroyDoor(Door* door);

void CloseDoor(Door* door, Passenger*** passen_arr, int* out_size);

int GetQueueLength(Door* door);

void InsertPassengerDoor(Door* door, Passenger* passenger);

void AbordDoor(Door* door, int** ids_arr);