<?php

require_once __DIR__ . '/CompilationModule.php';

class StatementsCompiler extends CompilationModule
{
    public function compile(): void
    {
        while (true) {
            if ($this->tokenizer->tokenType() !== JackTokenizer::KEYWORD) {
                $this->tokenizer->back();
                return;
            }

            $this->compileStatement();
        }
    }

    private function compileStatement(): void
    {
        switch ($this->tokenizer->keyword()) {
            case JackTokenizer::LET:
                $this->engine->compileLet();
                return;
            case JackTokenizer::IF:
                $this->engine->compileIf();
                return;
            case JackTokenizer::WHILE:
                $this->engine->compileWhile();
                return;
            case JackTokenizer::DO:
                $this->engine->compileDo();
                return;
            case JackTokenizer::RETURN:
                $this->engine->compileReturn();
                return;
        }
    }
}
