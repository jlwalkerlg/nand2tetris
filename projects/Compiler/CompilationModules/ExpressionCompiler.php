<?php

require_once __DIR__ . '/CompilationModule.php';

// term (op term)*

class ExpressionCompiler extends CompilationModule
{
    private $ops = [
        '+' => 'add',
        '-' => 'sub',
        '*' => null,
        '/' => null,
        '&' => 'and',
        '|' => 'or',
        '<' => 'lt',
        '>' => 'gt',
        '=' => 'eq',
    ];

    public function compile(): void
    {
        $this->engine->compileTerm();
        // op?

        while (true) {
            if ($this->tokenizer->tokenType() !== JackTokenizer::SYMBOL) return;
            if (!array_key_exists($this->tokenizer->symbol(), $this->ops)) return;

            // op term
            $operator = $this->tokenizer->symbol();
            $this->tokenizer->advance(); // term
            $this->engine->compileTerm();

            $this->compileOperator($operator);
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

        $this->vmWriter->writeArithmetic($this->ops[$operator]);
    }
}
