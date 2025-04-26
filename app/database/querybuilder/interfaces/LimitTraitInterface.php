<?php

namespace App\Database\QueryBuilder\Interfaces;

interface LimitTraitInterface
{
    public function limit(int $limit): QueryInterface;
    public function offset(int $offset): QueryInterface;
}
