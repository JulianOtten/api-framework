<?php

namespace App\Database\Helpers;

class Join
{
    public string $joinType = "JOIN";
    public string $table = "";

    /**
     * Conditions
     *
     * @var Condition[]
     */
    public array $conditions = [];

    public function __construct(string $joinType, string $table)
    {
        $this->joinType = $joinType;
        $this->table = $table;
    }

    public function setConditions(Condition ...$conditions): void
    {
        $this->conditions = [...$this->conditions, ...$conditions];
    }

    public function getJoinType(): string
    {
        return $this->joinType;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function getConditionsString(string $implodeValue = "AND"): string
    {
        $conditions = array_map(function (Condition $condition) {
            return $condition->get();
        }, $this->conditions);

        return implode(sprintf(" %s ", trim($implodeValue)), $conditions);
    }
}
