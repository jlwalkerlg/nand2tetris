<?php

require_once __DIR__ . '/CompilationModule.php';

// ((type varName) (, type varName)*)?

class ParameterListCompiler extends CompilationModule
{
    private $types = [
        JackTokenizer::INT => 'int',
        JackTokenizer::CHAR => 'char',
        JackTokenizer::BOOLEAN => 'boolean',
    ];

    public function compile()
    {
        // )|type

        while (true) {
            if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL) {
                if ($this->tokenizer->symbol() === ')') {
                    return;
                }
            }

            // type

            if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD) {
                $type = $this->types[$this->tokenizer->keyword()];
            } else {
                $type = $this->tokenizer->identifier();
            }

            $this->tokenizer->advance(); // varName
            $varName = $this->tokenizer->identifier();

            $this->tokenizer->advance(); // ,|)

            if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL) {
                if ($this->tokenizer->symbol() === ',') {
                    $this->tokenizer->advance(); // type
                }
            }

            $this->symbolTable->define($varName, $type, 'argument');
        }
    }
}
