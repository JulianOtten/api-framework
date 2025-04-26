<?php

namespace App\Database\Helpers;

class Condition
{
    protected string $column;
    protected string $operator;
    protected mixed $value;

    public function __construct(string $column, string $operator, mixed $value)
    {
        $this->column = $column;
        $this->operator = $operator;
        $this->value = $value;
    }

    public function get()
    {
        return implode(" ", [
            $this->column,
            $this->operator,
            $this->value
        ]);
    }
}
