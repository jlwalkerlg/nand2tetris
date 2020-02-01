<?php

require_once __DIR__ . '/CompilationModule.php';

class TypeCompiler extends CompilationModule
{
    public function compile(): void
    {
        $map = [
            JackTokenizer::INT => 'int',
            JackTokenizer::CHAR => 'char',
            JackTokenizer::BOOLEAN => 'boolean',
        ];

        if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD && array_key_exists($this->tokenizer->keyword(), $map)) {
            $this->writer->writeTag('keyword', $map[$this->tokenizer->keyword()]);
        } else {
            $this->engine->compileIdentifier();
        }
    }
}
