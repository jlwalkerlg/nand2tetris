<?php

require_once __DIR__ . '/Command.php';

class LabelCommand extends Command
{
    private $label;

    public function __construct(string $label)
    {
        $this->label = $label;
    }

    protected function writeLines(): void
    {
        $this->writeLine('// label');
        $this->writeLine('(' . $this->label . ')');
    }
}
