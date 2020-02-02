<?php

require_once __DIR__ . '/CompilationModule.php';

// integerConstant|stringConstant|keywordConstant|varName|varName[expression]|subroutineCall|(expression)|(~|-) term

class TermCompiler extends CompilationModule
{
    public function compile()
    {
        switch ($this->tokenizer->tokenType()) {
            case JackTokenizer::INT_CONST:
                return $this->compileIntegerConstant();
            case JackTokenizer::STRING_CONST:
                return $this->compileStringConstant();
            case JackTokenizer::KEYWORD:
                return $this->compileKeywordConstant();
            case JackTokenizer::IDENTIFIER:
                $identifier = $this->tokenizer->identifier();

                $this->tokenizer->advance();

                if ($this->tokenizer->tokenType() !== JackTokenizer::SYMBOL) {
                    return $this->compileVarName($identifier);
                }

                if ($this->tokenizer->symbol() === '[') {
                    return $this->compileArrayAccess($identifier);
                }

                $this->tokenizer->back();
                // subroutineCall
                return $this->engine->compileSubroutineCall();
            case JackTokenizer::SYMBOL:
                if ($this->tokenizer->symbol() === '-') {
                    $this->tokenizer->advance(); // term
                    $this->engine->compileTerm();
                    $this->vmWriter->writeArithmetic('neg');
                    return;
                }

                if ($this->tokenizer->symbol() === '~') {
                    $this->tokenizer->advance(); // term
                    $this->engine->compileTerm();
                    $this->vmWriter->writeArithmetic('not');
                    return;
                }

                // (
                $this->tokenizer->advance(); // expression
                $this->engine->compileExpression(); // )
                $this->tokenizer->advance();
                return;
        }
    }

    private function compileIntegerConstant()
    {
        $this->vmWriter->writePush('constant', $this->tokenizer->intVal());
        $this->tokenizer->advance();
    }

    private function compileStringConstant()
    {
        $this->vmWriter->writePush('constant', $this->tokenizer->intVal());
        $this->tokenizer->advance();
    }

    private function compileKeywordConstant()
    {
        switch ($this->tokenizer->keyword()) {
            case JackTokenizer::TRUE:
                $this->vmWriter->writePush('constant', 1);
                $this->vmWriter->writeArithmetic('neg');
                break;
            case JackTokenizer::FALSE:
                $this->vmWriter->writePush('constant', 0);
                break;
            case JackTokenizer::NULL:
                $this->vmWriter->writePush('constant', 0);
                break;
            case JackTokenizer::THIS:
                $this->vmWriter->writePush('pointer', 0);
                break;
        }

        $this->tokenizer->advance();
    }

    private function compileVarName(string $varName)
    {
        $segment = $this->symbolTable->kindOf($varName);
        $index = $this->symbolTable->indexOf($varName);

        $this->vmWriter->writePush($this->getSegment($segment), $index);
    }

    private function compileArrayAccess(string $varName)
    {
        // [

        $this->tokenizer->advance(); // expression

        $this->engine->compileExpression(); // ]

        $segment = $this->symbolTable->kindOf($varName);
        $index = $this->symbolTable->indexOf($varName);

        $this->vmWriter->writePush($this->getSegment($segment), $index);
        $this->vmWriter->writeArithmetic('add');
        $this->vmWriter->writePop('pointer', 1);
        $this->vmWriter->writePush('pointer', 1);

        $this->tokenizer->advance();
    }
}
