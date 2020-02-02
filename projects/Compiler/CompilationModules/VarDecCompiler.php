<?php

require_once __DIR__ . '/CompilationModule.php';

// var type varName (, varName)*;

class VarDecCompiler extends CompilationModule
{
    private $types = [
        JackTokenizer::INT => 'int',
        JackTokenizer::CHAR => 'char',
        JackTokenizer::BOOLEAN => 'boolean',
    ];

    public function compile(): void
    {
        // var

        $this->tokenizer->advance(); // type
        if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD) {
            $type = $this->types[$this->tokenizer->keyword()];
        } else {
            $type = $this->tokenizer->identifier();
        }

        $this->tokenizer->advance(); // varName
        $varName = $this->tokenizer->identifier();

        $this->symbolTable->define($varName, $type, 'var');

        $this->tokenizer->advance(); // ,|;

        while (true) {
            if ($this->tokenizer->symbol() === ';') {
                $this->tokenizer->advance();
                return;
            }

            // ,
            $this->tokenizer->advance(); // varName
            $varName = $this->tokenizer->identifier();

            $this->symbolTable->define($varName, $type, 'var');

            $this->tokenizer->advance();
        }
    }
}
