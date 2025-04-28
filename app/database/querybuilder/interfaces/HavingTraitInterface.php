<?php

namespace App\Database\QueryBuilder\Interfaces;

use App\Database\Helpers\Condition;

interface HavingTraitInterface
{
    public function where(Condition ...$conditions): static;
    // public function and(Condition ...$conditions): static;
}
