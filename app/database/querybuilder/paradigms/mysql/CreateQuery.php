<?php

namespace App\Database\QueryBuilder\Paradigms\MySQL;

use App\Database\QueryBuilder\Abstract\AbstractQuery;
use App\Database\QueryBuilder\Interfaces\CreateQueryInterface;

class CreateQuery extends AbstractQuery implements CreateQueryInterface
{
    public function build(): string
    {
        return "";
    }
}
