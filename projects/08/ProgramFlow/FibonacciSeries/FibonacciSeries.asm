// push
@1
D=A
@2
A=M+D
D=M
@SP
A=M
M=D
@SP
M=M+1
// pop
@4
D=A
@R13
M=D
@SP
M=M-1
@SP
A=M
D=M
@R13
A=M
M=D
// push
@0
D=A
@SP
A=M
M=D
@SP
M=M+1
// pop
@0
D=A
@4
A=M
D=D+A
@R13
M=D
@SP
M=M-1
@SP
A=M
D=M
@R13
A=M
M=D
// push
@1
D=A
@SP
A=M
M=D
@SP
M=M+1
// pop
@1
D=A
@4
A=M
D=D+A
@R13
M=D
@SP
M=M-1
@SP
A=M
D=M
@R13
A=M
M=D
// push
@0
D=A
@2
A=M+D
D=M
@SP
A=M
M=D
@SP
M=M+1
// push
@2
D=A
@SP
A=M
M=D
@SP
M=M+1
// sub
@SP
M=M-1
A=M
D=M
@SP
M=M-1
A=M
M=M-D
@SP
M=M+1
// pop
@0
D=A
@2
A=M
D=D+A
@R13
M=D
@SP
M=M-1
@SP
A=M
D=M
@R13
A=M
M=D
// label
(MAIN_LOOP_START)
// push
@0
D=A
@2
A=M+D
D=M
@SP
A=M
M=D
@SP
M=M+1
// if
@SP
M=M-1
A=M
D=M
@COMPUTE_ELEMENT
D;JNE
// goto
@END_PROGRAM
0;JMP
// label
(COMPUTE_ELEMENT)
// push
@0
D=A
@4
A=M+D
D=M
@SP
A=M
M=D
@SP
M=M+1
// push
@1
D=A
@4
A=M+D
D=M
@SP
A=M
M=D
@SP
M=M+1
// add
@SP
M=M-1
A=M
D=M
@SP
M=M-1
A=M
M=M+D
@SP
M=M+1
// pop
@2
D=A
@4
A=M
D=D+A
@R13
M=D
@SP
M=M-1
@SP
A=M
D=M
@R13
A=M
M=D
// push
@4
D=M
@SP
A=M
M=D
@SP
M=M+1
// push
@1
D=A
@SP
A=M
M=D
@SP
M=M+1
// add
@SP
M=M-1
A=M
D=M
@SP
M=M-1
A=M
M=M+D
@SP
M=M+1
// pop
@4
D=A
@R13
M=D
@SP
M=M-1
@SP
A=M
D=M
@R13
A=M
M=D
// push
@0
D=A
@2
A=M+D
D=M
@SP
A=M
M=D
@SP
M=M+1
// push
@1
D=A
@SP
A=M
M=D
@SP
M=M+1
// sub
@SP
M=M-1
A=M
D=M
@SP
M=M-1
A=M
M=M-D
@SP
M=M+1
// pop
@0
D=A
@2
A=M
D=D+A
@R13
M=D
@SP
M=M-1
@SP
A=M
D=M
@R13
A=M
M=D
// goto
@MAIN_LOOP_START
0;JMP
// label
(END_PROGRAM)
