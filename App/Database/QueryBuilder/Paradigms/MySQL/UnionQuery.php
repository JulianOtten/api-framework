<?php

namespace App\Database\QueryBuilder\Paradigms\MySQL;

use App\Database\QueryBuilder\Abstraction\AbstractQuery;
use App\Database\QueryBuilder\Interfaces\CreateQueryInterface;
use App\Database\QueryBuilder\Interfaces\SelectQueryInterface;
use App\Database\QueryBuilder\Interfaces\SubqueryTraitInterface;
use App\Database\QueryBuilder\Interfaces\UnionQueryInterface;
use App\Database\QueryBuilder\Traits\LimitTrait;
use App\Database\QueryBuilder\Traits\OrderByTrait;
use App\Database\QueryBuilder\Traits\SubqueryTrait;

class UnionQuery extends AbstractQuery implements UnionQueryInterface
{
    use LimitTrait;
    use OrderByTrait;
    use SubqueryTrait;

    protected array $queries = [];

    public function __construct(SubqueryTraitInterface ...$queries)
    {
        $this->queries = $queries;

        foreach ($queries as $query) {
            $this->setSubQueryBinds($query);
        }
    }

    public function build(): string
    {
        if ($this->valid === false) {
            return "";
        }

        $query = implode("{$this->getImplodeValue()} UNION {$this->getImplodeValue()}", $this->queries);

        $query = [
            $query,
            $this->getOrderBy(),
            $this->getLimit(),
        ];

        $query = array_filter($query);

        $query = implode(" {$this->getImplodeValue()}", $query);

        $query = $this->buildAsSubquery($query);

        return $query;
    }
}
