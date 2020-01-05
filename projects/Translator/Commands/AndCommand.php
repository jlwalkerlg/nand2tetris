<?php

require_once __DIR__ . '/Command.php';

class AndCommand extends Command
{
    protected function writeLines(): void
    {
        $this->writeLine('// and');
        $this->popStackIntoD();
        $this->decrementStackPointer();
        $this->writeLine('A=M');
        $this->writeLine('M=D&M');
        $this->incrementStackPointer();
    }
}
