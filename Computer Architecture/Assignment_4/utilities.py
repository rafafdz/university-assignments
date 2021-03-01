def binary_to_decimal(binary_string):
    return int(binary_string, 2)


def decimal_to_binary(number, zeros=8):
    return bin(number)[2:].zfill(zeros)





