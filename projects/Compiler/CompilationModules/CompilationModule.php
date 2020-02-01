<?php

require_once __DIR__ . '/../JackTokenizer.php';
require_once __DIR__ . '/../XmlStream.php';
require_once __DIR__ . '/../CompilationEngine.php';

abstract class CompilationModule
{
    protected $tokenizer;
    protected $writer;
    protected $engine;

    public function __construct(JackTokenizer $tokenizer, XmlStream $writer, CompilationEngine $engine)
    {
        $this->tokenizer = $tokenizer;
        $this->writer = $writer;
        $this->engine = $engine;
    }

    abstract public function compile(): void;
}
