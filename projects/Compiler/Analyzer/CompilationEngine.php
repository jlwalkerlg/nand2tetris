<?php

class CompilationEngine
{
    private $inputFile;
    private $outputFile;

    public function __construct($inputFile, $outputFile)
    {
        $this->inputFile = $inputFile;
        $this->outputFile = $outputFile;
    }
}
