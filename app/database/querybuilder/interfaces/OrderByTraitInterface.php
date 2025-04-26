<?php

namespace App\Database\QueryBuilder\Interfaces;

interface OrderByTraitInterface
{
    public function orderBy(string|SelectQueryInterface $column, string $direction = "ASC");
}
