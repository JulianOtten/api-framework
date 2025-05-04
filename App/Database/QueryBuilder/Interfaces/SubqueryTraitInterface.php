<?php

namespace App\Database\QueryBuilder\Interfaces;

use App\Database\Helpers\Condition;

interface SubqueryTraitInterface extends AbstractQueryInterface
{
    public function as(string $alias): static;
    public function isSubQuery(): static;
    public function alias(string $alias): static;
}
