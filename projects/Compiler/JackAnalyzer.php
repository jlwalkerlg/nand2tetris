<?php

require_once __DIR__ . '/JackTokenizer.php';
require_once __DIR__ . '/CompilationEngine.php';
require_once __DIR__ . '/SymbolTable.php';
require_once __DIR__ . '/VMWriter.php';

if ($argc !== 2 || !file_exists($argv[1])) {
    exit('Usage: "php JackAnalyzer.php [input]" where input is path to a .jack file or a diirectory containing one or more .jack files.');
}

$analyzer = new JackAnalyzer;
$analyzer->run($argv[1]);

class JackAnalyzer
{
    private $prefix = 'JW_';

    public function run(string $input)
    {
        $files = $this->getFiles($input);

        foreach ($files as $inputFilename => $outputFilename) {
            $outputFile = fopen($outputFilename, 'w');

            $tokenizer = new JackTokenizer($inputFilename);
            $writer = new VMWriter($outputFile);
            $symbolTable = new SymbolTable;
            $xmlWriter = new XmlStream($outputFile);
            $compilationEngine = new CompilationEngine($tokenizer, $writer, $symbolTable, $xmlWriter);

            $compilationEngine->compileClass();

            $tokenizer->close();
            $writer->close();
        }
    }

    private function getFiles(string $path)
    {
        $path = realpath($path);

        if (!is_dir($path)) {
            return [$path => dirname($path) . '/' . $this->getOutputFilename($path)];
        }

        $files = [];

        $contents = scandir($path);
        foreach ($contents as $filename) {
            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            if ($ext === 'jack') {
                $files[$path . '/' . $filename] = $path . '/' . $this->getOutputFilename($filename);
            }
        }

        return $files;
    }

    private function getOutputFilename(string $inputFilename)
    {
        return $this->prefix . basename($inputFilename, '.jack') . '.xml';
    }
}
