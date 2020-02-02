<?php

require_once __DIR__ . '/CompilationModule.php';

class ReturnCompiler extends CompilationModule
{
    public function compile(): void
    {
        // return

        $this->tokenizer->advance();
        // expression|;

        if ($this->tokenizer->tokenType() !== JackTokenizer::SYMBOL) {
            $this->engine->compileExpression();
        }

        $this->vmWriter->writeReturn();

        $this->tokenizer->advance();
    }
}
