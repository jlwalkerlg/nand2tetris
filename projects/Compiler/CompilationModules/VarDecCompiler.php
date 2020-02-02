<?php

require_once __DIR__ . '/CompilationModule.php';

class VarDecCompiler extends CompilationModule
{
    private $types = [
        JackTokenizer::INT => 'int',
        JackTokenizer::CHAR => 'char',
        JackTokenizer::BOOLEAN => 'boolean',
    ];

    public function compile(): void
    {
        $this->tokenizer->advance();
        if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD) {
            $type = $this->types[$this->tokenizer->keyword()];
        } else {
            $type = $this->tokenizer->identifier();
        }
        // int|char|boolean|ClassName

        $this->tokenizer->advance();

        $varName = $this->tokenizer->identifier();
        $this->symbolTable->define($varName, $type, 'var');

        while (true) {
            $this->tokenizer->advance();

            if ($this->tokenizer->symbol() === ';') break;

            $this->tokenizer->advance();

            $varName = $this->tokenizer->identifier();
            $this->symbolTable->define($varName, $type, 'var');
        }
    }
}
