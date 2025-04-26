<?php

namespace App\Database\QueryBuilder\Traits;

use App\Database\QueryBuilder\Interfaces\QueryInterface;

trait LimitTrait
{

    protected int $limit;
    protected int $offset;

    public function limit(int $limit): QueryInterface
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): QueryInterface
    {
        $this->offset = $offset;
        return $this;
    }

    protected function getLimit()
    {
        if(empty($this->limit)) {
            return "";
        }

        $limit = "LIMIT " . $this->limit;

        if(!empty($this->offset)) {
            $limit .= ", " . $this->offset;
        }

        return $limit;
    }
}
