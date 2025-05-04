<?php

namespace App\Database\QueryBuilder\Paradigms\MySQL;

use App\Database\QueryBuilder\Abstraction\AbstractQuery;
use App\Database\QueryBuilder\Interfaces\SelectQueryInterface;
use App\Database\QueryBuilder\Traits\GroupByTrait;
use App\Database\QueryBuilder\Traits\HavingTrait;
use App\Database\QueryBuilder\Traits\JoinTrait;
use App\Database\QueryBuilder\Traits\LimitTrait;
use App\Database\QueryBuilder\Traits\OrderByTrait;
use App\Database\QueryBuilder\Traits\SubqueryTrait;
use App\Database\QueryBuilder\Interfaces\SubqueryTraitInterface;
use App\Database\QueryBuilder\Traits\WhereTrait;

class SelectQuery extends AbstractQuery implements SelectQueryInterface
{
    use WhereTrait;
    use HavingTrait;
    use LimitTrait;
    use JoinTrait;
    use OrderByTrait;
    use GroupByTrait;
    use SubqueryTrait;

    protected string $table;
    protected null|string $alias = null;
    protected bool $isSubQuery = false;
    protected array $columns = [];

    public function __construct(string|SubqueryTraitInterface ...$columns)
    {
        if (count($columns) == 0) {
            $columns = ['*'];
        }

        $this->select(...$columns);
    }
    public function select(string|SubqueryTraitInterface ...$columns): static
    {
        foreach ($columns as $column) {
            if ($column instanceof SubqueryTraitInterface) {
                $this->setSubQueryBinds($column);
            }
        }

        $columns = array_map(fn($el) => $this->sanitize($el), $columns);

        $this->columns = [...$this->columns, ...$columns];
        return $this;
    }

    public function from(string $table): static
    {
        $this->table = $this->sanitize($table);

        return $this;
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
            $this->getHavings(),
            $this->getOrderBy(),
            $this->getLimit(),
        ];

        $query = array_filter($query);

        $query = implode(" {$this->getImplodeValue()}", $query);

        $query = $this->buildAsSubquery($query);

        return $query;
    }
}
