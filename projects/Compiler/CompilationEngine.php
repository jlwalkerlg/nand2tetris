<?php

require_once __DIR__ . '/JackTokenizer.php';
require_once __DIR__ . '/VMWriter.php';
require_once __DIR__ . '/SymbolTable.php';

class CompilationEngine
{
    private $tokenizer;
    private $writer;
    private $symbolTable;

    private $modules = [];

    public function __construct(JackTokenizer $tokenizer, VMWriter $writer, SymbolTable $symbolTable)
    {
        $this->tokenizer = $tokenizer;
        $this->writer = $writer;
        $this->symbolTable = $symbolTable;
    }

    private function defer(string $module, ...$args)
    {
        if (!array_key_exists($module, $this->modules)) {
            $compilerName = $module . 'Compiler';
            require_once __DIR__ . "/CompilationModules/{$compilerName}.php";
            $this->modules[$module] = new $compilerName($this->tokenizer, $this->writer, $this->symbolTable, $this);
        }

        return $this->modules[$module]->compile(...$args);
    }

    public function compileClass(...$args)
    {
        return $this->defer('Class', ...$args);
    }

    public function compileClassVarDec(...$args)
    {
        return $this->defer('ClassVarDec', ...$args);
    }

    public function compileSubroutine(...$args)
    {
        return $this->defer('Subroutine', ...$args);
    }

    public function compileParameterList(...$args)
    {
        return $this->defer('ParameterList', ...$args);
    }

    public function compileVarDec(...$args)
    {
        return $this->defer('VarDec', ...$args);
    }

    public function compileStatements(...$args)
    {
        return $this->defer('Statements', ...$args);
    }

    public function compileDo(...$args)
    {
        return $this->defer('Do', ...$args);
    }

    public function compileLet(...$args)
    {
        return $this->defer('Let', ...$args);
    }

    public function compileWhile(...$args)
    {
        return $this->defer('While', ...$args);
    }

    public function compileReturn(...$args)
    {
        return $this->defer('Return', ...$args);
    }

    public function compileIf(...$args)
    {
        return $this->defer('If', ...$args);
    }

    public function compileExpression(...$args)
    {
        return $this->defer('Expression', ...$args);
    }

    public function compileTerm(...$args)
    {
        return $this->defer('Term', ...$args);
    }

    public function compileExpressionList(...$args)
    {
        return $this->defer('ExpressionList', ...$args);
    }

    public function compileSubroutineCall(...$args)
    {
        return $this->defer('SubroutineCall', ...$args);
    }
}
