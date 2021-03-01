#pragma once

#include  "door.h"

typedef struct terminal {
    Door** doors;
    int id;
    int door_count;
    bool open;
} Terminal;


Terminal* CreateTerminal(int terminal_id, int door_count);

void DestroyTerminal(Terminal* terminal);

void InsertPassengerTerminal(Terminal* terminal, Passenger* passenger);

void CloseTerminalDoor(Terminal* terminal, int door_index);

void AbordTerminalDoor(Terminal* terminal, int door_index, int** ids_arr);

void ClosureTerminal(Terminal* term_from, Terminal* term_to);

void PrintReport(Terminal* terminal);

void KillPerson(Terminal* terminal, int door_id, int index);