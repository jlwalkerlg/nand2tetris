<?php

require_once __DIR__ . '/Tokenizer.php';
require_once __DIR__ . '/XmlStream.php';

class CompilationEngine
{
    private $tokenizer;
    private $outputFile;

    public function __construct(Tokenizer $tokenizer, $outputFile)
    {
        $this->tokenizer = $tokenizer;
        $this->outputFile = $outputFile;
        $this->xmlWriter = new XmlStream($this->outputFile);
    }

    public function compileClass(): void
    {
        $this->xmlWriter->writeOpeningTag('class');

        while ($this->tokenizer->hasMoreTokens()) {
            $this->tokenizer->advance();
            $this->xmlWriter->writeTag('token', $this->tokenizer->currentToken);
        }

        $this->xmlWriter->writeClosingTag('class');
    }
}
