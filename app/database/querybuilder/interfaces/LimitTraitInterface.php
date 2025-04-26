<?php

namespace App\Database\QueryBuilder\Interfaces;

interface LimitTraitInterface
{
    public function limit(int $limit, $offset = null): static;
    public function offset(int $offset): static;
}
