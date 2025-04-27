<?php

namespace App\Database\QueryBuilder\Paradigms\MySQL;

use App\Database\QueryBuilder\Abstraction\AbstractQuery;
use App\Database\QueryBuilder\Interfaces\SelectQueryInterface;
use App\Database\QueryBuilder\Traits\GroupByTrait;
use App\Database\QueryBuilder\Traits\JoinTrait;
use App\Database\QueryBuilder\Traits\LimitTrait;
use App\Database\QueryBuilder\Traits\OrderByTrait;
use App\Database\QueryBuilder\Traits\WhereTrait;

class SelectQuery extends AbstractQuery implements SelectQueryInterface
{
    use WhereTrait;
    use LimitTrait;
    use JoinTrait;
    use OrderByTrait;
    use GroupByTrait;

    protected string $table;
    protected null|string $alias = null;
    protected array $columns = [];

    public function __construct(string|SelectQueryInterface ...$columns)
    {
        if (count($columns) == 0) {
            $columns = ['*'];
        }

        $this->columns(...$columns);
    }

    public function columns(string|SelectQueryInterface ...$columns): SelectQueryInterface
    {
        foreach($columns as $column) {

            if ($column instanceof SelectQueryInterface) {
                $this->setSubQueryBinds($column);
            }
        }

        $columns = array_map(fn($el) => $this->sanitize($el), $columns);

        $this->columns = $columns;
        return $this;
    }

    public function from(string $table): SelectQueryInterface
    {
        $this->table = $this->sanitize($table);

        return $this;
    }

    /**
     * Turn this query into a subquery, and set the alias for this query
     *
     * @param string $as
     * @return SelectQueryInterface
     */
    public function as(string $alias): SelectQueryInterface
    {
        $this->alias = $this->sanitize($alias);
        return $this;
    }

    /**
     * Same as `as()`, but with a clearer name.
     * `as()` can be used in a more readable syntax, while `alias()` is semantically more correct
     *
     * @param string $alias
     * @return SelectQueryInterface
     */
    public function alias(string $alias): SelectQueryInterface
    {
        return $this->as($alias);
    }

    public function build(): string
    {
        if ($this->valid === false) {
            return "";
        }

        $query = [
            "SELECT",
            implode(", ", $this->columns),
            "FROM",
            $this->table,
            $this->getJoins(),
            $this->getWheres(),
            $this->getGroupBy(),
            $this->getOrderBy(),
            $this->getLimit(),
        ];

        $query = array_filter($query);

        $query = implode(" {$this->getImplodeValue()}", $query);

        if ($this->alias !== null) {
            $query = "(" . $query . ") as " . $this->alias;
        }

        return $query;
    }
}
