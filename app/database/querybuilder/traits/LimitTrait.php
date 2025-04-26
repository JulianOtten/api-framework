<?php

namespace App\Database\QueryBuilder\Traits;

use App\Database\QueryBuilder\Interfaces\QueryInterface;

trait LimitTrait
{

    protected int $limit;
    protected int $offset;

    public function limit(int $limit, $offset = null): static
    {
        $this->limit = $limit;

        if (!is_null($offset)) {
            $this->offset = $offset;
        }

        return $this;
    }

    public function offset(int $offset): static
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
