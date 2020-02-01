<?php

require_once __DIR__ . '/CompilationModule.php';

class VarDecCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->writer->writeOpeningTag('varDec');

        $this->writer->writeTag('keyword', 'var');

        $this->tokenizer->advance();
        $this->engine->compileType();

        $this->tokenizer->advance();
        $this->engine->compileIdentifier();

        while (true) {
            $this->tokenizer->advance();
            $this->engine->compileSymbol();

            if ($this->tokenizer->symbol() === ';') break;

            $this->tokenizer->advance();
            $this->engine->compileIdentifier();
        }

        $this->writer->writeClosingTag('varDec');
    }
}
