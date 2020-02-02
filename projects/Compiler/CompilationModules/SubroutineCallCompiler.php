<?php

require_once __DIR__ . '/CompilationModule.php';

class SubroutineCallCompiler extends CompilationModule
{
    // method()|obj.method()
    public function compile(): void
    {
        // obj|method
        $identifier = $this->tokenizer->identifier();

        $this->tokenizer->advance();
        $symbol = $this->tokenizer->symbol();
        $this->tokenizer->back();

        if ($symbol !== '.') {
            $subroutineName = $identifier;
        } else {
            $this->tokenizer->advance();
            // .
            $this->tokenizer->advance();
            // method

            $obj = $identifier;
            $method = $this->tokenizer->identifier();

            $subroutineName = "{$obj}.{$method}";
        }

        $this->tokenizer->advance();
        // (

        $this->tokenizer->advance();
        $nArgs = $this->engine->compileExpressionList();

        $this->vmWriter->writeCall($subroutineName, $nArgs);

        $this->tokenizer->advance();
        // )

        $this->tokenizer->advance();
    }
}
