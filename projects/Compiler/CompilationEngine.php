<?php

require_once __DIR__ . '/JackTokenizer.php';
require_once __DIR__ . '/XmlStream.php';

class CompilationEngine
{
    private $tokenizer;
    private $outputFile;
    private $xmlWriter;

    private $modules = [];

    public function __construct(JackTokenizer $tokenizer, $outputFile)
    {
        $this->tokenizer = $tokenizer;
        $this->outputFile = $outputFile;
        $this->xmlWriter = new XmlStream($this->outputFile);
    }

    private function defer(string $module): void
    {
        if (!array_key_exists($module, $this->modules)) {
            $compilerName = $module . 'Compiler';
            require_once __DIR__ . "/CompilationModules/{$compilerName}.php";
            $this->modules[$module] = new $compilerName($this->tokenizer, $this->xmlWriter, $this);
        }

        $this->modules[$module]->compile();
    }

    public function compileClass(): void
    {
        $this->defer('Class');
    }

    public function compileClassVarDec(): void
    {
        $this->defer('ClassVarDec');
    }

    public function compileSubroutine(): void
    {
        $this->defer('Subroutine');
    }

    public function compileParameterList(): void
    {
        $this->defer('ParameterList');
    }

    public function compileVarDec(): void
    {
        $this->defer('VarDec');
    }

    public function compileStatements(): void
    {
        $this->defer('Statements');
    }

    public function compileDo(): void
    {
        $this->defer('Do');
    }

    public function compileLet(): void
    {
        $this->defer('Let');
    }

    public function compileWhile(): void
    {
        $this->defer('While');
    }

    public function compileReturn(): void
    {
        $this->defer('Return');
    }

    public function compileIf(): void
    {
        $this->defer('If');
    }

    public function compileExpression(): void
    {
        $this->defer('Expression');
    }

    public function compileTerm(): void
    {
        $this->defer('Term');
    }

    public function compileExpressionList(): void
    {
        $this->defer('ExpressionList');
    }

    public function compileSubroutineBody(): void
    {
        $this->defer('SubroutineBody');
    }

    public function compileStatement(): void
    {
        $this->defer('Statement');
    }

    public function compileSubroutineCall(): void
    {
        $this->defer('SubroutineCall');
    }

    public function compileType(): void
    {
        $this->defer('Type');
    }

    public function compileSymbol(): void
    {
        $this->defer('Symbol');
    }

    public function compileIdentifier(): void
    {
        $this->defer('Identifier');
    }

    public function compileIntConst(): void
    {
        $this->defer('IntConst');
    }

    public function compileStringConst(): void
    {
        $this->defer('StringConst');
    }

    public function compileKeywordConst(): void
    {
        $this->defer('KeywordConst');
    }
}
