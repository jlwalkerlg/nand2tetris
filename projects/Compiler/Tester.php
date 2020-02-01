<?php

require_once __DIR__ . '/JackAnalyzer.php';

if ($argc !== 2 || !file_exists($argv[1])) {
    exit('Usage: "php Tester.php [input]" where input is path to a .jack file or a directory containing one or more .jack files.');
}

$analyzer = new Tester;
$analyzer->run($argv[1]);

class Tester
{
    private $prefix = 'JW_';

    public function run(string $input)
    {
        $analyzer = new JackAnalyzer;
        $analyzer->run($input);

        $files = $this->getFiles($input);

        foreach ($files as [$outputFilename, $compareFilename]) {
            $result = [];
            exec('../tools/TextComparer.sh ' . $outputFilename . ' ' . $compareFilename, $result);
            $result = implode('', $result);
            if ($result !== 'Comparison ended successfully') {
                echo "Test failed for file {$outputFilename}:" . PHP_EOL . PHP_EOL;
                echo $result . PHP_EOL;
                return;
            }
        }

        echo 'Test finished successfully.' . PHP_EOL;
        return;
    }

    private function getFiles(string $path)
    {
        $path = realpath($path);

        if (!is_dir($path)) {
            $outputFilename = dirname($path) . '/' . $this->getOutputFilename($path);
            $compareFilename = dirname($path) . '/' . $this->getCompareFilename($path);
            return [$outputFilename, $compareFilename];
        }

        $files = [];

        $contents = scandir($path);
        foreach ($contents as $filename) {
            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            if ($ext === 'jack') {
                $outputFilename = $path . '/' . $this->getOutputFilename($filename);
                $compareFilename = $path . '/' . $this->getCompareFilename($filename);
                $files[] = [$outputFilename, $compareFilename];
            }
        }

        return $files;
    }

    private function getOutputFilename(string $inputFilename)
    {
        return $this->prefix . basename($inputFilename, '.jack') . '.xml';
    }

    private function getCompareFilename(string $inputFilename): string
    {
        return basename($inputFilename, '.jack') . '.xml';
    }
}
