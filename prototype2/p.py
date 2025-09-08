def welcome():
    """
    Print a welcome message for the Caesar Cipher program.
    """
    print("Welcome to the Caesar Cipher \nThis program encrypts and decrypts text with the Caesar Cipher.")

import os

def is_file(fname):
    """
    Check if the given filename exists.
    """
    return os.path.isfile(fname)

string = "abcdefghijklmnopqrstuvwxyz"

def encrypt(m, s):
    """
    Encrypt the given message using the Caesar Cipher with the given shift.
    """
    result = ''
    m = m.lower()
    for chr in m:
        if chr in string:
            position = string.index(chr)
            out = (position + s) % len(string)
            result += string[out]
        else:
            result += chr
    return result.upper()

def decrypt(m, s):
    """
    Decrypt the given message using the Caesar Cipher with the given shift.
    """
    result = ''
    m = m.lower()
    for chr in m:
        if chr in string:
            position = string.index(chr)
            out = (position - s) % len(string)
            result += string[out]
        else:
            result += chr
    return result.upper()

def process_file(filename, mode, position):
    """
    Process the contents of a file, encrypting or decrypting the text using the Caesar Cipher.
    """
    if not is_file(filename):
        print(f"File '{filename}' does not exist.")
        return

    e_d_message = []
    with open(filename, 'r') as file:
        for line in file:
            line = line.strip().upper()
            if mode == 'e':
                e_d_message.append(encrypt(line, position))
            elif mode == 'd':
                e_d_message.append(decrypt(line, position))
    return e_d_message

def write_message(message):
    """
    Write the processed messages to a file.
    """
    with open("result.txt", "w") as text_file:
        for msg in message:
            text_file.write(msg + '\n')
    print("Output written in result")

def message_or_file():
    """
    Prompt the user to choose whether to encrypt/decrypt a message from the console or a file,
    and return the input mode, message, filename, and shift position.
    """
    while True:
        input_ = input("Would you like to encrypt (e) or decrypt (d): ").lower()
        if input_ in ["e", "d"]:
            break
        print("Invalid Mode")

    choice = input("Read from a file (f) or the console (c): ").lower()
    if choice == 'f':
        filename = input("Enter a filename: ").strip()
        while not is_file(filename):
            print("Invalid Filename")
            filename = input("Enter a filename: ").strip()
        while True:
            try:
                position = int(input("What is the shift number: "))
                if 0 <= position <= 25:
                    break
                else:
                    print("Shift must be between 0 and 25")
            except ValueError:
                print("Invalid Shift")
        return input_, None, filename, position
    elif choice == 'c':
        message = input("What message would you like to process: ").strip().upper()
        while True:
            try:
                position = int(input("What is the shift number: "))
                if 0 <= position <= 25:
                    break
                else:
                    print("Shift must be between 0 and 25")
            except ValueError:
                print("Invalid shift")
        return input_, message, None, position
    else:
        print("Invalid input. Choose 'f' for file or 'c' for console.")

def main():
    welcome()
    continue_program = True
    while continue_program:
        input_, message, filename, position = message_or_file()
        if filename:
            messages = process_file(filename, input_, position)
            if messages:
                write_message(messages)
        else:
            if input_ == 'e':
                print(encrypt(message, position))
            elif input_ == 'd':
                print(decrypt(message, position))
        continue_program = input("Would you like to encrypt or decrypt another message? (y/n): ").lower() == 'y'
    print("Thanks for using the program, goodbye!")

main()
