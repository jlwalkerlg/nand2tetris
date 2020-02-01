<?php

require_once __DIR__ . '/CompilationModule.php';

class IdentifierCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->writer->writeTag('identifier', $this->tokenizer->identifier());
    }
}
