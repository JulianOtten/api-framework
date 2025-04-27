<?php

namespace App\Database\QueryBuilder\Abstraction;

use InvalidArgumentException;
use Stringable;

abstract class AbstractQuery implements Stringable
{
    protected $query = null;

    /**
     * Can be overwritten to join query segments with additional information.
     * Leave blank for query with 0 formatting.
     * 
     * @example string "\\n" gives queries newlines between segments, making them more readable
     *
     * @var string
     */
    protected $implodeValue = "\n";

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
        return array_reduce($this->binds, function ($acc, $arr) {
            return [...$acc, ...$arr];
        }, []);
    }

    protected function reset()
    {
        $this->query = null;
        $this->binds = array_map(function ($el) {
            return [];
        }, $this->binds);
    }

    protected function getImplodeValue(): string
    {
        return $this->implodeValue;
    }

    public function __toString(): string
    {
        return $this->build();
    }
}
