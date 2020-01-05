<?php

require_once __DIR__ . '/Command.php';

class IfCommand extends Command
{
    private $label;

    public function __construct(string $label)
    {
        $this->label = $label;
    }

    protected function writeLines(): void
    {
        $this->writeLine('// if');
        $this->popStackIntoD();
        $this->writeLine('@' . $this->label);
        $this->writeLine('D;JNE');
    }
}
