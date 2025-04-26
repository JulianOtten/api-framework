<?php

namespace App\Database\QueryBuilder\Interfaces;

interface SelectQueryInterface extends LimitTraitInterface, WhereTraitInterface, JoinTraitInterface, OrderByTraitInterface
{

    public function __construct(string|SelectQueryInterface ...$columns);
    public function columns(string|SelectQueryInterface ...$columns): SelectQueryInterface;

    public function as(string $alias): SelectQueryInterface;
    public function from(string $table): SelectQueryInterface;
}
