// push
@17
D=A
@SP
A=M
M=D
@SP
M=M+1
// push
@17
D=A
@SP
A=M
M=D
@SP
M=M+1
// eq
@SP
M=M-1
A=M
D=M
@SP
M=M-1
A=M
D=M-D
// If D = 0 write true; else write false
@TRUE1
D;JEQ
@SP
A=M
M=0
@POSTTRUE2
0;JMP
(TRUE1)
@SP
A=M
M=-1
(POSTTRUE2)
@SP
M=M+1
// push
@17
D=A
@SP
A=M
M=D
@SP
M=M+1
// push
@16
D=A
@SP
A=M
M=D
@SP
M=M+1
// eq
@SP
M=M-1
A=M
D=M
@SP
M=M-1
A=M
D=M-D
// If D = 0 write true; else write false
@TRUE3
D;JEQ
@SP
A=M
M=0
@POSTTRUE4
0;JMP
(TRUE3)
@SP
A=M
M=-1
(POSTTRUE4)
@SP
M=M+1
// push
@16
D=A
@SP
A=M
M=D
@SP
M=M+1
// push
@17
D=A
@SP
A=M
M=D
@SP
M=M+1
// eq
@SP
M=M-1
A=M
D=M
@SP
M=M-1
A=M
D=M-D
// If D = 0 write true; else write false
@TRUE5
D;JEQ
@SP
A=M
M=0
@POSTTRUE6
0;JMP
(TRUE5)
@SP
A=M
M=-1
(POSTTRUE6)
@SP
M=M+1
// push
@892
D=A
@SP
A=M
M=D
@SP
M=M+1
// push
@891
D=A
@SP
A=M
M=D
@SP
M=M+1
// lt
@SP
M=M-1
A=M
D=M
@SP
M=M-1
A=M
D=M-D
// If D < 0 write true; else write false
@TRUE7
D;JLT
@SP
A=M
M=0
@POSTTRUE8
0;JMP
(TRUE7)
@SP
A=M
M=-1
(POSTTRUE8)
@SP
M=M+1
// push
@891
D=A
@SP
A=M
M=D
@SP
M=M+1
// push
@892
D=A
@SP
A=M
M=D
@SP
M=M+1
// lt
@SP
M=M-1
A=M
D=M
@SP
M=M-1
A=M
D=M-D
// If D < 0 write true; else write false
@TRUE9
D;JLT
@SP
A=M
M=0
@POSTTRUE10
0;JMP
(TRUE9)
@SP
A=M
M=-1
(POSTTRUE10)
@SP
M=M+1
// push
@891
D=A
@SP
A=M
M=D
@SP
M=M+1
// push
@891
D=A
@SP
A=M
M=D
@SP
M=M+1
// lt
@SP
M=M-1
A=M
D=M
@SP
M=M-1
A=M
D=M-D
// If D < 0 write true; else write false
@TRUE11
D;JLT
@SP
A=M
M=0
@POSTTRUE12
0;JMP
(TRUE11)
@SP
A=M
M=-1
(POSTTRUE12)
@SP
M=M+1
// push
@32767
D=A
@SP
A=M
M=D
@SP
M=M+1
// push
@32766
D=A
@SP
A=M
M=D
@SP
M=M+1
// gt
@SP
M=M-1
A=M
D=M
@SP
M=M-1
A=M
D=M-D
// If D > 0 write true; else write false
@TRUE13
D;JGT
@SP
A=M
M=0
@POSTTRUE14
0;JMP
(TRUE13)
@SP
A=M
M=-1
(POSTTRUE14)
@SP
M=M+1
// push
@32766
D=A
@SP
A=M
M=D
@SP
M=M+1
// push
@32767
D=A
@SP
A=M
M=D
@SP
M=M+1
// gt
@SP
M=M-1
A=M
D=M
@SP
M=M-1
A=M
D=M-D
// If D > 0 write true; else write false
@TRUE15
D;JGT
@SP
A=M
M=0
@POSTTRUE16
0;JMP
(TRUE15)
@SP
A=M
M=-1
(POSTTRUE16)
@SP
M=M+1
// push
@32766
D=A
@SP
A=M
M=D
@SP
M=M+1
// push
@32766
D=A
@SP
A=M
M=D
@SP
M=M+1
// gt
@SP
M=M-1
A=M
D=M
@SP
M=M-1
A=M
D=M-D
// If D > 0 write true; else write false
@TRUE17
D;JGT
@SP
A=M
M=0
@POSTTRUE18
0;JMP
(TRUE17)
@SP
A=M
M=-1
(POSTTRUE18)
@SP
M=M+1
// push
@57
D=A
@SP
A=M
M=D
@SP
M=M+1
// push
@31
D=A
@SP
A=M
M=D
@SP
M=M+1
// push
@53
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
// push
@112
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
// neg
@SP
M=M-1
A=M
M=-M
@SP
M=M+1
// and
@SP
M=M-1
A=M
D=M
@SP
M=M-1
A=M
M=D&M
@SP
M=M+1
// push
@82
D=A
@SP
A=M
M=D
@SP
M=M+1
// or
@SP
M=M-1
A=M
D=M
@SP
M=M-1
A=M
M=D|M
@SP
M=M+1
// not
@SP
M=M-1
A=M
M=!M
@SP
M=M+1
