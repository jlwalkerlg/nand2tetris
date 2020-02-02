<?php

require_once __DIR__ . '/../JackTokenizer.php';
require_once __DIR__ . '/../XmlStream.php';
require_once __DIR__ . '/../CompilationEngine.php';

abstract class CompilationModule
{
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
}
