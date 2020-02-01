<?php

require_once __DIR__ . '/CompilationModule.php';

class KeywordConstCompiler extends CompilationModule
{
    public function compile(): void
    {
        $map = [
            JackTokenizer::TRUE => 'true',
            JackTokenizer::FALSE => 'false',
            JackTokenizer::NULL => 'null',
            JackTokenizer::THIS => 'this',
        ];

        $this->writer->writeTag('keyword', $map[$this->tokenizer->keyword()]);
    }
}
