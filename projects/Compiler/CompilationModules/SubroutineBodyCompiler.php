<?php

require_once __DIR__ . '/CompilationModule.php';

class SubroutineBodyCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->writer->writeOpeningTag('subroutineBody');

        $this->engine->compileSymbol();

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
        $this->engine->compileSymbol();

        $this->writer->writeClosingTag('subroutineBody');
    }
}
