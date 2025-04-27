<?php

namespace App\Database\QueryBuilder\Interfaces;

interface AbstractQueryInterface
{
    public function build(): string;
    public function getBinds(): string;
}
