<?php

require_once __DIR__ . '/CompilationModule.php';

class TypeCompiler extends CompilationModule
{
    private $map = [
        JackTokenizer::INT => 'int',
        JackTokenizer::CHAR => 'char',
        JackTokenizer::BOOLEAN => 'boolean',
    ];

    public function compile(): void
    {
        if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD && array_key_exists($this->tokenizer->keyword(), $this->map)) {
            $this->writer->writeTag('keyword', $this->map[$this->tokenizer->keyword()]);
        } else {
            $this->engine->compileIdentifier('class');
        }
    }
}
