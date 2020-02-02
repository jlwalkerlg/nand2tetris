<?php

require_once __DIR__ . '/CompilationModule.php';

// (expression (, expression)*)?

class ExpressionListCompiler extends CompilationModule
{
    public function compile(): int
    {
        // expression|)

        $nArgs = 0;

        while (true) {
            if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL) {
                if ($this->tokenizer->symbol() === ')') {
                    $this->tokenizer->advance();
                    return $nArgs;
                }

                if ($this->tokenizer->symbol() === ',') {
                    $this->tokenizer->advance();
                }
            }

            $nArgs++;
            $this->engine->compileExpression();
        }
    }
}
