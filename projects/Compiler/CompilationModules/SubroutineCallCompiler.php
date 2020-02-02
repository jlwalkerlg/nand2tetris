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
            $nArgs = 1;
            $className = $this->symbolTable->getClassName();
            $subroutineName = $identifier;
            $this->vmWriter->writePush('pointer', 0);

            $this->tokenizer->advance(); // expressionList
            $nArgs += $this->engine->compileExpressionList(); // )

            $this->vmWriter->writeCall("{$className}.{$subroutineName}", $nArgs);
        } else {
            // .
            $this->tokenizer->advance(); // subroutineName
            $subroutineName = $this->tokenizer->identifier();

            $isMethod = $this->symbolTable->indexOf($identifier) !== null;

            if ($isMethod) {
                $className = $this->symbolTable->typeOf($identifier);
                $kind = $this->symbolTable->kindOf($identifier);
                $index = $this->symbolTable->indexOf($identifier);
                $this->vmWriter->writePush($this->getSegment($kind), $index);
                $nArgs = 1;
            } else {
                $className = $identifier;
                $nArgs = 0;
            }

            $this->tokenizer->advance(); // (
            $this->tokenizer->advance(); // expressionList
            $nArgs += $this->engine->compileExpressionList(); // )

            $this->vmWriter->writeCall("{$className}.{$subroutineName}", $nArgs);
        }
    }
}
