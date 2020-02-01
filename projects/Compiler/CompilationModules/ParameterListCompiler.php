<?php

require_once __DIR__ . '/CompilationModule.php';

class ParameterListCompiler extends CompilationModule
{
    private $types = [
        JackTokenizer::INT => 'int',
        JackTokenizer::CHAR => 'char',
        JackTokenizer::BOOLEAN => 'boolean',
    ];

    public function compile(): void
    {
        $this->writer->writeOpeningTag('parameterList');

        while (true) {
            if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL) {
                if ($this->tokenizer->symbol() === ')') {
                    $this->tokenizer->back();
                    break;
                }
                $this->engine->compileSymbol();
            } else {
                if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD) {
                    $type = $this->types[$this->tokenizer->keyword()];
                } else {
                    $type = $this->tokenizer->identifier();
                }
                $this->engine->compileType();

                $this->tokenizer->advance();
                $identifier = $this->tokenizer->identifier();
                $this->symbolTable->define($identifier, $type, 'argument');
                $this->engine->compileIdentifier('argument', true);
            }

            $this->tokenizer->advance();
        }

        $this->writer->writeClosingTag('parameterList');
    }
}
