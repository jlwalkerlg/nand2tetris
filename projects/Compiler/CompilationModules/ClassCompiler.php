<?php

require_once __DIR__ . '/CompilationModule.php';

class ClassCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->writer->writeOpeningTag('class');

        $this->tokenizer->advance();
        $this->writer->writeTag('keyword', 'class');

        $this->tokenizer->advance();
        $this->engine->compileIdentifier('class');

        $this->tokenizer->advance();
        $this->engine->compileSymbol();

        while ($this->tokenizer->hasMoreTokens()) {
            $this->tokenizer->advance();

            switch ($this->tokenizer->tokenType()) {
                case JackTokenizer::KEYWORD:
                    switch ($this->tokenizer->keyword()) {
                        case JackTokenizer::CONSTRUCTOR:
                        case JackTokenizer::FUNCTION:
                        case JackTokenizer::METHOD:
                            $this->engine->compileSubroutine();
                            break;
                        case JackTokenizer::FIELD:
                        case JackTokenizer::STATIC:
                            $this->engine->compileClassVarDec();
                            break;
                    }
                    break;
                case JackTokenizer::SYMBOL:
                    $this->engine->compileSymbol();
                    break;
            }
        }

        $this->writer->writeClosingTag('class');
    }
}
