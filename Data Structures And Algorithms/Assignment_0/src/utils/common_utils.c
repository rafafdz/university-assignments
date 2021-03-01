#include "common_utils.h"

#include <stdlib.h>
#include <string.h>
#include <sys/time.h>
#include <time.h>

// Warning: It is necessary to deallocate after use!
char* strmult(const char* string, int times) {
  char* buffer = malloc(strlen(string) * times + 1);
  buffer[0] = '\0';
  for (int i = 0; i < times; i++) {
    strcat(buffer, string);
  }
  return buffer;
}

void PrintRepeated(const char* string, int times) {
  for (int i = 0; i < times; i++) {
    printf("%s", string);
  }
}

int count_digits(int number) {
  int digits = 0;
  do {
    digits++;
    number /= 10;
  } while (number > 0);
  return digits;
}
// Source:
// https://stackoverflow.com/questions/2461667/centering-strings-with-printf
void print_centered(char* string, int fieldWidth) {
  int padlen = (fieldWidth - strlen(string)) / 2;
  printf("%*s%s%*s", padlen, "", string, padlen, "");
}

// Source:
// https://stackoverflow.com/questions/2999075/generate-a-random-number-within-range
int randint(int limit) {
  int divisor = RAND_MAX / (limit + 1);
  int retval;
  do {
    retval = rand() / divisor;
  } while (retval > limit);

  return retval;
}

void print_int_centered(int num, int fieldWidth) {
  char buff[count_digits(num) + 1];
  sprintf(buff, "%i", num);
  print_centered(buff, fieldWidth);
}

// based on
// https://stackoverflow.com/questions/44244570/c-best-way-to-go-to-a-known-line-of-a-file
// Finds line and then returns its content in buff
void get_nth_line(FILE* file, int line_n, char* buff, int buff_size) {
  fseek(file, 0, SEEK_SET);
  int linecount = 0;
  int c = 0;
  while (linecount < line_n && (c = fgetc(file)) != EOF) {
    if (c == '\n') {
      linecount++;
    }
  }
  if (c == EOF) {
    fprintf(stderr, "The file has less than %i lines. Exiting\n", line_n);
    exit(1);
  }
  fgets(buff, buff_size, file);
}

CpuTimer* start_cpu_timer(){
  CpuTimer* timer = malloc(sizeof(CpuTimer));
  timer->start = clock();
  return timer;
}

double get_cpu_ellapsed(CpuTimer* timer){
  clock_t end = clock();
  return ((double)(end - timer->start)) / CLOCKS_PER_SEC;
}

void destroy_cpu_timer(CpuTimer* timer){
  free(timer);
}

long get_micro_time(){
  struct timeval timecheck;
  gettimeofday(&timecheck, NULL);
  long time = (long)timecheck.tv_sec * 1000000 + (long)timecheck.tv_usec;
  return time;
}

Timer* start_timer(){
  Timer* timer = malloc(sizeof(Timer));
  timer->start = get_micro_time();
  return timer;
}

double get_ellapsed(Timer* timer) {
  return (double) (get_micro_time() - timer->start) / 1000000.0;
}

void destroy_timer(Timer* timer){
  free(timer);
}
