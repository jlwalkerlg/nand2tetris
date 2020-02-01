<?php

require_once __DIR__ . '/JackTokenizer.php';
require_once __DIR__ . '/XmlStream.php';

class CompilationEngine
{
    private $tokenizer;
    private $outputFile;
    private $xmlWriter;

    public function __construct(JackTokenizer $tokenizer, $outputFile)
    {
        $this->tokenizer = $tokenizer;
        $this->outputFile = $outputFile;
        $this->xmlWriter = new XmlStream($this->outputFile);
    }

    public function compileClass(): void
    {
        $this->xmlWriter->writeOpeningTag('class');

        $this->tokenizer->advance();
        $this->xmlWriter->writeTag('keyword', 'class');

        $this->tokenizer->advance();
        $this->compileIdentifier();

        $this->tokenizer->advance();
        $this->compileSymbol();

        while ($this->tokenizer->hasMoreTokens()) {
            $this->tokenizer->advance();

            switch ($this->tokenizer->tokenType()) {
                case JackTokenizer::KEYWORD:
                    switch ($this->tokenizer->keyword()) {
                        case JackTokenizer::CONSTRUCTOR:
                        case JackTokenizer::FUNCTION:
                        case JackTokenizer::METHOD:
                            $this->compileSubroutine();
                            break;
                        case JackTokenizer::FIELD:
                        case JackTokenizer::STATIC:
                            $this->compileClassVarDec();
                            break;
                    }
                    break;
                case JackTokenizer::SYMBOL:
                    $this->compileSymbol();
                    break;
            }
        }

        $this->xmlWriter->writeClosingTag('class');
    }

    private function compileSubroutine(): void
    {
        $this->xmlWriter->writeOpeningTag('subroutineDec');

        $map = [
            JackTokenizer::CONSTRUCTOR => 'constructor',
            JackTokenizer::FUNCTION => 'function',
            JackTokenizer::METHOD => 'method',
        ];

        $this->xmlWriter->writeTag('keyword', $map[$this->tokenizer->keyword()]);

        $this->tokenizer->advance();
        if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD && $this->tokenizer->keyword() === JackTokenizer::VOID) {
            $this->xmlWriter->writeTag('keyword', 'void');
        } else {
            $this->compileType();
        }

        $this->tokenizer->advance();
        $this->compileIdentifier();

        $this->tokenizer->advance();
        $this->compileSymbol();

        $this->tokenizer->advance();
        $this->compileParameterList();

        $this->tokenizer->advance();
        $this->compileSymbol();

        $this->tokenizer->advance();
        $this->compileSubroutineBody();

        $this->xmlWriter->writeClosingTag('subroutineDec');
    }

    private function compileParameterList(): void
    {
        $this->xmlWriter->writeOpeningTag('parameterList');

        while (true) {
            if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL) {
                if ($this->tokenizer->symbol() === ')') {
                    $this->tokenizer->back();
                    break;
                }
                $this->compileSymbol();
            } else {
                $this->compileType();

                $this->tokenizer->advance();
                $this->compileIdentifier();
            }

            $this->tokenizer->advance();
        }

        $this->xmlWriter->writeClosingTag('parameterList');
    }

    private function compileExpressionList(): void
    {
        $this->xmlWriter->writeOpeningTag('expressionList');

        while (true) {
            if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL) {
                if ($this->tokenizer->symbol() === ')') {
                    $this->tokenizer->back();
                    break;
                }
                $this->compileSymbol();
            } else {
                $this->compileExpression();
            }

            $this->tokenizer->advance();
        }

        $this->xmlWriter->writeClosingTag('expressionList');
    }

    private function compileSubroutineBody(): void
    {
        $this->xmlWriter->writeOpeningTag('subroutineBody');

        $this->compileSymbol();

        while (true) {
            $this->tokenizer->advance();

            if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD && $this->tokenizer->keyword() === JackTokenizer::VAR) {
                $this->compileVarDec();
            } else {
                break;
            }
        }

        $this->compileStatements();

        $this->tokenizer->advance();
        $this->compileSymbol();

        $this->xmlWriter->writeClosingTag('subroutineBody');
    }

    private function compileClassVarDec(): void
    {
        $this->xmlWriter->writeOpeningTag('classVarDec');

        switch ($this->tokenizer->keyword()) {
            case JackTokenizer::FIELD:
                $this->xmlWriter->writeTag('keyword', 'field');
                break;
            case JackTokenizer::STATIC:
                $this->xmlWriter->writeTag('keyword', 'static');
                break;
        }

        $this->tokenizer->advance();
        $this->compileType();

        $this->tokenizer->advance();
        $this->compileIdentifier();

        while (true) {
            $this->tokenizer->advance();
            $this->compileSymbol();

            if ($this->tokenizer->symbol() === ';') break;

            $this->tokenizer->advance();
            $this->compileIdentifier();
        }

        $this->xmlWriter->writeClosingTag('classVarDec');
    }

    private function compileVarDec(): void
    {
        $this->xmlWriter->writeOpeningTag('varDec');

        $this->xmlWriter->writeTag('keyword', 'var');

        $this->tokenizer->advance();
        $this->compileType();

        $this->tokenizer->advance();
        $this->compileIdentifier();

        while (true) {
            $this->tokenizer->advance();
            $this->compileSymbol();

            if ($this->tokenizer->symbol() === ';') break;

            $this->tokenizer->advance();
            $this->compileIdentifier();
        }

        $this->xmlWriter->writeClosingTag('varDec');
    }

    private function compileStatements(): void
    {
        $this->xmlWriter->writeOpeningTag('statements');

        while (true) {
            if ($this->tokenizer->tokenType() !== JackTokenizer::KEYWORD) {
                $this->tokenizer->back();
                break;
            }

            $this->compileStatement();
            $this->tokenizer->advance();
        }

        $this->xmlWriter->writeClosingTag('statements');
    }

    private function compileStatement(): void
    {
        switch ($this->tokenizer->keyword()) {
            case JackTokenizer::LET:
                $this->compileLetStatement();
                break;
            case JackTokenizer::IF:
                $this->compileIfStatement();
                break;
            case JackTokenizer::WHILE:
                $this->compileWhileStatement();
                break;
            case JackTokenizer::DO:
                $this->compileDoStatement();
                break;
            case JackTokenizer::RETURN:
                $this->compileReturnStatement();
                break;
        }
    }

    private function compileLetStatement(): void
    {
        $this->xmlWriter->writeOpeningTag('letStatement');

        $this->xmlWriter->writeTag('keyword', 'let');

        $this->tokenizer->advance();
        $this->compileIdentifier();

        $this->tokenizer->advance();
        if ($this->tokenizer->symbol() !== '=') {
            $this->compileSymbol();

            $this->tokenizer->advance();
            $this->compileExpression();

            $this->tokenizer->advance();
            $this->compileSymbol();

            $this->tokenizer->advance();
        }

        $this->compileSymbol();

        $this->tokenizer->advance();
        $this->compileExpression();

        $this->tokenizer->advance();
        $this->compileSymbol();

        $this->xmlWriter->writeClosingTag('letStatement');
    }

    private function compileIfStatement(): void
    {
        $this->xmlWriter->writeOpeningTag('ifStatement');

        $this->xmlWriter->writeTag('keyword', 'if');

        $this->tokenizer->advance();
        $this->compileSymbol();

        $this->tokenizer->advance();
        $this->compileExpression();

        $this->tokenizer->advance();
        $this->compileSymbol();

        $this->tokenizer->advance();
        $this->compileSymbol();

        $this->tokenizer->advance();
        $this->compileStatements();

        $this->tokenizer->advance();
        $this->compileSymbol();

        $this->tokenizer->advance();
        if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD && $this->tokenizer->keyword() === JackTokenizer::ELSE) {
            $this->xmlWriter->writeTag('keyword', 'else');

            $this->tokenizer->advance();
            $this->compileSymbol();

            $this->tokenizer->advance();
            $this->compileStatements();

            $this->tokenizer->advance();
            $this->compileSymbol();
        } else {
            $this->tokenizer->back();
        }

        $this->xmlWriter->writeClosingTag('ifStatement');
    }

    private function compileWhileStatement(): void
    {
        $this->xmlWriter->writeOpeningTag('whileStatement');

        $this->xmlWriter->writeTag('keyword', 'while');

        $this->tokenizer->advance();
        $this->compileSymbol();

        $this->tokenizer->advance();
        $this->compileExpression();

        $this->tokenizer->advance();
        $this->compileSymbol();

        $this->tokenizer->advance();
        $this->compileSymbol();

        $this->tokenizer->advance();
        $this->compileStatements();

        $this->tokenizer->advance();
        $this->compileSymbol();

        $this->xmlWriter->writeClosingTag('whileStatement');
    }

    private function compileDoStatement(): void
    {
        $this->xmlWriter->writeOpeningTag('doStatement');

        $this->xmlWriter->writeTag('keyword', 'do');

        $this->tokenizer->advance();
        $this->compileSubroutineCall();

        $this->tokenizer->advance();
        $this->compileSymbol();

        $this->xmlWriter->writeClosingTag('doStatement');
    }

    private function compileSubroutineCall(): void
    {
        $this->compileIdentifier();

        $this->tokenizer->advance();
        $this->compileSymbol();

        if ($this->tokenizer->symbol() === '.') {
            $this->tokenizer->advance();
            $this->compileIdentifier();

            $this->tokenizer->advance();
            $this->compileSymbol();
        }

        $this->tokenizer->advance();
        $this->compileExpressionList();

        $this->tokenizer->advance();
        $this->compileSymbol();
    }

    private function compileReturnStatement(): void
    {
        $this->xmlWriter->writeOpeningTag('returnStatement');

        $this->xmlWriter->writeTag('keyword', 'return');

        $this->tokenizer->advance();
        if ($this->tokenizer->tokenType() !== JackTokenizer::SYMBOL) {
            $this->compileExpression();

            $this->tokenizer->advance();
        }

        $this->compileSymbol();

        $this->xmlWriter->writeClosingTag('returnStatement');
    }

    private function compileExpression(): void
    {
        $operators = ['+', '-', '*', '/', '&', '|', '<', '>', '='];

        $this->xmlWriter->writeOpeningTag('expression');

        $this->compileTerm();

        $this->tokenizer->advance();
        if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL && in_array($this->tokenizer->symbol(), $operators)) {
            $this->compileSymbol();

            $this->tokenizer->advance();
            $this->compileTerm();
        } else {
            $this->tokenizer->back();
        }

        $this->xmlWriter->writeClosingTag('expression');
    }

    private function compileTerm(): void
    {
        $this->xmlWriter->writeOpeningTag('term');

        switch ($this->tokenizer->tokenType()) {
            case JackTokenizer::INT_CONST:
                $this->compileIntConst();
                break;
            case JackTokenizer::STRING_CONST:
                $this->compileStringConst();
                break;
            case JackTokenizer::KEYWORD:
                $this->compileKeywordConst();
                break;
            case JackTokenizer::SYMBOL:
                if ($this->tokenizer->symbol() === '(') {
                    $this->compileSymbol();

                    $this->tokenizer->advance();
                    $this->compileExpression();

                    $this->tokenizer->advance();
                    $this->compileSymbol();
                } else if ($this->tokenizer->symbol() === '-' || $this->tokenizer->symbol() === '~') {
                    $this->compileSymbol();

                    $this->tokenizer->advance();
                    $this->compileTerm();
                }
                break;
            case JackTokenizer::IDENTIFIER:
                $this->tokenizer->advance();
                if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL && $this->tokenizer->symbol() === '[') {
                    // array access
                    $this->tokenizer->back();

                    $this->compileIdentifier();

                    $this->tokenizer->advance();
                    $this->compileSymbol();

                    $this->tokenizer->advance();
                    $this->compileExpression();

                    $this->tokenizer->advance();
                    $this->compileSymbol();
                } else if ($this->tokenizer->tokenType() === JackTokenizer::SYMBOL && $this->tokenizer->symbol() === '.') {
                    // subroutine call
                    $this->tokenizer->back();
                    $this->compileSubroutineCall();
                } else {
                    // just a var name
                    $this->tokenizer->back();
                    $this->compileIdentifier();
                }
                break;
        }

        $this->xmlWriter->writeClosingTag('term');
    }

    private function compileType(): void
    {
        $map = [
            JackTokenizer::INT => 'int',
            JackTokenizer::CHAR => 'char',
            JackTokenizer::BOOLEAN => 'boolean',
        ];

        if ($this->tokenizer->tokenType() === JackTokenizer::KEYWORD && array_key_exists($this->tokenizer->keyword(), $map)) {
            $this->xmlWriter->writeTag('keyword', $map[$this->tokenizer->keyword()]);
        } else {
            $this->compileIdentifier();
        }
    }

    private function compileSymbol(): void
    {
        $map = [
            '+' => '+',
            '-' => '-',
            '*' => '*',
            '/' => '/',
            '&' => '&',
            '|' => '|',
            '<' => '&lt;',
            '>' => '&gt;',
            '=' => '=',
        ];

        $symbol = $this->tokenizer->symbol();
        if (array_key_exists($symbol, $map)) {
            $symbol = $map[$symbol];
        }

        $this->xmlWriter->writeTag('symbol', $symbol);
    }

    private function compileIdentifier(): void
    {
        $this->xmlWriter->writeTag('identifier', $this->tokenizer->identifier());
    }

    private function compileIntConst(): void
    {
        $this->xmlWriter->writeTag('integerConstant', $this->tokenizer->intVal());
    }

    private function compileStringConst(): void
    {
        $this->xmlWriter->writeTag('stringConstant', $this->tokenizer->stringVal());
    }

    private function compileKeywordConst(): void
    {
        $map = [
            JackTokenizer::TRUE => 'true',
            JackTokenizer::FALSE => 'false',
            JackTokenizer::NULL => 'null',
            JackTokenizer::THIS => 'this',
        ];

        $this->xmlWriter->writeTag('keyword', $map[$this->tokenizer->keyword()]);
    }
}
