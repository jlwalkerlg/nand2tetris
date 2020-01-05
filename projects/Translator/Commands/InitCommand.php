<?php

require_once __DIR__ . '/Command.php';
require_once __DIR__ . '/CallCommand.php';

class InitCommand extends Command
{
    protected function writeLines(): void
    {
        $this->writeLine('// init');
        $this->initStackPointer();
        $this->initPointers(); // debugging only
        $this->callSysInit();
    }

    private function initStackPointer()
    {
        $this->writeLine('@256');
        $this->writeLine('D=A');
        $this->writeLine('@SP');
        $this->writeLine('M=D');
    }

    private function initPointers()
    {
        $this->writeLine('@1');
        $this->writeLine('D=-A');
        $this->writeLine('@LCL');
        $this->writeLine('M=D');

        $this->writeLine('@2');
        $this->writeLine('D=-A');
        $this->writeLine('@ARG');
        $this->writeLine('M=D');

        $this->writeLine('@3');
        $this->writeLine('D=-A');
        $this->writeLine('@THIS');
        $this->writeLine('M=D');

        $this->writeLine('@4');
        $this->writeLine('D=-A');
        $this->writeLine('@THAT');
        $this->writeLine('M=D');
    }

    private function callSysInit()
    {
        (new CallCommand('Sys.init', 0))->write($this->file);
    }
}
