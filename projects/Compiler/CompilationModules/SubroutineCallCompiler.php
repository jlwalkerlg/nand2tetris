<?php

require_once __DIR__ . '/CompilationModule.php';

// subroutineName (expressionList) | (className|varName).subroutineName(expressionList)

class SubroutineCallCompiler extends CompilationModule
{
    public function compile(): void
    {
        // subroutineName | (className|varName)
        $identifier = $this->tokenizer->identifier();

        $this->tokenizer->advance(); // (|.

        $symbol = $this->tokenizer->symbol();

        if ($symbol === '(') {
            $this->tokenizer->advance(); // expressionList
            $nArgs = $this->engine->compileExpressionList(); // )

            $this->vmWriter->writeCall($identifier, $nArgs);
        } else {
            // .
            $this->tokenizer->advance(); // subroutineName
            $subroutineName = $this->tokenizer->identifier();

            $this->tokenizer->advance(); // (
            $this->tokenizer->advance(); // expressionList
            $nArgs = $this->engine->compileExpressionList(); // )

            $this->vmWriter->writeCall("{$identifier}.{$subroutineName}", $nArgs);
        }
    }
}
