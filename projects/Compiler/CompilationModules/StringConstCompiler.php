<?php

require_once __DIR__ . '/CompilationModule.php';

class StringConstCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->writer->writeTag('stringConstant', $this->tokenizer->stringVal());
    }
}
