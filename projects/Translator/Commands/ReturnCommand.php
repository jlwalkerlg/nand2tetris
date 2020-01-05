<?php

require_once __DIR__ . '/Command.php';

class ReturnCommand extends Command
{
    protected function writeLines(): void
    {
        $this->writeLine('// return');

        // endFrame (R13) = LCL - 5
        $this->writeLine('@LCL');
        $this->writeLine('D=M');
        $this->writeLine('@R13');
        $this->writeLine('M=D');

        // retAddr (R14) = *(LCL - 5)
        $this->writeLine('@5');
        $this->writeLine('D=D-A');
        $this->writeLine('A=D');
        $this->writeLine('D=M');
        $this->writeLine('@R14');
        $this->writeLine('M=D');

        // *ARG = pop()
        $this->popStackIntoD();
        $this->writeLine('@ARG');
        $this->writeLine('A=M');
        $this->writeLine('M=D');

        // SP = ARG + 1
        $this->writeLine('@ARG');
        $this->writeLine('D=M+1');
        $this->writeLine('@SP');
        $this->writeLine('M=D');

        // THAT = *(endFrame - 1)
        $this->writeLine('@1');
        $this->writeLine('D=A');
        $this->writeLine('@R13');
        $this->writeLine('A=M-D');
        $this->writeLine('D=M');
        $this->writeLine('@THAT');
        $this->writeLine('M=D');

        // THIS = *(endFrame - 2)
        $this->writeLine('@2');
        $this->writeLine('D=A');
        $this->writeLine('@R13');
        $this->writeLine('A=M-D');
        $this->writeLine('D=M');
        $this->writeLine('@THIS');
        $this->writeLine('M=D');

        // ARG = *(endFrame - 3)
        $this->writeLine('@3');
        $this->writeLine('D=A');
        $this->writeLine('@R13');
        $this->writeLine('A=M-D');
        $this->writeLine('D=M');
        $this->writeLine('@ARG');
        $this->writeLine('M=D');

        // LCL = *(endFrame - 4)
        $this->writeLine('@4');
        $this->writeLine('D=A');
        $this->writeLine('@R13');
        $this->writeLine('A=M-D');
        $this->writeLine('D=M');
        $this->writeLine('@LCL');
        $this->writeLine('M=D');

        // goto retAddr
        $this->writeLine('@R14');
        $this->writeLine('A=M');
        $this->writeLine('0;JMP');
    }
}
