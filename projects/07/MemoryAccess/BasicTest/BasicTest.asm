// push
@10
D=A
@SP
A=M
M=D
@SP
M=M+1
// pop
@0
D=A
@1
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
@21
D=A
@SP
A=M
M=D
@SP
M=M+1
// push
@22
D=A
@SP
A=M
M=D
@SP
M=M+1
// pop
@2
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
// pop
@1
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
// push
@36
D=A
@SP
A=M
M=D
@SP
M=M+1
// pop
@6
D=A
@3
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
@42
D=A
@SP
A=M
M=D
@SP
M=M+1
// push
@45
D=A
@SP
A=M
M=D
@SP
M=M+1
// pop
@5
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
@510
D=A
@SP
A=M
M=D
@SP
M=M+1
// pop
@6
D=A
@5
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
@1
A=M+D
D=M
@SP
A=M
M=D
@SP
M=M+1
// push
@5
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
// push
@6
D=A
@3
A=M+D
D=M
@SP
A=M
M=D
@SP
M=M+1
// push
@6
D=A
@3
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
// push
@6
D=A
@5
A=A+D
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
