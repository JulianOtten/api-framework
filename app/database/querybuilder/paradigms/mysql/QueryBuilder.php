<?php

namespace App\Database\QueryBuilder\Paradigms\MySQL;

use App\Database\QueryBuilder\Interfaces\CreateQueryInterface;
use App\Database\QueryBuilder\Interfaces\DeleteQueryInterface;
use App\Database\QueryBuilder\Interfaces\InsertQueryInterface;
use App\Database\QueryBuilder\Interfaces\QueryInterface;
use App\Database\QueryBuilder\Interfaces\SelectQueryInterface;
use App\Database\QueryBuilder\Interfaces\UpdateQueryInterface;

class QueryBuilder implements QueryInterface
{
    public function __construct()
    {
    }

    public function select(): SelectQueryInterface
    {
        return new SelectQuery();
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
