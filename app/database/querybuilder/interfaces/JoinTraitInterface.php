<?php

namespace App\Database\QueryBuilder\Interfaces;

use App\Database\Helpers\Condition;

interface JoinTraitInterface
{
    public function join(string|SelectQueryInterface $table, Condition ...$conditions);
    public function leftJoin(string|SelectQueryInterface $table, Condition ...$conditions);
    public function rightJoin(string|SelectQueryInterface $table, Condition ...$conditions);
    public function innerJoin(string|SelectQueryInterface $table, Condition ...$conditions);
    public function outerJoin(string|SelectQueryInterface $table, Condition ...$conditions);
}
