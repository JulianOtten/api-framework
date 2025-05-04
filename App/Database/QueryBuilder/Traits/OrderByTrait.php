<?php

namespace App\Database\QueryBuilder\Traits;

use App\Database\QueryBuilder\Interfaces\SelectQueryInterface;
use App\Database\QueryBuilder\Interfaces\SubqueryTraitInterface;
use InvalidArgumentException;

trait OrderByTrait
{
    protected array $orderBy = [];

    public function orderBy(string|SubqueryTraitInterface $column, string $direction = "ASC"): static
    {
        $direction = strtoupper($direction);
        if (!in_array($direction, ['ASC', 'DESC'])) {
            throw new InvalidArgumentException("Argumnent direction must me 'ASC' or 'DESC'");
        }

        $isSubQuery = false;
        if ($column instanceof SubqueryTraitInterface) {
            $this->setSubQueryBinds($column);
            $isSubQuery = true;
        }

        $column = $this->sanitize($column);

        if ($isSubQuery) {
            $column = "({$column})";
        }

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
