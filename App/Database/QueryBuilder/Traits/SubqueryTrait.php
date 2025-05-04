<?php

namespace App\Database\QueryBuilder\Traits;

use App\Database\Helpers\Condition;
use App\Database\QueryBuilder\Interfaces\QueryInterface;

trait SubqueryTrait
{

    protected null|string $alias = null;
    protected bool $isSubQuery = false;

    /**
     * Turn this query into a subquery, and set the alias for this query
     *
     * @param string $as
     * @return static
     */
    public function as(string $alias): static
    {
        $this->alias = $this->sanitize($alias);
        $this->isSubQuery();
        return $this;
    }

    /**
     * Defines whether a query is a subquery, but does not require an alias to be set
     *
     * @return static
     */
    public function isSubQuery(): static
    {
        $this->isSubQuery = true;
        return $this;
    }

    /**
     * Same as `as()`, but with a clearer name.
     * `as()` can be used in a more readable syntax, while `alias()` is semantically more correct
     *
     * @param string $alias
     * @return static
     */
    public function alias(string $alias): static
    {
        return $this->as($alias);
    }

    /**
     * Turns a query into a sub query
     * If no sub query logic is set, return the input string
     *
     * @param string $query
     * @return string
     */
    protected function buildAsSubquery(string $query): string
    {
        if ($this->isSubQuery) {
            $query = "(" . $query . ")";
        }

        if ($this->alias !== null) {
            $query = $query . " as " . $this->alias;
        }

        return $query;
    }
}
