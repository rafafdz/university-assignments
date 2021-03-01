import sys

INSTRUCTION_MAP = {}
TAB = " " * 4 

with open("opcodes.txt", encoding="utf-8") as opcodes:
    for line in opcodes:
        line = line.strip()
        opcode = line[:4]
        opcode = opcode.replace("X", "x")
        instruction = line[5:]
        INSTRUCTION_MAP[opcode] = instruction # Populate dictionary

def hex_to_int(hex):
    return int(hex, 0)

def hex_to_int_compliment(hex):
    negative = hex_to_int(hex) - 256
    positive = hex_to_int(hex)
    if -128 <= negative <= 127:
        return negative
    return positive

def int_to_hex(number):
    return "0x{:02x}".format(number)

def format_line(line):
    return line[:5] + line[5:].replace(" ", ",")

class DecompileException(Exception):
    pass

class Disassembler:
    def __init__(self):
        self.vars = {} # Maps hex to generic name
        self.labels = {} # Maps hex to generic label
        self.label_line = [] # Label name to line to be inserted
        self.output = []

        self.var_counter = 0
        self.label_counter = 0
        self.binary_line_counter = 0

        self.binary_data_count = 0

        self.binary_lines = []

        self.data_lines = []
        self.code_lines = []

    def raise_decompile_exception(self, message, show_line=True):
        base_msg = "Decompile error\n"

        base_msg += message + "\n"

        # if show_line:
        #     counter = self.assembly_line_counter
        #     base_msg += f"at line {counter}: {self.assembly_lines[counter - 1]}"

        raise DecompileException(base_msg)

    def decompile_var(self, hex_value):
        value = hex_to_int(hex_value)
        hex_id = int_to_hex(self.var_counter)
        generic_name = f"VAR_{self.var_counter}"

        # Add variable to the registry
        self.vars[hex_id] = generic_name        
        self.data_lines.append(f"{generic_name} {value}")
        self.var_counter += 1


    def decompile_jump(self, instruction, label_hex, main=False):
        
        if main:
            generic_name = "MAIN"
        else:    
            generic_name = f"LABEL_{self.label_counter}"
        
        if label_hex not in self.labels:
            line_insert = (hex_to_int(label_hex) / 2) - self.binary_data_count
            self.label_line.append((generic_name, line_insert))
            self.labels[label_hex] = generic_name
            self.label_counter += 1

        if not main:
            label_name = self.labels[label_hex]
            new_line = instruction.replace("DIR", label_name)
            new_line = format_line(new_line)
            self.code_lines.append(new_line)


    def finalize_decompilation(self):
        self.output = []
        self.output.append("DATA:")

        self.output += [TAB + line for line in self.data_lines]
        self.output.append("CODE:")

        code = [TAB * 2 + line for line in self.code_lines]

        self.label_line.sort(key=lambda tup: tup[1])
        
        counter = 0
        for label, line_number in self.label_line:
            label = label + ":"
            code.insert(int(line_number) + counter - 1, TAB + label)
            counter += 1

        end_inserted = False
        if code: # If code is not empty!
            last_line = code[-1]
            if "LABEL" in last_line:

                label = last_line.strip().replace(":", "")
                code[-1] = TAB + "END:"
                end_inserted = True
                for index, line in enumerate(code):
                    if label in line:
                        new_line = line.replace(label, "END")
                        code[index] = new_line

        if not end_inserted:
            code.append(TAB + "END:")
    
        self.output += code
   
    def write_to_file(self, filename):
        with open(filename, "w", encoding="utf-8") as file:
            for line in self.output:
                print("writing line", line)
                file.write(line + "\n")

    def decompile_line(self, instruction, arg):
        print("Instruction", instruction, arg)
        if "DIR" in instruction:
            var_name = self.vars[arg]
            instruction = instruction.replace("DIR", var_name)

        elif "LIT" in instruction:
            number = hex_to_int_compliment(arg)
            instruction = instruction.replace("LIT", str(number))

        # Cambiar esto!
        instruction = format_line(instruction)
        self.code_lines.append(instruction)

    def decompile_from_file(self, filename):
        with open(filename, encoding="utf-8") as file:
            self.binary_lines = [line.strip() for line in file.readlines()]

        data_section = True

        for line in self.binary_lines:
            self.binary_line_counter += 1

            # Not likely, but for security
            if not line or line == "0xFF 0xFF":
                continue

            opcode, arg = line.split()
            
            if opcode not in INSTRUCTION_MAP:
                message = "Invalid opode " + opcode
                self.raise_decompile_exception(message)

            instruction = INSTRUCTION_MAP[opcode]

            if data_section:
                if instruction == "MOV A LIT":
                    self.decompile_var(arg)

                elif instruction == "JMP DIR":
                    self.binary_data_count = self.binary_line_counter - 1
                    self.decompile_jump(instruction, arg, main=True)
                    data_section = False

            else:
                if instruction[0] == "J":
                    self.decompile_jump(instruction, arg)
                else:
                    self.decompile_line(instruction, arg)

        self.finalize_decompilation()

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Arguments not valid")
        sys.exit(1)

    from_file = sys.argv[1]
    to_file = sys.argv[2]

    disassembler = Disassembler()
    disassembler.decompile_from_file(from_file)
    disassembler.write_to_file(to_file)
    print("Decompile done")