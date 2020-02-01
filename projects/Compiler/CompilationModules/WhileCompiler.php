<?php

require_once __DIR__ . '/CompilationModule.php';

class WhileCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->writer->writeOpeningTag('whileStatement');

        $this->writer->writeTag('keyword', 'while');

        $this->tokenizer->advance();
        $this->engine->compileSymbol();

        $this->tokenizer->advance();
        $this->engine->compileExpression();

        $this->tokenizer->advance();
        $this->engine->compileSymbol();

        $this->tokenizer->advance();
        $this->engine->compileSymbol();

        $this->tokenizer->advance();
        $this->engine->compileStatements();

        $this->tokenizer->advance();
        $this->engine->compileSymbol();

        $this->writer->writeClosingTag('whileStatement');
    }
}
