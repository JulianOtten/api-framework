<?php

namespace App\Database\QueryBuilder\Interfaces;

interface LimitTraitInterface
{

    protected int $limit = null;
    protected int $offset = null;

    public function limit(int $limit): SelectQueryInterface;
    public function offset(int $offset): SelectQueryInterface;
}
