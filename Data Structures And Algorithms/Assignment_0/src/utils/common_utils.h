#pragma once

#include <stdio.h>
#include <time.h>

typedef struct cpuTimer {
  clock_t start;
} CpuTimer;

typedef struct Timer {
  long start;
} Timer;

char* strmult(const char* string, int times);

void print_repeated(const char* string, int times);

int count_digits(int number);

void print_centered(char* string, int fieldWidth);

void print_int_centered(int num, int fieldWidth);

int randint(int limit);

void get_nth_line(FILE* file, int line_n, char* buff, int buff_size);

CpuTimer* start_cpu_timer();

double get_cpu_ellapsed(CpuTimer* timer);

void destroy_cpu_timer(CpuTimer* timer);

Timer* start_timer();

double get_ellapsed(Timer* timer);

void destroy_timer(Timer* timer);

//TODO: DEV ONLY
Timer* gtime;