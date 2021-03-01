#pragma once

#include "passenger.h"
#include "terminal.h"

void Entry(Terminal* terminal, int passsenger_id, enum Type type);

void Abord(Terminal* terminal, int door_id);

void Close(Terminal* terminal, int door_id);

void Closure(Terminal* terminal1, Terminal* terminal2);

void Laser(Terminal* terminal, int door_id, int index);