<?php

// The following identifiers may appear in the symbol table:
// Static: class-level scope.
// Field: class-level scope.
// Argument: subroutine-level scope.
// Var: subroutine-level scope.

// Any identifier not found in the symbol table can be assumed to be the name of a subroutine or class. These do not need to be kept in the symbol table since they can be inferred from the Jack grammar.

// Columns:
// Name (variable name)
// Type (variable type)
// Kind (static, field, argument, var, ClassName)
// Index (unique to each kind)

class SymbolTable
{
    private $className;
    private $classTable = [];
    private $subroutineTable = [];

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function startSubroutine(): void
    {
        $this->subroutineTable = [];
    }

    private function &getTable(string $kind): array
    {
        if ($kind === 'static' || $kind === 'field') {
            return $this->classTable;
        } else {
            return $this->subroutineTable;
        }
    }

    public function define(string $name, string $type, string $kind): void
    {
        $table = &$this->getTable($kind);

        $index = $this->varCount($kind);

        $table[$name] = [
            'type' => $type,
            'kind' => $kind,
            'index' => $index,
        ];
    }

    public function has(string $name): bool
    {
        return (array_key_exists($name, $this->subroutineTable) || array_key_exists($name, $this->classTable));
    }

    public function varCount(string $kind): int
    {
        $table = &$this->getTable($kind);

        $count = 0;
        foreach ($table as $entry) {
            if ($entry['kind'] === $kind) $count++;
        }

        return $count;
    }

    private function getAttr(string $name, string $attr): ?string
    {
        if (array_key_exists($name, $this->subroutineTable)) {
            return $this->subroutineTable[$name][$attr];
        }

        if (array_key_exists($name, $this->classTable)) {
            return $this->classTable[$name][$attr];
        }

        return null;
    }

    public function kindOf(string $name): ?string
    {
        return $this->getAttr($name, 'kind');
    }

    public function typeOf(string $name): ?string
    {
        return $this->getAttr($name, 'type');
    }

    public function indexOf(string $name): ?string
    {
        return $this->getAttr($name, 'index');
    }
}
