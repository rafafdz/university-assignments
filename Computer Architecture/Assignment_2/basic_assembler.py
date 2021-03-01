import sys

OPCODE_MAP = {}

with open("opcodes.txt", encoding="utf-8") as opcodes:
    for line in opcodes:
        line = line.strip()
        opcode = line[:4]
        opcode = opcode.replace("X", "x")
        instruction = line[5:]
        OPCODE_MAP[instruction] = opcode # Populate dictionary

def int_to_hex(number):
    return "0x{:02x}".format(number)

def int_to_hex_compliment(number):
    if not -128 <= number <= 127:
        message = f"Literal {number} cant be represented in 2s compliment"
        raise CompileException(message)
    if number < 0:
        return int_to_hex(256 + number)
    else:
        return int_to_hex(number)

class CompileException(Exception):
    pass

class Assembler:

    def __init__(self):
        self.vars = {}
        self.output = [] #Opcodes + Literals
        self.var_counter = 0
        self.assembly_line_counter = 0

        self.jump_index = []
        self.output = []
        self.assembly_lines = []
        self.labels = {}

    def raise_compile_exception(self, message, show_line=True):
        base_msg = "Compile error\n"

        base_msg += message + "\n"

        if show_line:
            counter = self.assembly_line_counter
            base_msg += f"at line {counter}: {self.assembly_lines[counter - 1]}"

        raise CompileException(base_msg)

    def compile_var(self, line):
        """Adds the name to variable dictionary and writes output"""
        args = line.split()
        if len(args) != 2:
            self.raise_compile_exception("Wrong variable assignment syntax")

        var_name, var_value = args
        self.vars[var_name] = self.var_counter

        first_line = self.var_counter == 0

        if first_line:
            self.compile_line("MOV B,0") # Sets counter to 0
        else:
            self.compile_line("INC B")
        
        self.compile_line(f"MOV A,{var_value}")
        self.compile_line("MOV (B),A")
        self.var_counter += 1

    def compile_line(self, line):
        print("Compiling line", line)
        instruction, args = line.split()
        args = args.split(",")

        if instruction[0] == "J": # Special case for jump instructions
            
            if len(args) != 1:
                self.raise_compile_exception("Invalid arguments in Jump")

            opcode = OPCODE_MAP[f"{instruction} DIR"]
            label = args[0]
            self.output.append([opcode, label])
            self.jump_index.append(len(self.output) - 1)
            return

        generic_args = []
        binary_arg = "0xFF"

        for arg in args:
            generic_name = arg

            if "(" in arg and "B" not in arg and "A" not in arg:
                var_name = arg[1:-1]
                
                if var_name not in self.vars:
                    message = f"Variable {var_name} not found"
                    self.raise_compile_exception(message)

                var_pointer = self.vars[var_name]
                binary_arg = int_to_hex(var_pointer)
                generic_name = "(DIR)"

            elif arg.isnumeric() or arg[1:].isnumeric():
                binary_arg = int_to_hex_compliment(int(arg))
                generic_name = "LIT"

            generic_args.append(generic_name)

        generic_ins = " ".join([instruction] + generic_args)

        if generic_ins not in OPCODE_MAP:
            message = f"{generic_ins} is not a valid instruction"
            self.raise_compile_exception(message)

        opcode = OPCODE_MAP[generic_ins]
        self.output.append([opcode, binary_arg])

    def finalize_compilation(self):
        """Replaces the jumps label with compiled line number"""
        for index in self.jump_index:
            line_list = self.output[index]
            print(line_list)
            label = line_list[1]

            if label not in self.labels:
                self.raise_compile_exception(f"{label} not found", show_line=False)

            label_number = self.labels[label]
            line_list[1] = int_to_hex(label_number)

        final_line = ["0xFF", "0xFF"]
        self.output.append(final_line)
        self.output.append(final_line)    

    def write_to_file(self, filename):
        with open(filename, "w", encoding="utf-8") as file:
            for line_list in self.output:
                print("writing line", line_list)
                to_write = " ".join(line_list) + "\n"
                file.write(to_write)

        print(f"{filename} generated succesfully")

    def compile_from_file(self, filename):
        main_section = ""
        
        # First pass, compile variables
        with open(filename, encoding="utf-8") as assembly:
            # Remove comments!
            self.assembly_lines = [line.split(";")[0].strip() 
                                   for line in assembly.readlines()]

        # First pass over the lines
        for line in self.assembly_lines:
            self.assembly_line_counter += 1

            if not line:
                continue # Skip empty lines

            if "JMP" in line:
                self.compile_line(line)
                continue

            if line in ("DATA:", "CODE:"):
                main_section = line[:-1] # Remove the dots

                if main_section == "CODE":
                    self.compile_line("JMP MAIN")
                
                continue

            if main_section == "DATA":
                self.compile_var(line)
                continue

            elif main_section == "CODE":
                if ":" in line: # Label detected
                    label = line[:-1]
                    self.labels[label] = len(self.output) * 2
                    continue

                self.compile_line(line)

        self.finalize_compilation()


if __name__ == "__main__":

    if len(sys.argv) != 3:
        print("Arguments not valid")
        sys.exit(1)

    from_file = sys.argv[1]
    to_file = sys.argv[2]

    assembler = Assembler()

    try:
        assembler.compile_from_file(from_file)
        assembler.write_to_file(to_file)
        print(f"{from_file} compiled succesfully into {to_file}")

    except CompileException as ex:
        print(ex)
        sys.exit(1)