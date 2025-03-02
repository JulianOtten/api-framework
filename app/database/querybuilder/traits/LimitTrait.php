<?php

namespace App\Database\QueryBuilder\Traits;

trait LimitTrait
{

    protected int $limit;
    protected int $offset;

    public function limit(int $limit)
    {
        $this->limit = $limit;
    }

    public function offset(int $offset)
    {
        $this->offset = $offset;
    }
}
