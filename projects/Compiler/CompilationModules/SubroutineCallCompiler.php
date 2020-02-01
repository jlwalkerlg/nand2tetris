<?php

require_once __DIR__ . '/CompilationModule.php';

class SubroutineCallCompiler extends CompilationModule
{
    public function compile(): void
    {
        $this->engine->compileIdentifier();

        $this->tokenizer->advance();
        $this->engine->compileSymbol();

        if ($this->tokenizer->symbol() === '.') {
            $this->tokenizer->advance();
            $this->engine->compileIdentifier();

            $this->tokenizer->advance();
            $this->engine->compileSymbol();
        }

        $this->tokenizer->advance();
        $this->engine->compileExpressionList();

        $this->tokenizer->advance();
        $this->engine->compileSymbol();
    }
}
