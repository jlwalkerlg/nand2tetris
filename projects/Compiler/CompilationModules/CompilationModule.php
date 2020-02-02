<?php

require_once __DIR__ . '/../JackTokenizer.php';
require_once __DIR__ . '/../XmlStream.php';
require_once __DIR__ . '/../CompilationEngine.php';

abstract class CompilationModule
{
    static $labelCount = 0;

    protected $tokenizer;
    protected $vmWriter;
    protected $symbolTable;
    protected $writer;
    protected $engine;

    public function __construct(JackTokenizer $tokenizer, VMWriter $vmWriter, SymbolTable $symbolTable, XmlStream $writer, CompilationEngine $engine)
    {
        $this->tokenizer = $tokenizer;
        $this->vmWriter = $vmWriter;
        $this->symbolTable = $symbolTable;
        $this->writer = $writer;
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
        for ($i = 0; $i < $n; $i++) {
            var_dump($this->tokenizer->currentToken);
            $this->tokenizer->advance();
        }
        for ($i = 0; $i < $n; $i++) {
            $this->tokenizer->back();
        }
    }
}
