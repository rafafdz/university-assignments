#pragma once

#include <stdbool.h>

#include "passenger.h"

typedef struct passenger_node Node;

struct passenger_node {
  Node* next;
  Passenger* passenger;
};

typedef struct queue {
  Node* head;
  Node* last_child;
  Node* last_adult;
  Node* tail;
  int last_child_index;
  int last_adult_index;
  int last_robot_index;  // Will equal the length
} Queue;

Queue* CreateQueue();

void DestroyQueue(Queue* queue);

void InsertPassengerQueue(Queue* queue, Passenger* passenger);

int GetLength(Queue* queue);

int GetNextPosition(Queue* queue, enum Type type);

Passenger* PopFirstQueue(Queue* queue);

Passenger* PopIndex(Queue* queue, int index);

void PopAllQueue(Queue* queue, Passenger*** passen_arr, int* out_size);

bool isEmpty(Queue* queue);

Passenger* RemovePassengerIndex(Queue*, int pass_index);

Passenger* RemoveFirstPassenger(Queue*);

void PrintQueue(Queue* queue);