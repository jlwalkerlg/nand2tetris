<?php

require_once __DIR__ . '/CompilationModule.php';

// statement*
// (let|if|while|do|return)*

class StatementsCompiler extends CompilationModule
{
    public function compile(): void
    {
        while (true) {
            if ($this->tokenizer->tokenType() !== JackTokenizer::KEYWORD) {
                return;
            }

            switch ($this->tokenizer->keyword()) {
                case JackTokenizer::LET:
                    $this->engine->compileLet(); // statement?
                    break;
                case JackTokenizer::IF:
                    $this->engine->compileIf(); // statement?
                    break;
                case JackTokenizer::WHILE:
                    $this->engine->compileWhile(); // statement?
                    break;
                case JackTokenizer::DO:
                    $this->engine->compileDo(); // statement?
                    break;
                case JackTokenizer::RETURN:
                    $this->engine->compileReturn(); // statement?
                    break;
            }
        }
    }
}
