<?php

require_once __DIR__ . '/CompilationModule.php';

class SymbolCompiler extends CompilationModule
{
    private $map = [
        '+' => '+',
        '-' => '-',
        '*' => '*',
        '/' => '/',
        '&' => '&',
        '|' => '|',
        '<' => '&lt;',
        '>' => '&gt;',
        '=' => '=',
    ];

    public function compile(): void
    {
        $symbol = $this->tokenizer->symbol();
        if (array_key_exists($symbol, $this->map)) {
            $symbol = $this->map[$symbol];
        }

        $this->writer->writeTag('symbol', $symbol);
    }
}
