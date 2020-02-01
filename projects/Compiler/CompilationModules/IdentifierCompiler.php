<?php

require_once __DIR__ . '/CompilationModule.php';

class IdentifierCompiler extends CompilationModule
{
    public function compile(?string $category = null, bool $isDeclaration = false): void
    {
        $identifier = $this->tokenizer->identifier();

        if ($category === 'class' || $category === 'subroutine') {
            $kind = null;
            $index = null;
        } else {
            $category = $kind = $this->symbolTable->kindOf($identifier);
            $index = $this->symbolTable->indexOf($identifier);
        }

        $this->writer->writeTag('identifier', $identifier, [
            'category' => $category,
            'isDeclaration' => $isDeclaration ? 'true' : 'false',
            'kind' => $kind,
            'index' => $index,
        ]);
    }
}
