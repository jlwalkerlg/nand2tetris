<?php

require_once __DIR__ . '/CompilationModule.php';

// if (expression) {statements} (else {statements})?

class IfCompiler extends CompilationModule
{
    public function compile(): void
    {
        // if

        $l1 = $this->generateLabel();
        $l2 = $this->generateLabel();

        $this->tokenizer->advance(); // (
        $this->tokenizer->advance(); // expression
        $this->engine->compileExpression();
        $this->vmWriter->writeArithmetic('not');
        $this->vmWriter->writeIf($l1);
        $this->tokenizer->advance(); // )
        $this->tokenizer->advance(); // {
        $this->engine->compileStatements(); // }
        $this->vmWriter->writeGoto($l2);

        $this->vmWriter->writeLabel($l1);

        $this->tokenizer->advance(); // else?
        if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD && $this->tokenizer->keyword() === JackTokenizer::ELSE) {
            $this->tokenizer->advance(); // {
            $this->tokenizer->advance(); // statements
            $this->engine->compileStatements(); // }
            $this->tokenizer->advance();
        }

        $this->vmWriter->writeLabel($l2);
    }
}
