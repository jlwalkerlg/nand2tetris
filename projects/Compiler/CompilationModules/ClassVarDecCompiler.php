<?php

require_once __DIR__ . '/CompilationModule.php';

class ClassVarDecCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->writer->writeOpeningTag('classVarDec');

        switch ($this->tokenizer->keyword()) {
            case JackTokenizer::FIELD:
                $this->writer->writeTag('keyword', 'field');
                break;
            case JackTokenizer::STATIC:
                $this->writer->writeTag('keyword', 'static');
                break;
        }

        $this->tokenizer->advance();
        $this->engine->compileType();

        $this->tokenizer->advance();
        $this->engine->compileIdentifier();

        while (true) {
            $this->tokenizer->advance();
            $this->engine->compileSymbol();

            if ($this->tokenizer->symbol() === ';') break;

            $this->tokenizer->advance();
            $this->engine->compileIdentifier();
        }

        $this->writer->writeClosingTag('classVarDec');
    }
}
