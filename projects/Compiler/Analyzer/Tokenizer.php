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

    private $inputFile;
    private $currentToken;
    private $tokenType;

    public function __construct($inputFile)
    {
        $this->inputFile = $inputFile;
    }

    public function hasMoreTokens(): bool
    {
        $this->skipWhitespace();

        return !feof($this->inputFile);
    }

    public function advance(): string
    {
        foreach ($this->keywords as $word) {
            if ($this->isNextString($word)) {
                $this->tokenType = self::KEYWORD;

                return $this->currentToken = $this->readString($word);
            }
        }

        foreach ($this->symbols as $symbol) {
            if ($this->isNextString($symbol)) {
                $this->tokenType = self::SYMBOL;

                return $this->currentToken = $this->readString($symbol);
            }
        }

        if ($this->isNextString('"')) {
            $this->tokenType = self::STRING_CONST;

            fseek($this->inputFile, 1, SEEK_CUR);
            $this->currentToken = $this->readUntilChar('"');
            fseek($this->inputFile, 1, SEEK_CUR);

            return $this->currentToken;
        }

        if ($this->isInt()) {
            $this->tokenType = self::INT_CONST;

            return $this->currentToken = $this->readInt();
        }

        $this->tokenType = self::IDENTIFIER;

        return $this->currentToken = $this->readUntilRegex('/[^a-zA-Z0-9_]/');
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
        $whitespaceChars = [' ', "\n", "\t"];

        while (true) {
            $char = fgetc($this->inputFile);

            // EOF
            if ($char === false) return;

            // string literal
            if ($char === '"') break;

            // not a comment
            if ($char !== '/') {
                if (in_array($char, $whitespaceChars)) continue;

                break;
            }

            $nextChar = fgetc($this->inputFile);
            if ($nextChar === '/') {
                // single-line comment -- skip line
                fgets($this->inputFile);
            } else if ($nextChar === '*') {
                // multi-line comment -- skip until end of comment
                $this->skipMultilineComment();
            } else {
                // not a comment -- go back 1 and break
                fseek($this->inputFile, -1, SEEK_CUR);
                break;
            }
        }

        // found token -- go back 1 char
        fseek($this->inputFile, -1, SEEK_CUR);
    }

    private function skipMultilineComment()
    {
        while (true) {
            $char = fgetc($this->inputFile);

            if ($char === '*') {
                if (fgetc($this->inputFile) === '/') {
                    return;
                } else {
                    fseek($this->inputFile, -1, SEEK_CUR);
                }
            }
        }
    }
}
