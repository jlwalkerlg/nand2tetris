<?php

require_once __DIR__ . '/CompilationModule.php';

class SubroutineCompiler extends CompilationModule
{
    private $map = [
        JackTokenizer::CONSTRUCTOR => 'constructor',
        JackTokenizer::FUNCTION => 'function',
        JackTokenizer::METHOD => 'method',
    ];

    public function compile(string $class): void
    {
        $this->symbolTable->startSubroutine();

        // constructor|method|function
        $subroutineType = $this->map[$this->tokenizer->keyword()];

        $this->tokenizer->advance();
        // void|int|char|booolean|ClassName

        $this->tokenizer->advance();
        // subroutine name
        $subroutineName = $this->tokenizer->identifier();

        $this->tokenizer->advance();
        // (

        $this->tokenizer->advance();
        $nLocals = $this->engine->compileParameterList();

        $this->vmWriter->writeFunction("{$class}.{$subroutineName}", $nLocals);

        $this->tokenizer->advance();
        // )

        $this->tokenizer->advance();
        $this->compileSubroutineBody();
    }

    private function compileSubroutineBody(): void
    {
        // {

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
        // }

        $this->tokenizer->advance();
    }
}
