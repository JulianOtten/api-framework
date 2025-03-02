<?php

namespace App\Database\QueryBuilder\Traits;

use App\Database\QueryBuilder\Interfaces\SelectQueryInterface;

trait JoinTrait
{

    protected array $joins;

    public function join(string|SelectQueryInterface $table, string $on)
    {
        $this->joins[$table] = $on;
    }
}
