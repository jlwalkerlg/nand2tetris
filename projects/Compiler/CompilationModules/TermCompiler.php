<?php

require_once __DIR__ . '/CompilationModule.php';

class TermCompiler extends CompilationModule
{
    public function compile(): void
    {
        switch ($this->tokenizer->tokenType()) {
            case JackTokenizer::INT_CONST:
                $this->vmWriter->writePush('constant', $this->tokenizer->intVal());
                return;
            case JackTokenizer::STRING_CONST:
                $this->engine->compileStringConst();
                return;
            case JackTokenizer::KEYWORD:
                $this->compileKeywordConst();
                return;
            case JackTokenizer::SYMBOL:
                if ($this->tokenizer->symbol() === '(') {
                    $this->tokenizer->advance();
                    $this->engine->compileExpression();

                    $this->tokenizer->advance();
                    // )
                } else if ($this->tokenizer->symbol() === '-') {
                    $this->tokenizer->advance();
                    $this->engine->compileTerm();
                    $this->vmWriter->writeArithmetic('neg');
                } else if ($this->tokenizer->symbol() === '~') {
                    $this->tokenizer->advance();
                    $this->engine->compileTerm();
                    $this->vmWriter->writeArithmetic('not');
                }
                return;
            case JackTokenizer::IDENTIFIER:
                $this->tokenizer->advance();
                if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL && $this->tokenizer->symbol() === '[') {
                    // array access
                    $this->tokenizer->back();

                    $this->engine->compileIdentifier();

                    $this->tokenizer->advance();
                    // [

                    $this->tokenizer->advance();
                    $this->engine->compileExpression();

                    $this->tokenizer->advance();
                    // ]
                } else if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL && $this->tokenizer->symbol() === '.') {
                    // subroutine call
                    $this->tokenizer->back();
                    $this->engine->compileSubroutineCall();
                } else {
                    // just a var name
                    $this->tokenizer->back();
                    $this->engine->compileIdentifier();
                }
                return;
        }
    }

    private function compileKeywordConst()
    {
        switch ($this->tokenizer->keyword()) {
            case JackTokenizer::TRUE:
                $this->vmWriter->writePush('constant', 1);
                $this->vmWriter->writeArithmetic('neg');
                return;
            case JackTokenizer::FALSE:
                return $this->vmWriter->writePush('constant', 0);
            case JackTokenizer::NULL:
                return $this->vmWriter->writePush('constant', 0);
            case JackTokenizer::THIS:
                return $this->vmWriter->writePush('pointer', 0);
        }
    }
}
