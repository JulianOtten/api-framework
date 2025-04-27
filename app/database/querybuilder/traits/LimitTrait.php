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
        $this->setBind('limit', $limit);
        
        if (!is_null($offset)) {
            $this->offset($offset);
        }
        
        return $this;
    }
    
    public function offset(int $offset): static
    {
        $this->setBind('limit', $offset);
        $this->offset = $offset;
        return $this;
    }

    protected function getLimit()
    {
        if (empty($this->limit)) {
            return "";
        }

        $limit = "LIMIT ?";

        if (!empty($this->offset)) {
            $limit .= ", ?";
        }

        return $limit;
    }
}
