// This file is part of www.nand2tetris.org
// and the book "The Elements of Computing Systems"
// by Nisan and Schocken, MIT Press.
// File name: projects/04/Fill.asm

// Runs an infinite loop that listens to the keyboard input.
// When a key is pressed (any key), the program blackens the screen,
// i.e. writes "black" in every pixel;
// the screen should remain fully black as long as the key is pressed.
// When no key is pressed, the program clears the screen, i.e. writes
// "white" in every pixel;
// the screen should remain fully clear as long as no key is pressed.

// Put your code here.

// Pixels 0-15 are given by the bits in the 16-bit register at Memory[16384]
// Pixels 16-31 are given by the bits in the 16-bit register at Memory[16385]
// Keyboard is 16-bit key code at Memory[24576]

// Loop through all screen registers
// If keyboard is 0, fill register with 0
// Else fill register with 1

// i_max = 24575
@24575 // [0] 0101 1111 1111 1111
D=A // [1] 1110 1100 0001 0000
@i_max // [2] 0000 0000 0001 0000
M=D // [3] 1110 0011 0000 1000

// i = screen
@SCREEN // [4] 0100 0000 0000 0000
D=A // [5] 1110 1100 0001 0000
@i // [6] 0000 0000 0010 0000
M=D // [7] 1110 0011 0000 1000

// Begin LOOP
(LOOP)
  // If keyboard == 0, jump to WHITE
  @KBD // [8] 0110 0000 0000 0000
  D=M // D = KBD [9] 1111 1100 0001 0000
  @WHITE // [10] 0000 0000 0001 0001
  D;JEQ // [11] 1110 0011 0000 0010

  // Set the current 16-bit screen register to -1 (black)
  @i // [12] 0000 0000 0010 0000
  A=M // A = i [13] 1111 1100 0010 0000
  M=-1 // RAM[i] = -1 [14] 1110 1110 1000 1000
  @SKIPWHITE // [15] 0000 0000 0001 0100
  0;JMP // [16] 1110 1010 1000 0111

  (WHITE)
  // Set the current 16-bit screen register to 0 (white)
  @i // [17] 0000 0000 0010 0000
  A=M // A = i [18] 1111 1100 0010 0000
  M=0 // RAM[i] = 0 [19] 1110 1010 1000 1000

  // i++
  (SKIPWHITE)
  @i // [20] 0000 0000 0010 0000
  M=M+1 // [21] 1111 1101 1100 1000

  // If i > i_max (i_max - i < 0), reset i
  @i_max // [22] 0000 0000 0001 0000
  D=M // D = i_max [23] 1111 1100 0001 0000
  @i // [24] 0000 0000 0010 0000
  D=D-M // D = i_max - i [25] 1111 0100 1101 0000
  @SKIPRESET // [26] 0000 0000 0010 0000
  D;JGE // [27] 1110 0011 0000 0011

  // i = SCREEN
  @SCREEN // [28] 0100 0000 0000 0000
  D=A // [29] 1110 1100 0001 0000
  @i // [30] 0000 0000 0010 0000
  M=D // [31] 1110 0011 0000 1000

  // LOOP
  (SKIPRESET)
  @LOOP // [32] 0000 0000 0000 1000
  0;JMP // [33] 1110 1010 1000 0111
