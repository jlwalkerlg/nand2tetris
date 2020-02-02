<?php

require_once __DIR__ . '/CompilationModule.php';

// while (expression) { statements }

class WhileCompiler extends CompilationModule
{
    public function compile(): void
    {
        // while

        $l1 = $this->generateLabel();
        $l2 = $this->generateLabel();

        $this->tokenizer->advance(); // (

        $this->writer->writeLabel($l1);

        $this->tokenizer->advance(); // expression
        $this->engine->compileExpression(); // )
        $this->writer->writeArithmetic('not');

        $this->writer->writeIf($l2);

        $this->tokenizer->advance(); // {

        $this->tokenizer->advance(); // statements
        $this->engine->compileStatements(); // }

        $this->writer->writeGoto($l1);
        $this->writer->writeLabel($l2);

        $this->tokenizer->advance();
    }
}
