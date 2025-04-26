<?php

namespace App\Database\QueryBuilder\Interfaces;

interface GroupByTraitInterface
{
    public function groupBy(string ...$columns): static;
}
