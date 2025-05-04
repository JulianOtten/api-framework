<?php

namespace App\Database\Helpers;

use App\Database\QueryBuilder\Interfaces\SelectQueryInterface;

class Condition
{
    protected string $column;
    protected string $operator;
    protected mixed $value;
    protected bool $bind;

    public function __construct(string $column, string $operator, mixed $value, bool $bind = true)
    {
        $this->column = $column;
        $this->operator = $operator;
        $this->value = $value;
        $this->bind = $bind;

        if ($value instanceof SelectQueryInterface) {
            $this->value->isSubQuery();
        }
    }

    public function get()
    {
        $bind = ($this->bind ? '?' : $this->value);
        
        if (is_array($this->value)) {
            $bind = [
                '(',
                implode(', ', array_fill(0, count($this->value), '?')),
                ')',
            ];   
            
            $bind = implode(" ", $bind);
        }

        if ($this->value instanceof SelectQueryInterface) {
            $bind = $this->value->build();
        }
        
        return implode(" ", [
            $this->column,
            $this->operator,
            $bind,
        ]);
    }

    public function getValue(): mixed
    {
        if ($this->value instanceof SelectQueryInterface) {
            return $this->value->getBinds();
        }
        return $this->value;
    }

    public function getBind(): bool
    {
        return $this->bind;
    }
}
