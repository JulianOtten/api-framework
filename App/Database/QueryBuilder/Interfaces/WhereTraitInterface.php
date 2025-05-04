<?php

namespace App\Database\QueryBuilder\Interfaces;

use App\Database\Helpers\Condition;

interface WhereTraitInterface extends AbstractQueryInterface
{
    public function where(Condition ...$conditions): static;
    public function and(Condition ...$conditions): static;
}
