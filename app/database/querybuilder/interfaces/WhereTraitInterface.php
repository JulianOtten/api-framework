<?php

namespace App\Database\QueryBuilder\Interfaces;

interface WhereTraitInterface
{
    protected $wheres = [];

    public function where(string $column, string $operator, string|SelectQueryInterface $value): SelectQueryInterface;
}
