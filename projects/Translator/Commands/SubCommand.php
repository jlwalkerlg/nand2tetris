<?php

require_once __DIR__ . '/Command.php';

class SubCommand extends Command
{
    protected function writeLines(): void
    {
        $this->writeLine('// sub');
        $this->popStackIntoD();
        $this->decrementStackPointer();
        $this->writeLine('A=M');
        $this->writeLine('M=M-D');
        $this->incrementStackPointer();
    }
}
