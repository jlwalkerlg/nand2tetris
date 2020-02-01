<?php

require_once __DIR__ . '/CompilationModule.php';

class IntConstCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->writer->writeTag('integerConstant', $this->tokenizer->intVal());
    }
}
