<?php

require_once __DIR__ . '/CompilationModule.php';

class ReturnCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->writer->writeOpeningTag('returnStatement');

        $this->writer->writeTag('keyword', 'return');

        $this->tokenizer->advance();
        if ($this->tokenizer->tokenType() !== JackTokenizer::SYMBOL) {
            $this->engine->compileExpression();

            $this->tokenizer->advance();
        }

        $this->engine->compileSymbol();

        $this->writer->writeClosingTag('returnStatement');
    }
}
