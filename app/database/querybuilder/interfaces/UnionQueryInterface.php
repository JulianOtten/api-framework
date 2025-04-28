<?php

namespace App\Database\QueryBuilder\Interfaces;

interface UnionQueryInterface extends
    AbstractQueryInterface,
    OrderByTraitInterface,
    LimitTraitInterface
{
}
