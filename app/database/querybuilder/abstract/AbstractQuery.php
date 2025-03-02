<?php

namespace App\Database\QueryBuilder\Abstract;

use InvalidArgumentException;
use Stringable;

abstract class AbstractQuery implements Stringable
{

    protected $query = null;

    protected $binds = [
        "wheres" => [],
        "limit" => [],
    ];

    abstract protected function build(): string;

    protected function setBind($type, $key, $value)
    {
        if (!isset($this->binds[$type])) {
            throw new InvalidArgumentException("$type is not a valid bind option");
        }

        $this->binds[$type][$key] = $value;
    }

    protected function getBinds()
    {
        return array_reduce($this->binds, function($acc, $arr) {
            return [...$acc, ...$arr];
        }, []);
    }

    protected function reset()
    {
        $this->query = null;
        $this->binds = array_map(function($el) {
            return [];
        }, $this->binds);
    }

    public function __toString(): string
    {
        return $this->build();
    }

}