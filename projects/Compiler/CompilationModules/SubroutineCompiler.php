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

        $subroutineType = $this->map[$this->tokenizer->keyword()];

        $this->tokenizer->advance(); // void|int|char|boolean|ClassName

        $this->symbolTable->startSubroutine();

        $this->tokenizer->advance(); // subroutineName
        $subroutineName = $this->tokenizer->identifier();

        $this->tokenizer->advance(); // (

        $this->tokenizer->advance(); // parameterList
        $this->engine->compileParameterList(); // )

        $this->tokenizer->advance(); // {

        $this->tokenizer->advance(); // varDec|statements

        $nLocals = 0;
        while ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD && $this->tokenizer->keyword() === JackTokenizer::VAR) {
            $nLocals += $this->engine->compileVarDec(); // varDec|statements
        }

        $this->vmWriter->writeFunction("{$class}.{$subroutineName}", $nLocals);

        $this->engine->compileStatements(); // }

        $this->tokenizer->advance();
    }
}
