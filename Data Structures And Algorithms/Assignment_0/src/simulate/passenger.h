#pragma once

enum Type {
    CHILD = 0,
    ADULT = 1,
    ROBOT = 2     
};

typedef struct passenger {
    int id;
    enum Type type;
} Passenger;


Passenger* CreatePassenger(int passgener_id, enum Type type);

void DestroyPassenger(Passenger* passenger);

void PrintPassenger(Passenger* passenger);