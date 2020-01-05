<?php

require_once __DIR__ . '/Commands/AddCommand.php';
require_once __DIR__ . '/Commands/SubCommand.php';
require_once __DIR__ . '/Commands/NegCommand.php';
require_once __DIR__ . '/Commands/EqCommand.php';
require_once __DIR__ . '/Commands/GtCommand.php';
require_once __DIR__ . '/Commands/LtCommand.php';
require_once __DIR__ . '/Commands/AndCommand.php';
require_once __DIR__ . '/Commands/OrCommand.php';
require_once __DIR__ . '/Commands/NotCommand.php';
require_once __DIR__ . '/Commands/PushCommand.php';
require_once __DIR__ . '/Commands/PopCommand.php';
require_once __DIR__ . '/Commands/LabelCommand.php';
require_once __DIR__ . '/Commands/GotoCommand.php';
require_once __DIR__ . '/Commands/IfCommand.php';
require_once __DIR__ . '/Commands/FunctionCommand.php';
require_once __DIR__ . '/Commands/ReturnCommand.php';
require_once __DIR__ . '/Commands/CallCommand.php';
require_once __DIR__ . '/Commands/InitCommand.php';

class CodeWriter
{
    private $outfile;

    private $currentFilename;

    public function __construct(string $outfileName)
    {
        $this->outfile = fopen($outfileName, 'w');
    }

    public function setCurrentFilename(string $filename): void
    {
        $this->currentFilename = $filename;
    }

    public function writeInit(): void
    {
        (new InitCommand)->write($this->outfile);
    }

    public function writeArithmetic(string $commandType): void
    {
        switch ($commandType) {
            case 'add':
                $command = new AddCommand;
                break;
            case 'sub':
                $command = new SubCommand;
                break;
            case 'neg':
                $command = new NegCommand;
                break;
            case 'eq':
                $command = new EqCommand;
                break;
            case 'gt':
                $command = new GtCommand;
                break;
            case 'lt':
                $command = new LtCommand;
                break;
            case 'and':
                $command = new AndCommand;
                break;
            case 'or':
                $command = new OrCommand;
                break;
            case 'not':
                $command = new NotCommand;
                break;
        }

        $command->write($this->outfile);
    }

    public function writePushPop(string $commandType, string $segment, int $index): void
    {
        if ($commandType === C_PUSH) {
            $this->writePush($segment, $index);
        } elseif ($commandType === C_POP) {
            $this->writePop($segment, $index);
        }
    }

    private function writePush(string $segment, int $index): void
    {
        (new PushCommand($segment, $index, $this->currentFilename))->write($this->outfile);
    }

    private function writePop(string $segment, int $index): void
    {
        (new PopCommand($segment, $index, $this->currentFilename))->write($this->outfile);
    }

    public function writeLabel(string $label): void
    {
        (new LabelCommand($label))->write($this->outfile);
    }

    public function writeGoto(string $label): void
    {
        (new GotoCommand($label))->write($this->outfile);
    }

    public function writeIf(string $label): void
    {
        (new IfCommand($label))->write($this->outfile);
    }

    public function writeFunction(string $functionName, int $numVars): void
    {
        (new FunctionCommand($functionName, $numVars))->write($this->outfile);
    }

    public function writeReturn(): void
    {
        (new ReturnCommand)->write($this->outfile);
    }

    public function writeCall(string $functionName, int $numArgs): void
    {
        (new CallCommand($functionName, $numArgs))->write($this->outfile);
    }

    public function close()
    {
        fclose($this->outfile);
    }
}
