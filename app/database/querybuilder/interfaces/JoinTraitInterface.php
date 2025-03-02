<?php

namespace App\Database\QueryBuilder\Interfaces;

interface JoinTraitInterface
{
    protected $joins = [];

    
    public function join(string|SelectQueryInterface $table, string $on);
}
