<?php

require_once __DIR__ . '/CompilationModule.php';

class DoCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->writer->writeOpeningTag('doStatement');

        $this->writer->writeTag('keyword', 'do');

        $this->tokenizer->advance();
        $this->engine->compileSubroutineCall();

        $this->tokenizer->advance();
        $this->engine->compileSymbol();

        $this->writer->writeClosingTag('doStatement');
    }
}
