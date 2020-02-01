<?php

require_once __DIR__ . '/CompilationModule.php';

class ClassVarDecCompiler extends CompilationModule
{
    private $types = [
        JackTokenizer::INT => 'int',
        JackTokenizer::CHAR => 'char',
        JackTokenizer::BOOLEAN => 'boolean',
    ];

    public function compile(): void
    {
        $this->writer->writeOpeningTag('classVarDec');

        switch ($this->tokenizer->keyword()) {
            case JackTokenizer::FIELD:
                $kind = $category = 'field';
                break;
            case JackTokenizer::STATIC:
                $kind = $category = 'static';
                break;
        }

        $this->writer->writeTag('keyword', $kind);

        $this->tokenizer->advance();
        if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD) {
            $type = $this->types[$this->tokenizer->keyword()];
        } else {
            $type = $this->tokenizer->identifier();
        }
        $this->engine->compileType();

        $this->tokenizer->advance();
        $identifier = $this->tokenizer->identifier();
        $this->symbolTable->define($identifier, $type, $kind);
        $this->engine->compileIdentifier($category, true);

        while (true) {
            $this->tokenizer->advance();
            $this->engine->compileSymbol();

            if ($this->tokenizer->symbol() === ';') break;

            $this->tokenizer->advance();
            $identifier = $this->tokenizer->identifier($category, true);
            $this->symbolTable->define($identifier, $type, $kind);
            $this->engine->compileIdentifier();
        }

        $this->writer->writeClosingTag('classVarDec');
    }
}
