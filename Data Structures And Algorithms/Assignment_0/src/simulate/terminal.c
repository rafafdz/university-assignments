#include "terminal.h"

#include <stdbool.h>
#include <stdio.h>
#include <stdlib.h>

#include "door.h"
#include "queue.h"
#include "passenger.h"

Terminal* CreateTerminal(int id, int gates) {
  Terminal* terminal = malloc(sizeof(Terminal));
  terminal->id = id;
  terminal->open = true;
  terminal->door_count = gates;
  terminal->doors = malloc(sizeof(Door*) * gates);
  for (int door_idx = 0; door_idx < gates; door_idx++) {
    Door* door = CreateDoor(door_idx);
    terminal->doors[door_idx] = door;
  }
  return terminal;
}

void DestroyTerminal(Terminal* terminal) {
  for (int door_idx = 0; door_idx < terminal->door_count; door_idx++) {
    Door* door = terminal->doors[door_idx];
    DestroyDoor(door);
  }
  free(terminal->doors);
  free(terminal);
}

int OpenDoorCount(Terminal* terminal) {
  int count = 0;
  for (int door_idx = 0; door_idx < terminal->door_count; door_idx++) {
    count += terminal->doors[door_idx]->open;
  }
  return count;
}

void InsertPassengerTerminal(Terminal* terminal, Passenger* passenger) {
  if (!OpenDoorCount(terminal)) {
    fprintf(stderr,
            "Error! Trying to add Passenger to Terminal "
            "%d with doors closed\n",
            terminal->id);
    exit(1);
  }

  Door* min_door;
  int min_idx = -1;
  for (int door_idx = 0; door_idx < terminal->door_count; door_idx++) {
    Door* curr_door = terminal->doors[door_idx];

    if(!curr_door->open){
      continue;
    }

    int pos = GetNextPosition(curr_door->queue, passenger->type);

    if (min_idx == -1 || pos < min_idx) {
      min_idx = pos;
      min_door = curr_door;
    }
  }
  InsertPassengerDoor(min_door, passenger);
}

void AbordTerminalDoor(Terminal* terminal, int door_index, int** ids_arr) {
  if (door_index >= terminal->door_count) {
    fprintf(stderr, "Door index %d not found in Terminal %d", door_index,
           terminal->id);
    exit(1);
  }
  Door* door = terminal->doors[door_index];
  AbordDoor(door, ids_arr);
}

void CloseTerminalDoor(Terminal* terminal, int door_index) {
  Passenger** passen_arr;
  int passen_size;

  Door* door = terminal->doors[door_index];
  CloseDoor(door, &passen_arr, &passen_size);

  for (int i = 0; i < passen_size; i++) {
    Passenger* passenger = passen_arr[i];
    InsertPassengerTerminal(terminal, passenger);
  }
  free(passen_arr);
}

bool PeopleWaiting(Terminal* terminal) {
  for (int door_idx = 0; door_idx < terminal->door_count; door_idx++) {
    Door* door = terminal->doors[door_idx];
    if (GetQueueLength(door) > 0) {
      return true;
    }
  }
  return false;
}

void ClosureTerminal(Terminal* term_from, Terminal* term_to) {
  // Dumb linear implementation
  Queue* new_queue = CreateQueue();

  while (PeopleWaiting(term_from)) {
    for (int door_idx = 0; door_idx < term_from->door_count; door_idx++) {
      Door* door = term_from->doors[door_idx];
      if (!door->open || !GetQueueLength(door)) {
        continue;
      }
      Passenger* passen = PopFirstQueue(door->queue);
      InsertPassengerQueue(new_queue, passen);
    }
  }
  term_from->open = false;
  // new Queue is done. Insert people in terminal2
  while (!isEmpty(new_queue)) {
    Passenger* new_passen = PopFirstQueue(new_queue);
    InsertPassengerTerminal(term_to, new_passen);
  }
  DestroyQueue(new_queue);
}

void KillPerson(Terminal* terminal, int door_id, int index){
  Door* door = terminal->doors[door_id];
  Passenger* removed = PopIndex(door->queue, index);
  DestroyPassenger(removed);
}

void PrintReport(Terminal* terminal) {
  if (!terminal->open) {
    return;
  }
  printf("TERMINAL %d\n", terminal->id);

  for (int door_idx = 0; door_idx < terminal->door_count; door_idx++) {
    Door* curr_door = terminal->doors[door_idx];

    if (curr_door->open) {
      int queue_length = GetQueueLength(curr_door);
      printf("GATE %d: %d\n", curr_door->id, queue_length);
      Node* actual_node = curr_door->queue->head;

      while (actual_node) {
        printf("%d\n", actual_node->passenger->id);
        actual_node = actual_node->next;
      }
    }
  }
}