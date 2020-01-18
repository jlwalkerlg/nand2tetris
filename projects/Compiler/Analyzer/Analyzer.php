<?php

require_once __DIR__ . '/Tokenizer.php';
require_once __DIR__ . '/CompilationEngine.php';

if ($argc !== 2 || !file_exists($argv[1])) {
    exit('Usage: "php Analyzer.php [input]" where input is path to a .jack file or a diirectory containing one or more .jack files.');
}

$analyzer = new Analyzer;
$analyzer->run($argv[1]);

class Analyzer
{
    private $prefix = 'JW_';

    public function run(string $input)
    {
        $files = $this->getFiles($input);

        foreach ($files as $inputFilename => $outputFilename) {
            $inputFile = fopen($inputFilename, 'r');
            $outputFile = fopen($outputFilename, 'w');

            $tokenizer = new Tokenizer($inputFile);
            $compilationEngine = new CompilationEngine($tokenizer, $outputFile);

            $compilationEngine->compileClass();

            fclose($inputFile);
            fclose($outputFile);
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
