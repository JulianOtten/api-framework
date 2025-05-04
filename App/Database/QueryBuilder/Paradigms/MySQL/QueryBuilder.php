<?php

namespace App\Database\QueryBuilder\Paradigms\MySQL;

use App\Database\QueryBuilder\Interfaces\CreateQueryInterface;
use App\Database\QueryBuilder\Interfaces\DeleteQueryInterface;
use App\Database\QueryBuilder\Interfaces\InsertQueryInterface;
use App\Database\QueryBuilder\Interfaces\QueryInterface;
use App\Database\QueryBuilder\Interfaces\SelectQueryInterface;
use App\Database\QueryBuilder\Interfaces\SubqueryTraitInterface;
use App\Database\QueryBuilder\Interfaces\UnionQueryInterface;
use App\Database\QueryBuilder\Interfaces\UpdateQueryInterface;

class QueryBuilder implements QueryInterface
{
    public function __construct()
    {
    }

    public function select(string|SubqueryTraitInterface ...$columns): SelectQueryInterface
    {
        return new SelectQuery(...$columns);
    }

    public function union(SubqueryTraitInterface ...$queries): UnionQueryInterface
    {
        return new UnionQuery(...$queries);
    }

    public function update(): UpdateQueryInterface
    {
        return new UpdateQuery();
    }

    public function delete(): DeleteQueryInterface
    {
        return new DeleteQuery();
    }

    public function create(): CreateQueryInterface
    {
        return new CreateQuery();
    }

    public function insert(): InsertQueryInterface
    {
        return new InsertQuery();
    }
}
