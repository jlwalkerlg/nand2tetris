<?php

require_once __DIR__ . '/Command.php';

class NegCommand extends Command
{
    protected function writeLines(): void
    {
        $this->writeLine('// neg');
        $this->decrementStackPointer();
        $this->writeLine('A=M');
        $this->writeLine('M=-M');
        $this->incrementStackPointer();
    }
}
