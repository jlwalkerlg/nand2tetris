<?php

require_once __DIR__ . '/Command.php';

class CallCommand extends Command
{
    static private $i = 0;

    private $functionName;
    private $numArgs;

    public function __construct(string $functionName, int $numArgs)
    {
        $this->functionName = $functionName;
        $this->numArgs = $numArgs;
    }

    protected function writeLines(): void
    {
        $this->writeLine('// call');

        // push retAddr
        $returnLabel = $this->generateReturnLabel();
        $this->writeLine('@' . $returnLabel);
        $this->writeLine('D=A');
        $this->writeLine('@SP');
        $this->writeLine('A=M');
        $this->writeLine('M=D');
        $this->writeLine('@SP');
        $this->writeLine('M=M+1');

        // push LCL
        $this->pushPointer('LCL');

        // push ARG
        $this->pushPointer('ARG');

        // push THIS
        $this->pushPointer('THIS');

        // push THAT
        $this->pushPointer('THAT');

        // ARG = SP - 5 - nArgs
        $this->writeLine('@5');
        $this->writeLine('D=A');
        $this->writeLine('@' . $this->numArgs);
        $this->writeLine('D=D+A');
        $this->writeLine('@SP');
        $this->writeLine('D=M-D');
        $this->writeLine('@ARG');
        $this->writeLine('M=D');

        // LCL = SP
        $this->writeLine('@SP');
        $this->writeLine('D=M');
        $this->writeLine('@LCL');
        $this->writeLine('M=D');

        // goto functionName
        $this->writeLine('@' . $this->functionName);
        $this->writeLine('0;JMP');

        // write return label
        $this->writeLine('(' . $returnLabel . ')');
    }

    private function generateReturnLabel()
    {
        return $this->functionName . '$ret' . self::$i++;
    }

    private function pushPointer(string $pointer)
    {
        $this->writeLine('@' . $pointer);
        $this->writeLine('D=M');
        $this->writeLine('@SP');
        $this->writeLine('A=M');
        $this->writeLine('M=D');
        $this->writeLine('@SP');
        $this->writeLine('M=M+1');
    }
}
