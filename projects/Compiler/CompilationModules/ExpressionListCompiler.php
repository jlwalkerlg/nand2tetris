<?php

require_once __DIR__ . '/CompilationModule.php';

class ExpressionListCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->writer->writeOpeningTag('expressionList');

        while (true) {
            if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL) {
                if ($this->tokenizer->symbol() === ')') {
                    $this->tokenizer->back();
                    break;
                }

                if ($this->tokenizer->symbol() === ',') {
                    $this->engine->compileSymbol();
                    $this->tokenizer->advance();
                }
            }

            $this->engine->compileExpression();
            $this->tokenizer->advance();
        }

        $this->writer->writeClosingTag('expressionList');
    }
}
