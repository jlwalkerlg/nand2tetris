<?php

require_once __DIR__ . '/CompilationModule.php';

// do subroutineCall;

class DoCompiler extends CompilationModule
{
    public function compile(): void
    {
        // do

        $this->tokenizer->advance(); // subroutineCall

        $this->engine->compileSubroutineCall(); // ;

        $this->tokenizer->advance();

        $this->vmWriter->writePop('temp', 0);
    }
}
