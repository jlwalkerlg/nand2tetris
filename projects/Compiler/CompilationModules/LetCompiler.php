<?php

require_once __DIR__ . '/CompilationModule.php';

// let varName([expression])? = expression;

class LetCompiler extends CompilationModule
{
    public function compile(): void
    {
        // let

        $this->tokenizer->advance(); // varName

        $varName = $this->tokenizer->identifier();
        $segment = $this->symbolTable->kindOf($varName);
        $index = $this->symbolTable->indexOf($varName);

        $this->tokenizer->advance(); // [|=

        if ($this->tokenizer->symbol() === '[') {
            $this->tokenizer->advance(); // expression
            $this->engine->compileExpression(); // ]

            $this->tokenizer->advance();
        }

        $this->tokenizer->advance(); // expression
        $this->engine->compileExpression(); // ;

        $this->tokenizer->advance();

        $this->vmWriter->writePop($this->getSegment($segment), $index);
    }
}
