<?php

require_once __DIR__ . '/Command.php';

class PopCommand extends Command
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
        $this->writeLine('// pop');
        if ($this->segment !== 'static') {
            $this->storeSegmentAddress();
        }
        $this->decrementStackPointer();
        $this->readStackIntoD();
        $this->loadSegmentAddressIntoA();
        $this->writeDToM();
    }

    private function storeSegmentAddress(): void
    {
        $this->loadSegmentAddressIntoD();
        $this->storeDInTempRegister();
    }

    private function loadSegmentAddressIntoD(): void
    {
        if ($this->segment === 'pointer') {
            if ($this->index === 0) {
                $this->writeLine("@{$this->segments['this']}");
            } else {
                $this->writeLine("@{$this->segments['that']}");
            }
            $this->writeLine('D=A');
        } elseif ($this->segment === 'temp') {
            $this->writeLine("@{$this->index}");
            $this->writeLine('D=A');
            $this->writeLine("@{$this->segments[$this->segment]}");
            $this->writeLine('D=D+A');
        } else {
            $this->writeLine("@{$this->index}");
            $this->writeLine('D=A');
            $this->writeLine("@{$this->segments[$this->segment]}");
            $this->writeLine('A=M');
            $this->writeLine('D=D+A');
        }
    }

    private function storeDInTempRegister(): void
    {
        $this->writeLine('@R13');
        $this->writeLine('M=D');
    }

    private function readStackIntoD(): void
    {
        $this->writeLine('@SP'); // ??
        $this->writeLine('A=M');
        $this->writeLine('D=M');
    }

    private function writeDToM(): void
    {
        $this->writeLine('M=D');
    }

    private function loadSegmentAddressIntoA(): void
    {
        if ($this->segment === 'static') {
            $this->writeLine("@{$this->filename}.{$this->index}");
        } else {
            $this->writeLine('@R13');
            $this->writeLine('A=M');
        }
    }
}
