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

        if ($subroutineType === 'method') {
            $this->symbolTable->define('this', $class, 'argument');
        }

        $this->tokenizer->advance(); // void|int|char|boolean|ClassName

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

        if ($subroutineType === 'constructor') {
            $nFields = $this->symbolTable->varCount('field');
            $this->vmWriter->writePush('constant', $nFields);
            $this->vmWriter->writeCall('Memory.alloc', 1);
            $this->vmWriter->writePop('pointer', 0);
        }

        if ($subroutineType === 'method') {
            $this->vmWriter->writePush('argument', 0);
            $this->vmWriter->writePop('pointer', 0);
        }

        $this->engine->compileStatements(); // }

        $this->tokenizer->advance();
    }
}
