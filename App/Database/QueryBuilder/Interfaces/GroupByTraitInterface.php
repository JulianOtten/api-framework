<?php

namespace App\Database\QueryBuilder\Interfaces;

interface GroupByTraitInterface extends AbstractQueryInterface
{
    public function groupBy(string ...$columns): static;
}
