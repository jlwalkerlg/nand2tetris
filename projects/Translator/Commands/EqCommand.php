<?php

require_once __DIR__ . '/Command.php';

class EqCommand extends Command
{
    protected function writeLines(): void
    {
        $this->writeLine('// eq');
        $this->popStackIntoD();
        $this->decrementStackPointer();
        $this->writeLine('A=M');
        $this->writeLine('D=M-D');
        $this->writeLine('// If D = 0 write true; else write false');
        $true = self::$branch++;
        $this->writeLine('@TRUE' . $true);
        $this->writeLine('D;JEQ');
        $this->writeLine('@SP');
        $this->writeLine('A=M');
        $this->writeLine('M=0');
        $posttrue = self::$branch++;
        $this->writeLine('@POSTTRUE' . $posttrue);
        $this->writeLine('0;JMP');
        $this->writeLine("(TRUE{$true})");
        $this->writeLine('@SP');
        $this->writeLine('A=M');
        $this->writeLine('M=-1');
        $this->writeLine("(POSTTRUE{$posttrue})");
        $this->incrementStackPointer();
    }
}
