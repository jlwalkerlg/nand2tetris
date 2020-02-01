<?php

require_once __DIR__ . '/CompilationModule.php';

class ParameterListCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->writer->writeOpeningTag('parameterList');

        while (true) {
            if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL) {
                if ($this->tokenizer->symbol() === ')') {
                    $this->tokenizer->back();
                    break;
                }
                $this->engine->compileSymbol();
            } else {
                $this->engine->compileType();

                $this->tokenizer->advance();
                $this->engine->compileIdentifier();
            }

            $this->tokenizer->advance();
        }

        $this->writer->writeClosingTag('parameterList');
    }
}
