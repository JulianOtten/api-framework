<?php

namespace App\Database\QueryBuilder\Interfaces;

interface AbstractQueryInterface
{
    public function build(): string;
    public function getBinds(): array;
    public function reset(): static;

    /**
     * Purely used for combining parameters, but has to be public for this to work
     *
     * @return array
     */
    public function getRawBinds(): array;
}
