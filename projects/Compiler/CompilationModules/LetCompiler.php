<?php

require_once __DIR__ . '/CompilationModule.php';

class LetCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->writer->writeOpeningTag('letStatement');

        $this->writer->writeTag('keyword', 'let');

        $this->tokenizer->advance();
        $this->engine->compileIdentifier();

        $this->tokenizer->advance();
        if ($this->tokenizer->symbol() !== '=') {
            $this->engine->compileSymbol();

            $this->tokenizer->advance();
            $this->engine->compileExpression();

            $this->tokenizer->advance();
            $this->engine->compileSymbol();

            $this->tokenizer->advance();
        }

        $this->engine->compileSymbol();

        $this->tokenizer->advance();
        $this->engine->compileExpression();

        $this->tokenizer->advance();
        $this->engine->compileSymbol();

        $this->writer->writeClosingTag('letStatement');
    }
}
