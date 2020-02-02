<?php

require_once __DIR__ . '/CompilationModule.php';

// constructor|function|method void|type subroutineName (parameterList) subroutineBody

class SubroutineCompiler extends CompilationModule
{
    private $map = [
        JackTokenizer::CONSTRUCTOR => 'constructor',
        JackTokenizer::FUNCTION => 'function',
        JackTokenizer::METHOD => 'method',
    ];

    public function compile(string $class): void
    {
        // constructor|function|method

        $this->symbolTable->startSubroutine();

        $subroutineType = $this->map[$this->tokenizer->keyword()];

        $this->tokenizer->advance(); // void|int|char|boolean|ClassName

        $this->tokenizer->advance(); // subroutineName
        $subroutineName = $this->tokenizer->identifier();

        $this->tokenizer->advance(); // (

        $this->tokenizer->advance(); // parameterList
        $nLocals = $this->engine->compileParameterList(); // )

        $this->vmWriter->writeFunction("{$class}.{$subroutineName}", $nLocals);

        $this->tokenizer->advance(); // subroutineBody
        $this->compileSubroutineBody();
    }

    // { varDec* statements }
    private function compileSubroutineBody(): void
    {
        // {

        $this->tokenizer->advance(); // varDec|statements

        while ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD && $this->tokenizer->keyword() === JackTokenizer::VAR) {
            $this->engine->compileVarDec(); // varDec|statements
        }

        $this->engine->compileStatements(); // }

        $this->tokenizer->advance();
    }
}
