<?php

require_once __DIR__ . '/CompilationModule.php';

class ExpressionCompiler extends CompilationModule
{
    private $ops = [
        '+' => 'add',
        '-' => 'neg',
        '*' => 'Math.multiply()',
        '/' => 'Math.divide()',
        '&' => 'and',
        '|' => 'or',
        '<' => 'lt',
        '>' => 'gt',
        '=' => 'eq',
    ];

    public function compile(): void
    {
        $this->engine->compileTerm();

        $this->tokenizer->advance();
        if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL && array_key_exists($this->tokenizer->symbol(), $this->ops)) {
            $operator = $this->tokenizer->symbol();

            $this->tokenizer->advance();
            $this->engine->compileTerm();

            $this->compileOperator($operator);
        } else {
            $this->tokenizer->back();
        }
    }

    private function compileOperator(string $operator)
    {
        if ($operator === '*') {
            return $this->vmWriter->writeCall('Math.multiply', 2);
        }

        if ($operator === '/') {
            return $this->vmWriter->writeCall('Math.divide', 2);
        }

        return $this->vmWriter->writeArithmetic($this->ops[$operator]);
    }
}
