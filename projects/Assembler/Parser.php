<?php

class Parser
{
    private $symbols;

    public function __construct()
    {
        $this->symbols = SymbolTable::getInstance();
    }

    public function trim(string $line)
    {
        $line = $this->removeInlineComment($line);
        return trim($line);
    }

    private function removeInlineComment(string $line)
    {
        $pos = strpos($line, '//');
        if ($pos !== false) {
            $line = substr($line, 0, $pos);
        }

        return $line;
    }

    public function isInstruction(string $line)
    {
        if ($line === '') {
            return false;
        }

        if (substr($line, 0, 2) === '//') {
            return false;
        }

        if ($this->isLabel($line)) {
            return false;
        }

        return true;
    }

    public function isAInstruction(string $instruction)
    {
        return substr($instruction, 0, 1) === '@';
    }

    public function parseAInstruction(string $instruction)
    {
        $value = substr($instruction, 1);

        if (!$this->isSymbol($value)) {
            return $value;
        }

        if (!$this->symbols->has($value)) {
            $this->symbols->add($value);
        }

        return $this->symbols->get($value);
    }

    private function isSymbol(string $value)
    {
        return !is_numeric($value);
    }

    public function parseCInstruction(string $instruction)
    {
        if (strpos($instruction, ';') !== false) {
            $exploded = explode(';', $instruction);
            $instruction = $exploded[0];
            $jump = $exploded[1];
        } else {
            $jump = 'null';
        }

        if (strpos($instruction, '=') !== false) {
            $exploded = explode('=', $instruction);
            $dest = $exploded[0];
            $comp = $exploded[1];
        } else {
            $dest = 'null';
            $comp = $instruction;
        }

        return [
            'dest' => $dest,
            'comp' => $comp,
            'jump' => $jump,
        ];
    }

    public function isLabel(string $instruction)
    {
        return substr($instruction, 0, 1) === '(' && substr($instruction, -1) === ')';
    }

    public function parseLabel(string $instruction)
    {
        return trim($instruction, '()');
    }
}
