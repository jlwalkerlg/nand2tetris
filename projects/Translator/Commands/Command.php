<?php

abstract class Command
{
    protected static $branch = 1;

    protected $file;

    protected $segments = [
        'local' => 1,
        'argument' => 2,
        'this' => 3,
        'that' => 4,
        'temp' => 5,
    ];

    public function write($file): void
    {
        $this->file = $file;
        $this->writeLines();
    }

    abstract protected function writeLines(): void;

    protected function writeLine(string $line): void
    {
        fwrite($this->file, $line . PHP_EOL);
    }

    protected function decrementStackPointer(): void
    {
        $this->writeLine('@SP');
        $this->writeLine('M=M-1');
    }

    protected function incrementStackPointer(): void
    {
        $this->writeLine('@SP');
        $this->writeLine('M=M+1');
    }

    protected function popStackIntoD(): void
    {
        $this->decrementStackPointer();
        $this->writeLine('A=M');
        $this->writeLine('D=M');
    }
}
