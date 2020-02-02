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
                $this->tokenizer->advance();

                if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL) {
                    if ($this->tokenizer->symbol() === '[') {
                        $this->tokenizer->back();
                        return $this->compileArrayAccess();
                    }

                    if ($this->tokenizer->symbol() === '(' || $this->tokenizer->symbol() === '.') {
                        $this->tokenizer->back();
                        return $this->engine->compileSubroutineCall();
                    }
                }

                $this->tokenizer->back();
                return $this->compileVarName();
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
        $string = $this->tokenizer->stringVal();
        $len = strlen($string);

        // create new string object, leave base addr on stack
        $this->vmWriter->writePush('constant', $len);
        $this->vmWriter->writeCall('String.new', 1);

        // append each char to string object
        for ($i = 0; $i < $len; $i++) {
            $this->vmWriter->writePush('constant', ord($string[$i]));
            $this->vmWriter->writeCall('String.appendChar', 2);
        }

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

    private function compileVarName()
    {
        // varName
        $varName = $this->tokenizer->identifier();

        $segment = $this->symbolTable->kindOf($varName);
        $index = $this->symbolTable->indexOf($varName);

        $this->vmWriter->writePush($this->getSegment($segment), $index);

        $this->tokenizer->advance();
    }

    private function compileArrayAccess()
    {
        // varName
        $varName = $this->tokenizer->identifier();

        $this->tokenizer->advance(); // [
        $this->tokenizer->advance(); // expression

        $this->engine->compileExpression(); // ]

        $segment = $this->symbolTable->kindOf($varName);
        $index = $this->symbolTable->indexOf($varName);

        $this->vmWriter->writePush($this->getSegment($segment), $index);
        $this->vmWriter->writeArithmetic('add');
        $this->vmWriter->writePop('pointer', 1);
        $this->vmWriter->writePush('that', 0);

        $this->tokenizer->advance();
    }
}
