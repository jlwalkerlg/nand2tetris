<?php

require_once __DIR__ . '/CompilationModule.php';

class ParameterListCompiler extends CompilationModule
{
    private $types = [
        JackTokenizer::INT => 'int',
        JackTokenizer::CHAR => 'char',
        JackTokenizer::BOOLEAN => 'boolean',
    ];

    public function compile(): int
    {
        $nParams = 0;

        while (true) {
            if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL) {
                if ($this->tokenizer->symbol() === ')') {
                    $this->tokenizer->back();
                    return $nParams;
                }

                if ($this->tokenizer->symbol() === ',') {
                    $this->tokenizer->advance();
                }
            }

            if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD) {
                $type = $this->types[$this->tokenizer->keyword()];
            } else {
                $type = $this->tokenizer->identifier();
            }

            $this->tokenizer->advance();
            $varName = $this->tokenizer->identifier();

            $nParams++;
            $this->symbolTable->define($varName, $type, 'argument');

            $this->tokenizer->advance();
        }
    }
}
