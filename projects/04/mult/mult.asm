// This file is part of www.nand2tetris.org
// and the book "The Elements of Computing Systems"
// by Nisan and Schocken, MIT Press.
// File name: projects/04/Mult.asm

// Multiplies R0 and R1 and stores the result in R2.
// (R0, R1, R2 refer to RAM[0], RAM[1], and RAM[2], respectively.)

// Put your code here.

// Outline:
// R2 = 0
// While R0 > 0, R2 += R1

// Set R2 = 0
@R2 // [0] 0000 0000 0000 0010
M=0 // [1] 1110 1010 1000 1000

// Begin LOOP
(LOOP)
  // If R0 <= 0, goto END
  @R0 // [2] 0000 0000 0000 0000
  D=M // [3] 1111 1100 0001 0000
  @END // [4] 0000 0000 0000 1110
  D;JLE // [5] 1110 0011 0000 0110

  // Else:
  // R2 += R1
  @R1 // [6] 0000 0000 0000 0001
  D=M // [7] 1111 1100 0001 0000
  @R2 // [8] 0000 0000 0000 0010
  M=M+D // [9] 1111 0000 1000 1000

  // R0 -= 1
  @R0 // [10] 0000 0000 0000 0000
  M=M-1 // [11] 1111 1100 1000 1000

  // Loop
  @LOOP // [12] 0000 0000 0000 0010
  0;JMP // [13] 1110 1010 1000 0111

(END) //
  @END // [14] 0000 0000 0000 1110
  0;JMP // [15] 1110 1010 1000 0111
