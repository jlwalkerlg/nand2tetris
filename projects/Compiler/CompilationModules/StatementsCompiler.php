<?php

require_once __DIR__ . '/CompilationModule.php';

class StatementsCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->writer->writeOpeningTag('statements');

        while (true) {
            if ($this->tokenizer->tokenType() !== JackTokenizer::KEYWORD) {
                $this->tokenizer->back();
                break;
            }

            $this->compileStatement();
            $this->tokenizer->advance();
        }

        $this->writer->writeClosingTag('statements');
    }

    public function compileStatement(): void
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
