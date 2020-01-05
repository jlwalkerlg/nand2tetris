<?php

require_once __DIR__ . '/Command.php';
require_once __DIR__ . '/PushCommand.php';

class FunctionCommand extends Command
{
    private $functionName;
    private $numVars;

    public function __construct(string $functionName, int $numVars)
    {
        $this->functionName = $functionName;
        $this->numVars = $numVars;
    }

    protected function writeLines(): void
    {
        $this->writeLine('// function');
        $this->writeLine('(' . $this->functionName . ')');
        for ($i = 0; $i < $this->numVars; $i++) {
            $this->pushConstant('0');
        }
    }

    private function pushConstant(string $constant)
    {
        (new PushCommand('constant', $constant))->write($this->file);
    }
}
