<?php

namespace App\Database\QueryBuilder\Traits;

use App\Database\QueryBuilder\Interfaces\SelectQueryInterface;

trait JoinTrait
{
    protected array $joins = [];

    public function join(string|SelectQueryInterface $table, string $on)
    {
        $this->joins[$table] = $on;
        return $this;
    }

    protected function getJoins(): string
    {
        $joins = [];

        foreach ($this->joins as $table => $on) {
            $joins[] = "JOIN {$table} ON {$on}";
        }

        return implode(' ', $joins);
    }
}
