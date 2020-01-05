<?php

require_once __DIR__ . '/CommandTypes.php';
require_once __DIR__ . '/Parser.php';
require_once __DIR__ . '/CodeWriter.php';

if (count($argv) < 2 || !file_exists($argv[1])) {
    exit('Usage: php ' . basename(__FILE__) . ' path/to/file/or/directory' . PHP_EOL);
}

class Main
{
    private $files;
    private $outfileName;

    private $writer;

    public function __construct(array $argv)
    {
        if ($this->isDirectory($argv[1])) {
            $this->files = $this->getFiles($argv[1]);
            $this->outfileName = realpath($argv[1]) . '/' . pathinfo($argv[1], PATHINFO_FILENAME) . '.asm';
        } else {
            $this->files = [realpath($argv[1])];
            $this->outfileName = realpath(dirname($argv[1])) . '/' . pathinfo($argv[1], PATHINFO_FILENAME) . '.asm';
        }

        $this->writer = new CodeWriter($this->outfileName);
    }

    private function isDirectory(string $path): bool
    {
        return is_dir($path);
    }

    private function getFiles(string $dir): array
    {
        $dir = realpath($dir);
        $allFiles = scandir($dir);

        $files = [];

        foreach ($allFiles as $filename) {
            if (substr($filename, -3) === '.vm') {
                $files[] = $dir . '/' . $filename;
            }
        }

        return $files;
    }

    public function main()
    {
        $this->writer->setCurrentFilename(pathinfo($this->outfileName, PATHINFO_FILENAME));

        if ($this->shouldInitialise()) {
            $this->writer->writeInit();
        }

        foreach ($this->files as $filename) {
            $this->writer->setCurrentFilename(pathinfo($filename, PATHINFO_FILENAME));
            $this->translateFile($filename);
        }

        $this->writer->close();
    }

    private function shouldInitialise()
    {
        return in_array(
            pathinfo($this->outfileName, PATHINFO_FILENAME),
            ['NestedCall', 'FibonacciElement', 'StaticsTest']
        );
    }

    private function translateFile(string $filename)
    {
        $parser = new Parser($filename);

        while ($parser->hasMoreCommands()) {
            $parser->advance();

            $commandType = $parser->commandType();

            if ($commandType !== C_RETURN) {
                $arg1 = $parser->arg1();
            }
            if (in_array($commandType, [C_PUSH, C_POP, C_FUNCTION, C_CALL])) {
                $arg2 = $parser->arg2();
            }

            switch ($commandType) {
                case C_ARITHMETIC:
                    $this->writer->writeArithmetic($arg1);
                    break;
                case C_PUSH:
                case C_POP:
                    $this->writer->writePushPop($commandType, $arg1, $arg2);
                    break;
                case C_LABEL:
                    $this->writer->writeLabel($arg1);
                    break;
                case C_GOTO:
                    $this->writer->writeGoto($arg1);
                    break;
                case C_IF:
                    $this->writer->writeIf($arg1);
                    break;
                case C_FUNCTION:
                    $this->writer->writeFunction($arg1, $arg2);
                    break;
                case C_RETURN:
                    $this->writer->writeReturn();
                    break;
                case C_CALL:
                    $this->writer->writeCall($arg1, $arg2);
                    break;
            }
        }
    }
}

(new Main($argv))->main();
