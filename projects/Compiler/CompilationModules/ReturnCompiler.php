<?php

require_once __DIR__ . '/CompilationModule.php';

// return expression?;

class ReturnCompiler extends CompilationModule
{
    public function compile(): void
    {
        // return

        $this->tokenizer->advance(); // expression|;

        if ($this->tokenizer->tokenType() !== JackTokenizer::SYMBOL || $this->tokenizer->symbol() !== ';') {
            $this->engine->compileExpression(); // ;
        } else {
            // void function
            $this->vmWriter->writePush('constant', 0);
        }

        $this->vmWriter->writeReturn();

        $this->tokenizer->advance();
    }
}
