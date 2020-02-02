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
        $isArray = $this->tokenizer->symbol() === '[';

        if ($isArray) {
            $this->tokenizer->advance(); // expression
            $this->engine->compileExpression(); // ]

            // leave array index at top of stack
            $this->writer->writePush($this->getSegment($segment), $index);
            $this->writer->writeArithmetic('add');

            $this->tokenizer->advance();
        }

        $this->tokenizer->advance(); // expression
        $this->engine->compileExpression(); // ;

        $this->tokenizer->advance();

        if ($isArray) {
            // put result of expression in temp
            $this->writer->writePop('temp', 0);

            // pop array index into pointer 1
            $this->writer->writePop('pointer', 1);

            // put result back on stack
            $this->writer->writePush('temp', 0);

            // pop result into that 0
            $this->writer->writePop('that', 0);
        } else {
            $this->writer->writePop($this->getSegment($segment), $index);
        }
    }
}
