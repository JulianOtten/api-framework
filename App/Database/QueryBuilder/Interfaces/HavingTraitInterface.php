<?php

namespace App\Database\QueryBuilder\Interfaces;

use App\Database\Helpers\Condition;

interface HavingTraitInterface extends AbstractQueryInterface
{
    public function having(Condition ...$conditions): static;
    // public function and(Condition ...$conditions): static;
}
