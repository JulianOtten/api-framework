<?php

namespace App\Database\QueryBuilder;

use App\Database\QueryBuilder\Paradigms\MySQL\QueryBuilder;

class QueryBuilderFactory
{
    public static function fromConnection()
    {
        return new QueryBuilder();
    }
}
