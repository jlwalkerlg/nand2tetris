<?php

class Tokenizer
{
    // Token types.
    const KEYWORD = 1;
    const SYMBOL = 2;
    const IDENTIFIER = 3;
    const INT_CONST = 4;
    const STRING_CONST = 5;

    // Keyword types.
    const CLASSS = 1;
    const CONSTRUCTOR = 2;
    const FUNCTION = 3;
    const METHOD = 4;
    const FIELD = 5;
    const STATIC = 6;
    const VAR = 7;
    const INT = 8;
    const CHAR = 9;
    const BOOLEAN = 10;
    const VOID = 11;
    const TRUE = 12;
    const FALSE = 13;
    const NULL = 14;
    const THIS = 15;
    const THAT = 16;
    const LET = 17;
    const DO = 18;
    const IF = 19;
    const ELSE = 20;
    const WHILE = 21;
    const RETURN = 22;

    private $keywords = [
        'class', 'constructor', 'function', 'method', 'field', 'static', 'var', 'int', 'char', 'boolean', 'void', 'true', 'false', 'null', 'this', 'that', 'let', 'do', 'if', 'else', 'while', 'return',
    ];

    private $symbols = [
        '{', '}', '(', ')', '[', ']', '.', ',', ';', '+', '-', '*', '/', '&', '|', '<', '>', '=', '~',
    ];

    // TODO: make currentToken private; public for debugging purposes only.
    public $currentToken;
    private $tokenType;

    private $inputFile;

    public function __construct($inputFile)
    {
        $this->inputFile = $inputFile;
    }

    public function hasMoreTokens(): bool
    {
        $this->skipWhitespace();

        return !feof($this->inputFile);
    }

    public function advance(): void
    {
        foreach ($this->keywords as $word) {
            if ($this->isNextString($word)) {
                $this->tokenType = self::KEYWORD;
                $this->currentToken = $this->readString($word);

                return;
            }
        }

        foreach ($this->symbols as $symbol) {
            if ($this->isNextString($symbol)) {
                $this->tokenType = self::SYMBOL;
                $this->currentToken = $this->readString($symbol);

                return;
            }
        }

        if ($this->isNextString('"')) {
            $this->tokenType = self::STRING_CONST;

            fseek($this->inputFile, 1, SEEK_CUR);
            $this->currentToken = $this->readUntilChar('"');
            fseek($this->inputFile, 1, SEEK_CUR);

            return;
        }

        if ($this->isInt()) {
            $this->tokenType = self::INT_CONST;
            $this->currentToken = $this->readInt();

            return;
        }

        $this->tokenType = self::IDENTIFIER;
        $this->currentToken = $this->readUntilRegex('/[^a-zA-Z0-9_]/');
    }

    public function tokenType(): int
    {
        return $this->tokenType;
    }

    public function keyWord(): int
    {
        $keyword = strtoupper($this->currentToken);

        if ($keyword === 'CLASS') {
            $keyword = 'CLASSS';
        }

        return constant("self::{$keyword}");
    }

    public function symbol(): string
    {
        return $this->currentToken;
    }

    public function identifier(): string
    {
        return $this->currentToken;
    }

    public function intVal(): int
    {
        return $this->currentToken;
    }

    public function stringVal(): string
    {
        return $this->currentToken;
    }

    private function isNextString(string $string): bool
    {
        $position = ftell($this->inputFile);
        $result = fread($this->inputFile, strlen($string)) === $string;
        fseek($this->inputFile, $position);
        return $result;
    }

    private function readString(string $string): string
    {
        return fread($this->inputFile, strlen($string));
    }

    private function readUntilChar(string $match): string
    {
        $result = '';

        while (true) {
            $char = fgetc($this->inputFile);

            if ($char === $match) {
                fseek($this->inputFile, -1, SEEK_CUR);
                return $result;
            }

            $result .= $char;
        }
    }

    private function isInt(): bool
    {
        $result = is_numeric(fgetc($this->inputFile));
        fseek($this->inputFile, -1, SEEK_CUR);
        return $result;
    }

    private function readInt(): string
    {
        $int = '';

        while (true) {
            $char = fgetc($this->inputFile);

            if (!is_numeric($char)) {
                fseek($this->inputFile, -1, SEEK_CUR);
                return (int) $int;
            }

            $int .= $char;
        }
    }

    private function readUntilRegex(string $regex): string
    {
        $result = '';

        while (true) {
            $char = fgetc($this->inputFile);

            if (preg_match($regex, $char) === 1) {
                fseek($this->inputFile, -1, SEEK_CUR);
                return $result;
            }

            $result .= $char;
        }
    }

    private function skipWhitespace(): void
    {
        while (true) {
            $char = fgetc($this->inputFile);

            if (ctype_space($char)) continue;

            if ($char === '/' && $this->isNextString('/')) {
                $this->skipLine();
                continue;
            }

            if ($char === '/' && $this->isNextString('*')) {
                $this->skipMultilineComment();
                continue;
            }

            // found token -- go back 1 char if not EOF then return
            if ($char !== false) fseek($this->inputFile, -1, SEEK_CUR);
            return;
        }
    }

    private function skipLine(): void
    {
        fgets($this->inputFile);
    }

    private function skipMultilineComment(): void
    {
        while (true) {
            $char = fgetc($this->inputFile);

            if ($char === '*' && $this->isNextString('/')) {
                fgetc($this->inputFile);
                return;
            }
        }
    }
}
