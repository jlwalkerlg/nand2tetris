<?php

class SymbolTable
{
    private static $instance;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private $symbols = [
        'R0' => 0,
        'R1' => 1,
        'R2' => 2,
        'R3' => 3,
        'R4' => 4,
        'R5' => 5,
        'R6' => 6,
        'R7' => 7,
        'R8' => 8,
        'R9' => 9,
        'R10' => 10,
        'R11' => 11,
        'R12' => 12,
        'R13' => 13,
        'R14' => 14,
        'R15' => 15,
        'SP' => 0,
        'LCL' => 1,
        'ARG' => 2,
        'THIS' => 3,
        'THAT' => 4,
        'SCREEN' => 16384,
        'KBD' => 24576,
    ];

    private $labels = [];

    public function add(string $symbol)
    {
        $this->symbols[$symbol] = $this->getAvailableValue();
    }

    private function getAvailableValue()
    {
        $taken = array_values($this->symbols);

        $value = 16;

        while (true) {
            if (!in_array($value, $taken)) {
                break;
            }

            $value++;
        }

        return $value;
    }

    public function has(string $value)
    {
        return array_key_exists($value, $this->symbols) || array_key_exists($value, $this->labels);
    }

    public function get(string $value)
    {
        return $this->symbols[$value] ?? $this->labels[$value];
    }

    public function addLabel(string $label, int $value)
    {
        $this->labels[$label] = $value;
    }
}
