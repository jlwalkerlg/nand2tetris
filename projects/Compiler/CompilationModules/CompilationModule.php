<?php

require_once __DIR__ . '/../JackTokenizer.php';
require_once __DIR__ . '/../CompilationEngine.php';

abstract class CompilationModule
{
    static $labelCount = 0;

    protected $tokenizer;
    protected $writer;
    protected $symbolTable;
    protected $engine;

    public function __construct(JackTokenizer $tokenizer, VMWriter $writer, SymbolTable $symbolTable, CompilationEngine $engine)
    {
        $this->tokenizer = $tokenizer;
        $this->writer = $writer;
        $this->symbolTable = $symbolTable;
        $this->engine = $engine;
    }

    protected function generateLabel(): string
    {
        return 'L' . self::$labelCount++;
    }

    protected function getSegment(string $segment): string
    {
        if ($segment === 'var') $segment = 'local';
        if ($segment === 'field') $segment = 'this';

        return $segment;
    }

    protected function peek(int $n)
    {
        if ($n < 0) {
            for ($i = $n; $i < 0; $i++) {
                $this->tokenizer->back();
            }
        }

        for ($i = 0; $i < abs($n); $i++) {
            var_dump($this->tokenizer->currentToken);
            $this->tokenizer->advance();
        }

        if ($n > 0) {
            for ($i = 0; $i < $n; $i++) {
                $this->tokenizer->back();
            }
        }
    }
}
