<?php

namespace App\Database\QueryBuilder\Traits;

use App\Database\QueryBuilder\Interfaces\SelectQueryInterface;
use InvalidArgumentException;

trait OrderByTrait
{
    protected array $orderBy = [];

    public function orderBy(string|SelectQueryInterface $column, string $direction = "ASC"): static
    {
        $direction = strtoupper($direction);
        if (!in_array($direction, ['ASC', 'DESC'])) {
            throw new InvalidArgumentException("Argumnent direction must me 'ASC' or 'DESC'");
        }

        $column = $this->sanitize($column);

        $this->orderBy[] = "{$column} {$direction}";
        return $this;
    }

    public function getOrderBy(): string
    {
        if (empty($this->orderBy)) {
            return "";
        }
        return sprintf('ORDER BY %s', implode(", {$this->getImplodeValue()}", $this->orderBy));
    }
}
