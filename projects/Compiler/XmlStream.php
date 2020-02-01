<?php

class XmlStream
{
    private $indent = 0;

    private $outputFile;

    public function __construct($outputFile)
    {
        $this->outputFile = $outputFile;
    }

    public function writeOpeningTag(string $tag): void
    {
        $this->writeIndents();
        fwrite($this->outputFile, "<{$tag}>\n");
        $this->indent += 2;
    }

    public function writeClosingTag(string $tag): void
    {
        $this->indent -= 2;
        $this->writeIndents();
        fwrite($this->outputFile, "</{$tag}>\n");
    }

    public function writeTag(string $tag, string $contents): void
    {
        $this->writeIndents();
        fwrite($this->outputFile, "<{$tag}> {$contents} </{$tag}>\n");
    }

    public function writeIndents(): void
    {
        for ($n = 0; $n < $this->indent; $n++) {
            fwrite($this->outputFile, ' ');
        }
    }
}
