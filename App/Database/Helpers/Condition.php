<?php

namespace App\Database\Helpers;

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
    }

    public function get()
    {
        return implode(" ", [
            $this->column,
            $this->operator,
            ($this->bind ? '?' : $this->value),
        ]);
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getBind(): bool
    {
        return $this->bind;
    }
}
