#include "queue.h"

#include <stddef.h>
#include <stdio.h>
#include <stdlib.h>

Node* CreateNode(Passenger* passenger) {
  Node* node = malloc(sizeof(Node));
  node->passenger = passenger;
  node->next = NULL;
  return node;
}

void DestroyNode(Node* node) { free(node); }

Queue* CreateQueue() {
  Queue* queue = malloc(sizeof(Queue));
  queue->head = NULL;
  queue->last_child = NULL;
  queue->last_adult = NULL;
  queue->tail = NULL;
  queue->last_child_index = -1;
  queue->last_adult_index = -1;
  queue->last_robot_index = -1;
  return queue;
}

void DestroyQueue(Queue* queue) {
  Node* next_node = queue->head;
  while (next_node) {
    Node* next = next_node->next;
    DestroyPassenger(next_node->passenger);
    DestroyNode(next_node);
    next_node = next;
  }
  free(queue);
}

int GetLength(Queue* queue) { return queue->last_robot_index + 1; }

bool isEmpty(Queue* queue) { return GetLength(queue) == 0; }

void InsertFirst(Queue* queue, Node* node) {
  Node* old_head = queue->head;
  node->next = old_head;
  queue->head = node;
}

void InsertAfter(Node* parent, Node* child) {
  Node* old_neighbor = parent->next;
  parent->next = child;
  if (old_neighbor) {
    child->next = old_neighbor;
  }
}
void InsertChildQueue(Queue* queue, Passenger* child) {
  Node* new_node = CreateNode(child);
  if (queue->last_child) {
    InsertAfter(queue->last_child, new_node);
  } else {
    InsertFirst(queue, new_node);
  }
  queue->last_child = new_node;
  queue->last_child_index++;
  queue->last_adult_index++;
  queue->last_robot_index++;
}

void InsertAdultQueue(Queue* queue, Passenger* adult) {
  Node* new_node = CreateNode(adult);
  if (queue->last_adult) {
    InsertAfter(queue->last_adult, new_node);
  } else if (queue->last_child) {
    InsertAfter(queue->last_child, new_node);
  } else {
    InsertFirst(queue, new_node);
  }
  queue->last_adult = new_node;
  queue->last_adult_index++;
  queue->last_robot_index++;
}

void InsertRobotQueue(Queue* queue, Passenger* robot) {
  Node* new_node = CreateNode(robot);
  // There were robots before
  if (queue->tail) {
    InsertAfter(queue->tail, new_node);
  }  // No robots but Adults
  else if (queue->last_adult) {
    InsertAfter(queue->last_adult, new_node);
  }  // No Robots nor Adults, but children
  else if (queue->last_child) {
    InsertAfter(queue->last_child, new_node);
  }  // Empty queue
  else {
    InsertFirst(queue, new_node);
  }
  queue->tail = new_node;
  queue->last_robot_index++;
}

void InsertPassengerQueue(Queue* queue, Passenger* passenger) {
  if (passenger->type == CHILD) {
    InsertChildQueue(queue, passenger);
  } else if (passenger->type == ADULT) {
    InsertAdultQueue(queue, passenger);
  } else {
    InsertRobotQueue(queue, passenger);
  }
}

void updateIndexesAfterPop(Queue* queue, int index) {
  queue->last_robot_index--;

  if (index <= queue->last_adult_index) {
    queue->last_adult_index--;
  }
  if (index <= queue->last_child_index) {
    queue->last_child_index--;
  }
}

Passenger* PopFirstQueue(Queue* queue) {
  if (isEmpty(queue)) {
    fprintf(stderr, "Trying to pop on an empty list!\n");
    exit(1);
  }
  Node* removing = queue->head;
  Node* old_second = removing->next;
  queue->head = old_second;

  if (removing == queue->last_child) {
    queue->last_child = NULL;
  } else if (removing == queue->last_adult) {
    queue->last_adult = NULL;
  } else if (removing == queue->tail) {
    queue->tail = NULL;
  }

  Passenger* removing_passenger = removing->passenger;
  DestroyNode(removing);
  updateIndexesAfterPop(queue, 0);
  return removing_passenger;
}

Passenger* PopIndex(Queue* queue, int index) {
  if (index >= GetLength(queue)) {
    fprintf(stderr, "Cant Pop Index %d on Queue of Length %d\n", index,
            GetLength(queue));
    exit(1);
  }

  if (index == 0) {
    return PopFirstQueue(queue);
  }
  Node* parent_node = queue->head;
  for (int i = 0; i < index - 1; i++) {
    parent_node = parent_node->next;
  }
  Node* removing = parent_node->next;
  Node* new_next = removing->next;

  parent_node->next = new_next;

  if (removing == queue->last_child) {
    queue->last_child = parent_node;
  } else if (removing == queue->last_adult) {
    if (parent_node->passenger->type == ADULT) {
      queue->last_adult = parent_node;
    } else {
      queue->last_adult = NULL;
    }
  } else if (removing == queue->tail) {
    if (parent_node->passenger->type == ROBOT) {
      queue->tail = parent_node;
    } else {
      queue->tail = NULL;
    }
  }
  updateIndexesAfterPop(queue, index);
  Passenger* passen_removing = removing->passenger;
  DestroyNode(removing);
  return passen_removing;
}

void PopAllQueue(Queue* queue, Passenger*** passen_arr, int* out_size) {
  int queue_size = GetLength(queue);
  Passenger** passen_list = malloc(sizeof(Passenger*) * queue_size);
  for (int i = 0; i < queue_size; i++) {
    passen_list[i] = PopFirstQueue(queue);
  }
  *passen_arr = passen_list;
  *out_size = queue_size;
}

int GetNextPosition(Queue* queue, enum Type type) {
  if (type == CHILD) {
    return queue->last_child_index + 1;
  } else if (type == ADULT) {
    return queue->last_adult_index + 1;
  } else {
    return queue->last_robot_index + 1;
  }
}

void PrintQueue(Queue* queue) {
  printf("Queue(%i): ", GetLength(queue));
  Node* next_node = queue->head;
  while (next_node) {
    PrintPassenger(next_node->passenger);
    printf("-");
    next_node = next_node->next;
  }
  printf(" LC(%d) - LA(%d) - LR(%d)\n", queue->last_child_index + 1,
         queue->last_adult_index + 1, queue->last_robot_index + 1);
}