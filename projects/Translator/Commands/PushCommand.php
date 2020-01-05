<?php

require_once __DIR__ . '/Command.php';

class PushCommand extends Command
{
    private $segment;
    private $index;
    private $filename;

    public function __construct(string $segment, int $index, string $filename = null)
    {
        $this->segment = $segment;
        $this->index = $index;
        $this->filename = $filename;
    }

    protected function writeLines(): void
    {
        $this->writeLine('// push');
        $this->getD();
        $this->writeDToStack();
        $this->incrementStackPointer();
    }

    private function getD(): void
    {
        if ($this->segment === 'constant') {
            $this->writeLine("@{$this->index}");
            $this->writeLine('D=A');
        } elseif ($this->segment === 'static') {
            $this->writeLine("@{$this->filename}.{$this->index}");
            $this->writeLine('D=M');
        } elseif ($this->segment === 'temp') {
            $this->writeLine("@{$this->index}");
            $this->writeLine('D=A');
            $this->writeLine("@{$this->segments[$this->segment]}");
            $this->writeLine('A=A+D');
            $this->writeLine('D=M');
        } elseif ($this->segment === 'pointer') {
            if ($this->index === 0) {
                $this->writeLine("@{$this->segments['this']}");
            } else {
                $this->writeLine("@{$this->segments['that']}");
            }
            $this->writeLine('D=M');
        } else {
            $this->writeLine("@{$this->index}");
            $this->writeLine('D=A');
            $this->writeLine("@{$this->segments[$this->segment]}");
            $this->writeLine('A=M+D');
            $this->writeLine('D=M');
        }
    }

    private function writeDToStack(): void
    {
        $this->writeLine('@SP');
        $this->writeLine('A=M');
        $this->writeLine('M=D');
    }
}
