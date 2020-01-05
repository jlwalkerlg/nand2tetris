<?php

require_once __DIR__ . '/Command.php';

class OrCommand extends Command
{
    protected function writeLines(): void
    {
        $this->writeLine('// or');
        $this->popStackIntoD();
        $this->decrementStackPointer();
        $this->writeLine('A=M');
        $this->writeLine('M=D|M');
        $this->incrementStackPointer();
    }
}
