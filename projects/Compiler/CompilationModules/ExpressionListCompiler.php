<?php

require_once __DIR__ . '/CompilationModule.php';

class ExpressionListCompiler extends CompilationModule
{
    public function compile(): int
    {
        $nArgs = 0;

        while (true) {
            if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL) {
                if ($this->tokenizer->symbol() === ')') {
                    $this->tokenizer->back();
                    return $nArgs;
                }

                if ($this->tokenizer->symbol() === ',') {
                    $this->tokenizer->advance();
                }
            }

            $nArgs++;
            $this->engine->compileExpression();
            $this->tokenizer->advance();
        }
    }
}
