<?php

require_once __DIR__ . '/CompilationModule.php';

class ExpressionCompiler extends CompilationModule
{
    private $operators = ['+', '-', '*', '/', '&', '|', '<', '>', '='];

    public function compile(): void
    {
        $this->writer->writeOpeningTag('expression');

        $this->engine->compileTerm();

        $this->tokenizer->advance();
        if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL && in_array($this->tokenizer->symbol(), $this->operators)) {
            $this->engine->compileSymbol();

            $this->tokenizer->advance();
            $this->engine->compileTerm();
        } else {
            $this->tokenizer->back();
        }

        $this->writer->writeClosingTag('expression');
    }
}
