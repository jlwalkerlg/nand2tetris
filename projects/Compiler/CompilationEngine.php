<?php

require_once __DIR__ . '/JackTokenizer.php';
require_once __DIR__ . '/VMWriter.php';
require_once __DIR__ . '/SymbolTable.php';
require_once __DIR__ . '/XmlStream.php';

class CompilationEngine
{
    private $tokenizer;
    private $writer;
    private $symbolTable;
    private $xmlWriter;

    private $modules = [];

    public function __construct(JackTokenizer $tokenizer, VMWriter $writer, SymbolTable $symbolTable, XmlStream $xmlWriter)
    {
        $this->tokenizer = $tokenizer;
        $this->writer = $writer;
        $this->symbolTable = $symbolTable;
        $this->xmlWriter = $xmlWriter;
    }

    private function defer(string $module, ...$args): void
    {
        if (!array_key_exists($module, $this->modules)) {
            $compilerName = $module . 'Compiler';
            require_once __DIR__ . "/CompilationModules/{$compilerName}.php";
            $this->modules[$module] = new $compilerName($this->tokenizer, $this->writer, $this->symbolTable, $this->xmlWriter, $this);
        }

        $this->modules[$module]->compile(...$args);
    }

    public function compileClass(...$args): void
    {
        $this->defer('Class', ...$args);
    }

    public function compileClassVarDec(...$args): void
    {
        $this->defer('ClassVarDec', ...$args);
    }

    public function compileSubroutine(...$args): void
    {
        $this->defer('Subroutine', ...$args);
    }

    public function compileParameterList(...$args): void
    {
        $this->defer('ParameterList', ...$args);
    }

    public function compileVarDec(...$args): void
    {
        $this->defer('VarDec', ...$args);
    }

    public function compileStatements(...$args): void
    {
        $this->defer('Statements', ...$args);
    }

    public function compileDo(...$args): void
    {
        $this->defer('Do', ...$args);
    }

    public function compileLet(...$args): void
    {
        $this->defer('Let', ...$args);
    }

    public function compileWhile(...$args): void
    {
        $this->defer('While', ...$args);
    }

    public function compileReturn(...$args): void
    {
        $this->defer('Return', ...$args);
    }

    public function compileIf(...$args): void
    {
        $this->defer('If', ...$args);
    }

    public function compileExpression(...$args): void
    {
        $this->defer('Expression', ...$args);
    }

    public function compileTerm(...$args): void
    {
        $this->defer('Term', ...$args);
    }

    public function compileExpressionList(...$args): void
    {
        $this->defer('ExpressionList', ...$args);
    }

    public function compileSubroutineCall(...$args): void
    {
        $this->defer('SubroutineCall', ...$args);
    }

    public function compileType(...$args): void
    {
        $this->defer('Type', ...$args);
    }

    public function compileSymbol(...$args): void
    {
        $this->defer('Symbol', ...$args);
    }

    public function compileIdentifier(...$args): void
    {
        $this->defer('Identifier', ...$args);
    }

    public function compileIntConst(...$args): void
    {
        $this->defer('IntConst', ...$args);
    }

    public function compileStringConst(...$args): void
    {
        $this->defer('StringConst', ...$args);
    }

    public function compileKeywordConst(...$args): void
    {
        $this->defer('KeywordConst', ...$args);
    }
}
