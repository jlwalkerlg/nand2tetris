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
                    $this->writer->writeArithmetic('neg');
                    return;
                }

                if ($this->tokenizer->symbol() === '~') {
                    $this->tokenizer->advance(); // term
                    $this->engine->compileTerm();
                    $this->writer->writeArithmetic('not');
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
        $this->writer->writePush('constant', $this->tokenizer->intVal());
        $this->tokenizer->advance();
    }

    private function compileStringConstant()
    {
        $string = $this->tokenizer->stringVal();
        $len = strlen($string);

        // create new string object, leave base addr on stack
        $this->writer->writePush('constant', $len);
        $this->writer->writeCall('String.new', 1);

        // append each char to string object
        for ($i = 0; $i < $len; $i++) {
            $this->writer->writePush('constant', ord($string[$i]));
            $this->writer->writeCall('String.appendChar', 2);
        }

        $this->tokenizer->advance();
    }

    private function compileKeywordConstant()
    {
        switch ($this->tokenizer->keyword()) {
            case JackTokenizer::TRUE:
                $this->writer->writePush('constant', 1);
                $this->writer->writeArithmetic('neg');
                break;
            case JackTokenizer::FALSE:
                $this->writer->writePush('constant', 0);
                break;
            case JackTokenizer::NULL:
                $this->writer->writePush('constant', 0);
                break;
            case JackTokenizer::THIS:
                $this->writer->writePush('pointer', 0);
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

        $this->writer->writePush($this->getSegment($segment), $index);

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

        $this->writer->writePush($this->getSegment($segment), $index);
        $this->writer->writeArithmetic('add');
        $this->writer->writePop('pointer', 1);
        $this->writer->writePush('that', 0);

        $this->tokenizer->advance();
    }
}
