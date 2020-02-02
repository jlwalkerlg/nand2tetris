<?php

require_once __DIR__ . '/CompilationModule.php';

class ClassCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->tokenizer->advance();

        $this->tokenizer->advance();
        $class = $this->tokenizer->identifier();

        $this->tokenizer->advance();

        while ($this->tokenizer->hasMoreTokens()) {
            $this->tokenizer->advance();

            if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD) {
                switch ($this->tokenizer->keyword()) {
                    case JackTokenizer::CONSTRUCTOR:
                    case JackTokenizer::FUNCTION:
                    case JackTokenizer::METHOD:
                        $this->engine->compileSubroutine($class);
                        break;
                    case JackTokenizer::FIELD:
                    case JackTokenizer::STATIC:
                        $this->engine->compileClassVarDec($class);
                        break;
                }
            }
        }
    }
}
