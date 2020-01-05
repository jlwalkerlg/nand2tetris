<?php

require_once __DIR__ . '/Command.php';

class GotoCommand extends Command
{
    private $label;

    public function __construct(string $label)
    {
        $this->label = $label;
    }

    protected function writeLines(): void
    {
        $this->writeLine('// goto');
        $this->writeLine('@' . $this->label);
        $this->writeLine('0;JMP');
    }
}
