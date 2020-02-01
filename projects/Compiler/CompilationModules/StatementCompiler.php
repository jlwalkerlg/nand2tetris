<?php

require_once __DIR__ . '/CompilationModule.php';

class StatementCompiler extends CompilationModule
{
    public function compile(): void
    {
        switch ($this->tokenizer->keyword()) {
            case JackTokenizer::LET:
                $this->engine->compileLet();
                break;
            case JackTokenizer::IF:
                $this->engine->compileIf();
                break;
            case JackTokenizer::WHILE:
                $this->engine->compileWhile();
                break;
            case JackTokenizer::DO:
                $this->engine->compileDo();
                break;
            case JackTokenizer::RETURN:
                $this->engine->compileReturn();
                break;
        }
    }
}
