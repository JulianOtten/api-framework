<?php

namespace App\Database\QueryBuilder\Traits;

use App\Database\Helpers\Condition;
use App\Database\Helpers\Join;
use App\Database\QueryBuilder\Interfaces\SelectQueryInterface;
use App\Database\QueryBuilder\Interfaces\SubqueryTraitInterface;

trait JoinTrait
{
    protected array $joins = [];

    public function join(string|SubqueryTraitInterface $table, Condition ...$conditions)
    {
        return $this->setJoin('JOIN', $table, ...$conditions);
    }

    public function leftJoin(string|SubqueryTraitInterface $table, Condition ...$conditions)
    {
        return $this->setJoin('LEFT JOIN', $table, ...$conditions);
    }

    public function rightJoin(string|SubqueryTraitInterface $table, Condition ...$conditions)
    {
        return $this->setJoin('RIGHT JOIN', $table, ...$conditions);
    }

    public function innerJoin(string|SubqueryTraitInterface $table, Condition ...$conditions)
    {
        return $this->setJoin('INNER JOIN', $table, ...$conditions);
    }

    public function outerJoin(string|SubqueryTraitInterface $table, Condition ...$conditions)
    {
        return $this->setJoin('OUTER JOIN', $table, ...$conditions);
    }

    protected function setJoin(string $joinType, string|SubqueryTraitInterface $table, Condition ...$conditions)
    {
        if ($table instanceof SubqueryTraitInterface) {
            $this->setSubQueryBinds($table);
        }

        $table = $this->sanitize($table);

        $join = new Join($joinType, $table);

        foreach ($conditions as $condition) {
            $join->setConditions($condition);

            if ($condition->getBind()) {
                $this->setBind('join', $condition->getValue());
            }
        }

        $this->joins[] = $join;

        return $this;
    }

    protected function getJoins(): string
    {
        $joins = [];

        foreach ($this->joins as $join) {
            $joins[] = "{$join->getJoinType()} {$join->getTable()} ON {$join->getConditionsString()}";
        }

        return implode(" {$this->getImplodeValue()}", $joins);
    }
}
