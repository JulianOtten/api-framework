<?php

namespace App\Database\QueryBuilder\Interfaces;

interface SelectQueryInterface extends
    LimitTraitInterface,
    WhereTraitInterface,
    HavingTraitInterface,
    JoinTraitInterface,
    OrderByTraitInterface,
    GroupByTraitInterface,
    AbstractQueryInterface
{
    public function __construct(string|SelectQueryInterface ...$columns);
    public function select(string|SelectQueryInterface ...$columns): SelectQueryInterface;

    public function as(string $alias): SelectQueryInterface;
    public function isSubQuery(): SelectQueryInterface;
    public function from(string $table): SelectQueryInterface;
}
