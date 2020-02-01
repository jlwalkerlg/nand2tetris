<?php

require_once __DIR__ . '/CompilationModule.php';

class SubroutineCallCompiler extends CompilationModule
{
    public function compile(): void
    {
        $identifier = $this->tokenizer->identifier();

        $this->tokenizer->advance();
        $symbol = $this->tokenizer->symbol();
        $this->tokenizer->back();

        // subroutine or class/variable
        if ($symbol !== '.') {
            $this->engine->compileIdentifier('subroutine');
        } else {
            $isClass = $this->symbolTable->has($identifier);
            $this->engine->compileIdentifier($isClass ? 'class' : null);
        }

        $this->tokenizer->advance();
        $this->engine->compileSymbol();

        if ($symbol === '.') {
            $this->tokenizer->advance();
            $this->engine->compileIdentifier('subroutine');

            $this->tokenizer->advance();
            $this->engine->compileSymbol();
        }

        $this->tokenizer->advance();
        $this->engine->compileExpressionList();

        $this->tokenizer->advance();
        $this->engine->compileSymbol();
    }
}
