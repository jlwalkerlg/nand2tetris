<?php

require_once __DIR__ . '/CompilationModule.php';

class TermCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->writer->writeOpeningTag('term');

        switch ($this->tokenizer->tokenType()) {
            case JackTokenizer::INT_CONST:
                $this->engine->compileIntConst();
                break;
            case JackTokenizer::STRING_CONST:
                $this->engine->compileStringConst();
                break;
            case JackTokenizer::KEYWORD:
                $this->engine->compileKeywordConst();
                break;
            case JackTokenizer::SYMBOL:
                if ($this->tokenizer->symbol() === '(') {
                    $this->engine->compileSymbol();

                    $this->tokenizer->advance();
                    $this->engine->compileExpression();

                    $this->tokenizer->advance();
                    $this->engine->compileSymbol();
                } else if ($this->tokenizer->symbol() === '-' || $this->tokenizer->symbol() === '~') {
                    $this->engine->compileSymbol();

                    $this->tokenizer->advance();
                    $this->engine->compileTerm();
                }
                break;
            case JackTokenizer::IDENTIFIER:
                $this->tokenizer->advance();
                if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL && $this->tokenizer->symbol() === '[') {
                    // array access
                    $this->tokenizer->back();

                    $this->engine->compileIdentifier();

                    $this->tokenizer->advance();
                    $this->engine->compileSymbol();

                    $this->tokenizer->advance();
                    $this->engine->compileExpression();

                    $this->tokenizer->advance();
                    $this->engine->compileSymbol();
                } else if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL && $this->tokenizer->symbol() === '.') {
                    // subroutine call
                    $this->tokenizer->back();
                    $this->engine->compileSubroutineCall();
                } else {
                    // just a var name
                    $this->tokenizer->back();
                    $this->engine->compileIdentifier();
                }
                break;
        }

        $this->writer->writeClosingTag('term');
    }
}
