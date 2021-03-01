#include "events.h"

#include <stdio.h>

#include "queue.h"

void Entry(Terminal* terminal, int passsenger_id, enum Type type) {
  Passenger* new_passen = CreatePassenger(passsenger_id, type);
  InsertPassengerTerminal(terminal, new_passen);
}

void Abord(Terminal* terminal, int door_id) {
  int aborded_ids[8];
  // Dirty fix!
  int* pointer_aborded = &aborded_ids[0];
  AbordTerminalDoor(terminal, door_id, &pointer_aborded);

  int pod_index = terminal->doors[door_id]->pod_count - 1;
  printf("POD %d %d %d LOG\n", terminal->id, door_id, pod_index);
  for (int i = 0; i < 8; i++) {
    printf("%d\n", aborded_ids[i]);
  }
}

void Close(Terminal* terminal, int door_id) {
  CloseTerminalDoor(terminal, door_id);
}

void Closure(Terminal* terminal1, Terminal* terminal2) {
  ClosureTerminal(terminal1, terminal2);
}

void Laser(Terminal* terminal, int door_id, int index) {
  KillPerson(terminal, door_id, index);
}
