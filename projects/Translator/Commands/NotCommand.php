<?php

require_once __DIR__ . '/Command.php';

class NotCommand extends Command
{
    protected function writeLines(): void
    {
        $this->writeLine('// not');
        $this->decrementStackPointer();
        $this->writeLine('A=M');
        $this->writeLine('M=!M');
        $this->incrementStackPointer();
    }
}
