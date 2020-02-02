<?php

require_once __DIR__ . '/CompilationModule.php';

// static|field type varName (, varName)*;

class ClassVarDecCompiler extends CompilationModule
{
    private $types = [
        JackTokenizer::INT => 'int',
        JackTokenizer::CHAR => 'char',
        JackTokenizer::BOOLEAN => 'boolean',
    ];

    public function compile(): void
    {
        // field|static
        switch ($this->tokenizer->keyword()) {
            case JackTokenizer::FIELD:
                $kind = $category = 'field';
                break;
            case JackTokenizer::STATIC:
                $kind = $category = 'static';
                break;
        }

        $this->tokenizer->advance(); // type

        if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD) {
            $type = $this->types[$this->tokenizer->keyword()];
        } else {
            $type = $this->tokenizer->identifier();
        }

        $this->tokenizer->advance(); // varName

        $varName = $this->tokenizer->identifier();
        $this->symbolTable->define($varName, $type, $kind);

        while (true) {
            $this->tokenizer->advance(); // ,|;

            if ($this->tokenizer->symbol() === ';') {
                $this->tokenizer->advance();
                return;
            }

            $this->tokenizer->advance(); // varName

            $varName = $this->tokenizer->identifier();
            $this->symbolTable->define($varName, $type, $kind);
        }
    }
}
