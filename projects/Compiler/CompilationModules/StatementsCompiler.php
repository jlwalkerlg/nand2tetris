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

            $this->engine->compileStatement();
            $this->tokenizer->advance();
        }

        $this->writer->writeClosingTag('statements');
    }
}
