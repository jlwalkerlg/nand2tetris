<?php

require_once __DIR__ . '/CompilationModule.php';

// class className { classVarDec* subroutineDec* }

class ClassCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->tokenizer->advance(); // class

        $this->tokenizer->advance(); // className
        $className = $this->tokenizer->identifier();

        $this->tokenizer->advance(); // {
        $this->tokenizer->advance(); // classVarDec|subroutineDec|}

        while ($this->tokenizer->hasMoreTokens()) {
            if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD) {
                switch ($this->tokenizer->keyword()) {
                    case JackTokenizer::FIELD:
                    case JackTokenizer::STATIC:
                        $this->engine->compileClassVarDec($className);
                        break;
                    case JackTokenizer::CONSTRUCTOR:
                    case JackTokenizer::FUNCTION:
                    case JackTokenizer::METHOD:
                        $this->engine->compileSubroutine($className);
                        break;
                }
            }
        }
    }
}
