<?php

namespace App\Database\QueryBuilder\Interfaces;

interface QueryInterface
{
    public function __construct();

    public function select(): SelectQueryInterface;
    public function update(): UpdateQueryInterface;
    public function delete(): DeleteQueryInterface;
    public function create(): CreateQueryInterface;
}
