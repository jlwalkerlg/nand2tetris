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
        $this->writer->writeOpeningTag('varDec');

        $this->writer->writeTag('keyword', 'var');

        $this->tokenizer->advance();
        if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD) {
            $type = $this->types[$this->tokenizer->keyword()];
        } else {
            $type = $this->tokenizer->identifier();
        }
        $this->engine->compileType();

        $this->tokenizer->advance();
        $identifier = $this->tokenizer->identifier();
        $this->symbolTable->define($identifier, $type, 'var');
        $this->engine->compileIdentifier('var', true);

        while (true) {
            $this->tokenizer->advance();
            $this->engine->compileSymbol();

            if ($this->tokenizer->symbol() === ';') break;

            $this->tokenizer->advance();
            $identifier = $this->tokenizer->identifier();
            $this->symbolTable->define($identifier, $type, 'var');
            $this->engine->compileIdentifier('var', true);
        }

        $this->writer->writeClosingTag('varDec');
    }
}
