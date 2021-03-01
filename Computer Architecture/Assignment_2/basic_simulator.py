from basic_assembler import int_to_hex, int_to_hex_compliment, OPCODE_MAP
from basic_disassembler import  (hex_to_int, hex_to_int_compliment, 
                                 INSTRUCTION_MAP)
import inspect, sys

def formatted_hex(number):
    if number is None:
        return "-"
    result = "-" * (number < 0) + int_to_hex(abs(number))[2:]
    return result.upper()

def int_to_binary(number):
    return bin(number).replace("0b", "").zfill(8)

def detect_carry(num1, num2):
    """Detects if there is a 8 bit carry from signed nums"""
    hex1 = int_to_hex_compliment(num1)
    hex2 = int_to_hex_compliment(num2)
    positive1 = hex_to_int(hex1)
    positive2 = hex_to_int(hex2)
    return positive1 + positive2 > 255

def detect_overflow(num1, num2):
    hex1 = int_to_hex_compliment(num1)
    hex2 = int_to_hex_compliment(num2)
    positive1 = hex_to_int(hex1)
    positive2 = hex_to_int(hex2)
    bin1 = int_to_binary(positive1)
    bin2 = int_to_binary(positive2)

def binary_sum(num1, num2):
    """Sums two signed bytes"""
    hex1 = int_to_hex_compliment(num1)
    hex2 = int_to_hex_compliment(num2)
    positive1 = hex_to_int(hex1)
    positive2 = hex_to_int(hex2)
    binary = int_to_binary(positive1 + positive2)[-8:]
    decimal = int(binary, 2)
    new_hex = int_to_hex_compliment(decimal)
    return hex_to_int_compliment(new_hex)

class Memory:
    def __init__(self):
        self.words = {}

    def store(self, value, location):
        """Location should be a positive integer"""
        self.words[location] = value

    def load(self, location):
        if location not in self.words:
            return 0
        return self.words[location]

class Computer:

    def __init__(self):
        self.rom = [] # Lines from file
        self.opcode_method = {}
        self.memory = Memory()
        self.pc = -1
        self.a = None # Initial values for registers
        self.b = None
        self.z = 0
        self.n = 0
        self.c = 0
        self.v = 0
        self._setup_method_dict()
        self.output = []

    def _setup_method_dict(self):
        special_cases = {"mov_a__b__" : "MOV A (B)", 
                         "mov_b__b__" : "MOV B (B)",
                         "mov__b__a" : "MOV (B) A"}

        method_list = inspect.getmembers(self, predicate=inspect.ismethod)
        for name, method in method_list:

            if name in special_cases:
                instruction = special_cases[name]
            else:
                instruction = name.upper().replace("_", " ")
                if "MOV" in instruction:
                    instruction = instruction.replace("DIR", "(DIR)")

            if instruction not in OPCODE_MAP:
                continue 

            opcode = OPCODE_MAP[instruction]
            self.opcode_method[opcode] = method

    def run_file(self, filename):
        with open(filename, encoding="utf-8") as file:
            self.rom = [line.strip() for line in file.readlines()]

        iteration = 0
        while True:
            self.pc += 1
            iteration += 1

            counter_before = self.pc
            line = self.rom[self.pc]
            opcode, arg_hex = line.split()

            if opcode == "0xFF":
                return

            instruction = INSTRUCTION_MAP[opcode]
            method = self.opcode_method[opcode]

            #print("Iteracion", iteration, "Ejecutando", instruction, arg_hex)

            if "DIR" in instruction:
                arg = hex_to_int(arg_hex) # Do not use twos complement
                if instruction[0] == "J": # Jump detected
                    arg = int(arg / 2) - 1

            elif "LIT" in instruction:
                arg = hex_to_int_compliment(arg_hex)
            
            if "DIR" in instruction or "LIT" in instruction:
                method(arg)
            else:
                method()

            #print(f"Iteracion {iteration}. Linea {self.pc}")
            output_line = self.show_information(iteration, counter_before,
                                                opcode, arg_hex)

            self.output.append(output_line)

    def show_information(self, iteration, counter, opcode, literal):
        template = "{:>4}| {:>4} | {:>4} | {:^4} | {:^6} | {:^7}"
        pc = int_to_hex(counter * 2)[2:].upper()
        a_register = formatted_hex(self.a)
        b_register = formatted_hex(self.b)
        op = opcode[2:].upper()

        if literal == "0xFF":
            literal = "0x00"
        lit = formatted_hex(hex_to_int_compliment(literal))

        line = template.format(iteration, a_register, b_register, pc, op, lit)
        return line

    def write_to_file(self, filename):
        with open(filename, "w", encoding="utf-8") as file:
            for line in self.output:
                file.write(line + "\n")

    def mov_a_b(self):
        self.a = self.b

    def mov_b_a(self):
        self.b = self.a

    def mov_a_lit(self, lit):
        self.a = lit

    def mov_b_lit(self, lit):
        self.b = lit

    def add_a_b(self):
        self.a += self.b

    def add_b_a(self):
        self.b += self.a

    def add_a_lit(self, lit):
        self.a += lit
    
    def sub_a_b(self):
        self.a -= self.b

    def sub_b_a(self):
        self.b = self.a - self.b

    def sub_a_lit(self, lit):
        self.a -= lit

    def and_a_b(self):
        self.a = self.a & self.b

    def and_b_a(self):
        self.b = self.a & self.b

    def and_a_lit(self, lit):
        self.a = self.a and lit

    def or_a_b(self):
        self.a = self.a | self.b

    def or_b_a(self):
        self.b = self.a | self.b

    def or_a_lit(self, lit):
        self.a = self.a | lit

    def not_a_b(self):
        self.a = ~self.a

    def not_b_a(self):
        self.b = ~self.a

    def not_a_lit(self, lit):
        self.a = ~lit

    def xor_a_b(self):
        self.a = self.a ^ self.b

    def xor_b_a(self):
        self.b = self.a ^ self.b

    def xor_a_lit(self, lit):
        self.a = self.a ^ lit

    def shl_a_b(self):
        self.a = self.a << 1

    def shl_b_a(self):
        self.b = self.a << 1

    def shl_a_lit(self, lit):
        self.a = lit << 1

    def shr_a_b(self):
        self.a = self.a >> 1

    def shr_b_a(self):
        self.b = self.a >> 1

    def shr_a_lit(self, lit):
        self.a = lit >> 1

    def cmp_a_b(self):
        result = self.a - self.b
        self.z = int(result == 0)
        self.n = int(result < 0)

    def cmp_a_lit(self, lit):
        result = self.a - lit
        self.z = int(result == 0)
        self.n = int(result < 0)

    def jmp_dir(self, target):
        self.pc = target

    def jeq_dir(self, target):
        if self.z:
            self.jmp_dir(target)

    def jne_dir(self, target):
        if not self.z:
            self.jmp_dir(target)

    def jgt_dir(self, target):
        if not self.z and not self.n:
            self.jmp_dir(target)

    def jge_dir(self, target):
        if not self.n:
            self.jmp_dir(target)
    
    def jlt_dir(self, target):
        if self.n:
            self.jmp_dir(target)

    def jle_dir(self, target):
        if self.n or self.z:
            self.jmp_dir(target)

    def jcr_dir(self, target):
        if self.c:
           self.jmp_dir(target)

    def jov_dir(self, target):
        if self.v:
            self.jmp_dir(target)

    def mov_a_dir(self, dir):
        self.a = self.memory.load(dir)

    def mov_b_dir(self, dir):
        self.b = self.memory.load(dir)

    def mov_dir_a(self, dir):
        self.memory.store(self.a, dir)

    def mov_dir_b(self, dir):
        self.memory.store(self.b, dir)

    # To do: Parse manually!
    def mov_a__b__(self):
        self.a = self.memory.load(self.b)
    
    def mov_b__b__(self):
        self.b = self.memory.load(self.b)
    
    def mov__b__a(self):
        self.memory.store(self.a, self.b)

    def inc_b(self):
        self.b += 1

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Arguments not valid")
        sys.exit(1)

    from_file = sys.argv[1]
    to_file = sys.argv[2]

    computer = Computer()
    computer.run_file(from_file)
    computer.write_to_file(to_file)