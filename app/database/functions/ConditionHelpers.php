<?php

namespace App\Database\QueryBuilder\Functions;

use App\Database\Helpers\Condition;

function eq(string $column, mixed $value): Condition
{
    return new Condition($column, '=', $value);
}

function lt(string $column, mixed $value): Condition
{
    return new Condition($column, '<', $value);
}

function gt(string $column, mixed $value): Condition
{
    return new Condition($column, '>', $value);
}

function in(string $column, array $values): Condition
{
    return new Condition($column, 'IN', $values);
}

function isNull(string $column): Condition
{
    return new Condition($column, 'IS', null);
}

function isNotNull(string $column): Condition
{
    return new Condition($column, 'IS NOT', null);
}

function lte(string $column, mixed $value): Condition
{
    return new Condition($column, '<=', $value);
}

function gte(string $column, mixed $value): Condition
{
    return new Condition($column, '>=', $value);
}

function notEq(string $column, mixed $value): Condition
{
    return new Condition($column, '!=', $value);
}
