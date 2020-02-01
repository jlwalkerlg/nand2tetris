<?php

class XmlStream
{
    private $indent = 0;

    private $outputFile;

    public function __construct($outputFile)
    {
        $this->outputFile = $outputFile;
    }

    public function writeOpeningTag(string $tag, array $attrs = []): void
    {
        $this->writeIndents();
        fwrite($this->outputFile, "<{$this->openingTag($tag,$attrs)}>\n");
        $this->indent += 2;
    }

    public function writeClosingTag(string $tag): void
    {
        $this->indent -= 2;
        $this->writeIndents();
        fwrite($this->outputFile, "</{$tag}>\n");
    }

    public function writeTag(string $tag, string $contents, array $attrs = []): void
    {
        $this->writeIndents();
        fwrite($this->outputFile, "<{$this->openingTag($tag,$attrs)}> {$contents} </{$tag}>\n");
    }

    public function writeIndents(): void
    {
        for ($n = 0; $n < $this->indent; $n++) {
            fwrite($this->outputFile, ' ');
        }
    }

    private function openingTag(string $tag, array $attrs = []): string
    {
        foreach ($attrs as $key => $value) {
            if ($value !== null) {
                $tag .= ' ' . $key . '="' . $value . '"';
            }
        }

        return $tag;
    }
}
