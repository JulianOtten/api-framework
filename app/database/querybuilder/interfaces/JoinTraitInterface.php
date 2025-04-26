<?php

namespace App\Database\QueryBuilder\Interfaces;

interface JoinTraitInterface
{
    public function join(string|SelectQueryInterface $table, string $on);
}
