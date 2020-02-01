<?php

require_once __DIR__ . '/CompilationModule.php';

class SubroutineCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->writer->writeOpeningTag('subroutineDec');

        $map = [
            JackTokenizer::CONSTRUCTOR => 'constructor',
            JackTokenizer::FUNCTION => 'function',
            JackTokenizer::METHOD => 'method',
        ];

        $this->writer->writeTag('keyword', $map[$this->tokenizer->keyword()]);

        $this->tokenizer->advance();
        if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD && $this->tokenizer->keyword() === JackTokenizer::VOID) {
            $this->writer->writeTag('keyword', 'void');
        } else {
            $this->engine->compileType();
        }

        $this->tokenizer->advance();
        $this->engine->compileIdentifier('subroutine');

        $this->tokenizer->advance();
        $this->engine->compileSymbol();

        $this->tokenizer->advance();
        $this->engine->compileParameterList();

        $this->tokenizer->advance();
        $this->engine->compileSymbol();

        $this->tokenizer->advance();
        $this->compileSubroutineBody();

        $this->writer->writeClosingTag('subroutineDec');
    }

    private function compileSubroutineBody(): void
    {
        $this->writer->writeOpeningTag('subroutineBody');

        $this->engine->compileSymbol();

        while (true) {
            $this->tokenizer->advance();

            if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD && $this->tokenizer->keyword() === JackTokenizer::VAR) {
                $this->engine->compileVarDec();
            } else {
                break;
            }
        }

        $this->engine->compileStatements();

        $this->tokenizer->advance();
        $this->engine->compileSymbol();

        $this->writer->writeClosingTag('subroutineBody');
    }
}
