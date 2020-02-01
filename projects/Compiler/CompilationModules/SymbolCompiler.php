<?php

require_once __DIR__ . '/CompilationModule.php';

class SymbolCompiler extends CompilationModule
{
    public function compile(): void
    {
        $map = [
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

        $symbol = $this->tokenizer->symbol();
        if (array_key_exists($symbol, $map)) {
            $symbol = $map[$symbol];
        }

        $this->writer->writeTag('symbol', $symbol);
    }
}
