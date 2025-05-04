<?php

namespace App\Database\QueryBuilder\Interfaces;

interface SelectQueryInterface extends
    LimitTraitInterface,
    WhereTraitInterface,
    HavingTraitInterface,
    JoinTraitInterface,
    OrderByTraitInterface,
    GroupByTraitInterface,
    AbstractQueryInterface,
    SubqueryTraitInterface
{
    public function __construct(string|SubqueryTraitInterface ...$columns);
    public function select(string|SubqueryTraitInterface ...$columns): static;

    public function from(string|SubqueryTraitInterface $table): static;
}
