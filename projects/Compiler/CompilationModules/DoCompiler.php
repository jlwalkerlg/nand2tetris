<?php

require_once __DIR__ . '/CompilationModule.php';

class DoCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->tokenizer->advance();
        $this->engine->compileSubroutineCall();

        // ;

        $this->tokenizer->advance();
    }
}
