<?php

require_once __DIR__ . '/CompilationModule.php';

class ExpressionCompiler extends CompilationModule
{
    public function compile(): void
    {
        $operators = ['+', '-', '*', '/', '&', '|', '<', '>', '='];

        $this->writer->writeOpeningTag('expression');

        $this->engine->compileTerm();

        $this->tokenizer->advance();
        if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL && in_array($this->tokenizer->symbol(), $operators)) {
            $this->engine->compileSymbol();

            $this->tokenizer->advance();
            $this->engine->compileTerm();
        } else {
            $this->tokenizer->back();
        }

        $this->writer->writeClosingTag('expression');
    }
}
