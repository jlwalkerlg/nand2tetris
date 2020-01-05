<?php

require_once __DIR__ . '/CommandTypes.php';

class Parser
{
    private $file;
    private $currentLine;
    private $currentCommand;

    public function __construct(string $filename)
    {
        $this->file = fopen($filename, 'r');
    }

    public function __destruct()
    {
        fclose($this->file);
    }

    public function hasMoreCommands(): bool
    {
        while (!feof($this->file)) {
            $this->currentLine = trim(fgets($this->file));
            if ($this->lineIsCommand($this->currentLine)) {
                return true;
            }
        }

        return false;
    }

    private function lineIsCommand(string $line): bool
    {
        return !empty($line) && substr($line, 0, 2) !== '//';
    }

    public function advance(): void
    {
        $this->currentCommand = explode(' ', $this->currentLine);
    }

    public function commandType(): string
    {
        if ($this->isArithmeticCommand()) {
            return C_ARITHMETIC;
        }

        if ($this->isPushCommand()) {
            return C_PUSH;
        }

        if ($this->isPopCommand()) {
            return C_POP;
        }

        if ($this->isLabelCommand()) {
            return C_LABEL;
        }

        if ($this->isIfCommand()) {
            return C_IF;
        }

        if ($this->isGotoCommand()) {
            return C_GOTO;
        }

        if ($this->isFunctionCommand()) {
            return C_FUNCTION;
        }

        if ($this->isCallCommand()) {
            return C_CALL;
        }

        if ($this->isReturnCommand()) {
            return C_RETURN;
        }

        throw new Exception('Command type unknown: ' . $this->currentCommand[0]);
    }

    private function isArithmeticCommand(): bool
    {
        return ($this->commandStartsWith('add')
            || $this->commandStartsWith('sub')
            || $this->commandStartsWith('neg')
            || $this->commandStartsWith('eq')
            || $this->commandStartsWith('gt')
            || $this->commandStartsWith('lt')
            || $this->commandStartsWith('and')
            || $this->commandStartsWith('or')
            || $this->commandStartsWith('not'));
    }

    private function isPushCommand(): bool
    {
        return $this->commandStartsWith('push');
    }

    private function isPopCommand(): bool
    {
        return $this->commandStartsWith('pop');
    }

    private function isLabelCommand(): bool
    {
        return $this->commandStartsWith('label');
    }

    private function isIfCommand(): bool
    {
        return $this->commandStartsWith('if-goto');
    }

    private function isGotoCommand(): bool
    {
        return $this->commandStartsWith('goto');
    }

    private function isFunctionCommand(): bool
    {
        return $this->commandStartsWith('function');
    }

    private function isCallCommand(): bool
    {
        return $this->commandStartsWith('call');
    }

    private function isReturnCommand(): bool
    {
        return $this->commandStartsWith('return');
    }

    private function commandStartsWith(string $string): bool
    {
        return $string === substr($this->currentLine, 0, strlen($string));
    }

    public function arg1(): string
    {
        if ($this->isArithmeticCommand()) {
            return $this->currentCommand[0];
        }

        return $this->currentCommand[1];
    }

    public function arg2(): int
    {
        return (int) $this->currentCommand[2];
    }
}
