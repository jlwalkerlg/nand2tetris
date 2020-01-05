<?php

require_once __DIR__ . '/Command.php';

class AddCommand extends Command
{
    protected function writeLines(): void
    {
        $this->writeLine('// add');
        $this->popStackIntoD();
        $this->decrementStackPointer();
        $this->writeLine('A=M');
        $this->writeLine('M=M+D');
        $this->incrementStackPointer();
    }
}
