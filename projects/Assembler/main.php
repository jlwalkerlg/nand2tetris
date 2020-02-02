<?php

require_once __DIR__ . '/SymbolTable.php';
require_once __DIR__ . '/Parser.php';
require_once __DIR__ . '/Translator.php';

$symbols = SymbolTable::getInstance();
$parser = new Parser;
$translator = new Translator;

$instructions = [];

$infileName = pathinfo($argv[1], PATHINFO_FILENAME);
$outputDir = dirname(realpath($argv[1]));
$outfileName = $outputDir . '/' . $infileName . '.hack';

$infile = fopen($argv[1], 'r');

$lineNumber = 0;

// First pass.
while (!feof($infile)) {

    $line = fgets($infile);
    $line = $parser->trim($line);

    if ($parser->isInstruction($line)) {
        $lineNumber++;
        continue;
    }

    if ($parser->isLabel($line)) {
        $label = $parser->parseLabel($line);
        $symbols->addLabel($label, $lineNumber);
    }
}

rewind($infile);

// Second pass.
while (!feof($infile)) {

    $line = fgets($infile);
    $line = $parser->trim($line);

    if (!$parser->isInstruction($line)) continue;

    if ($parser->isAInstruction($line)) {
        $value = $parser->parseAInstruction($line);
        $instructions[] = $translator->translateAInstruction($value);
    } else {
        $info = $parser->parseCInstruction($line);
        $instructions[] = $translator->translateCInstruction($info);
    }
}

fclose($infile);

file_put_contents($outfileName, implode("\n", $instructions));
