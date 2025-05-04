<?php

namespace App\Database\QueryBuilder\Interfaces;

use App\Database\Helpers\Condition;

interface JoinTraitInterface extends AbstractQueryInterface
{
    public function join(string|SubqueryTraitInterface $table, Condition ...$conditions);
    public function leftJoin(string|SubqueryTraitInterface $table, Condition ...$conditions);
    public function rightJoin(string|SubqueryTraitInterface $table, Condition ...$conditions);
    public function innerJoin(string|SubqueryTraitInterface $table, Condition ...$conditions);
    public function outerJoin(string|SubqueryTraitInterface $table, Condition ...$conditions);
}
