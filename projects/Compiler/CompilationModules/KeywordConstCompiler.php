<?php

require_once __DIR__ . '/CompilationModule.php';

class KeywordConstCompiler extends CompilationModule
{
    private $map = [
        JackTokenizer::TRUE => 'true',
        JackTokenizer::FALSE => 'false',
        JackTokenizer::NULL => 'null',
        JackTokenizer::THIS => 'this',
    ];

    public function compile(): void
    {
        $this->writer->writeTag('keyword', $this->map[$this->tokenizer->keyword()]);
    }
}
