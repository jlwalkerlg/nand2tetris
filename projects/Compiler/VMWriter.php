<?php

class VMWriter
{
    private $file;

    public function __construct(string $filename)
    {
        $this->file = fopen($filename, 'w');
    }

    public function writePush(string $segment, int $index): void
    {
        fwrite($this->file, "push {$segment} {$index}" . PHP_EOL);
    }

    public function writePop(string $segment, int $index): void
    {
        fwrite($this->file, "pop {$segment} {$index}" . PHP_EOL);
    }

    public function writeArithmetic(string $command): void
    {
        fwrite($this->file, $command . PHP_EOL);
    }

    public function writeLabel(string $label): void
    {
        fwrite($this->file, "label {$label}" . PHP_EOL);
    }

    public function writeGoto(string $label): void
    {
        fwrite($this->file, "goto {$label}" . PHP_EOL);
    }

    public function writeIf(string $label): void
    {
        fwrite($this->file, "if-goto {$label}" . PHP_EOL);
    }

    public function writeCall(string $name, int $nArgs): void
    {
        fwrite($this->file, "call {$name} {$nArgs}" . PHP_EOL);
    }

    public function writeFunction(string $name, int $nLocals): void
    {
        fwrite($this->file, "function {$name} {$nLocals}" . PHP_EOL);
    }

    public function writeReturn(): void
    {
        fwrite($this->file, "return" . PHP_EOL);
    }

    public function close(): void
    {
        fclose($this->file);
    }
}
