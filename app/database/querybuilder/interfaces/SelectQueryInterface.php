<?php

namespace App\Database\QueryBuilder\Interfaces;

interface SelectQueryInterface extends LimitTraitInterface, WhereTraitInterface, JoinTraitInterface
{

    protected string $table;
    protected array $columns = null;

    public function __construct(string|SelectQueryInterface ...$columns);
    public function columns(string|SelectQueryInterface ...$columns): SelectQueryInterface;

    public function from(string $table): SelectQueryInterface;
}
