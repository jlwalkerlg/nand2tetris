<?php

require_once __DIR__ . '/CompilationModule.php';

class IfCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->writer->writeOpeningTag('ifStatement');

        $this->writer->writeTag('keyword', 'if');

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

        $this->tokenizer->advance();
        if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD && $this->tokenizer->keyword() === JackTokenizer::ELSE) {
            $this->writer->writeTag('keyword', 'else');

            $this->tokenizer->advance();
            $this->engine->compileSymbol();

            $this->tokenizer->advance();
            $this->engine->compileStatements();

            $this->tokenizer->advance();
            $this->engine->compileSymbol();
        } else {
            $this->tokenizer->back();
        }

        $this->writer->writeClosingTag('ifStatement');
    }
}
