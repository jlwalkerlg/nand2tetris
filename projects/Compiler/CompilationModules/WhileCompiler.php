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

        $this->vmWriter->writeLabel($l1);

        $this->tokenizer->advance(); // expression
        $this->engine->compileExpression(); // )
        $this->vmWriter->writeArithmetic('not');

        $this->vmWriter->writeIf($l2);

        $this->tokenizer->advance(); // {

        $this->tokenizer->advance(); // statements
        $this->engine->compileStatements(); // }

        $this->vmWriter->writeGoto($l1);
        $this->vmWriter->writeLabel($l2);

        $this->tokenizer->advance();
    }
}
