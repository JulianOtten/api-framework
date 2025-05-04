<?php

namespace App\Database\QueryBuilder\Interfaces;

interface OrderByTraitInterface extends AbstractQueryInterface
{
    public function orderBy(string|SubqueryTraitInterface $column, string $direction = "ASC");
}
