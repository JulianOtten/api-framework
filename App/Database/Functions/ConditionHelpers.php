<?php

namespace App\Database\QueryBuilder\Functions;

use App\Database\Helpers\Condition;
use App\Database\QueryBuilder\Interfaces\SelectQueryInterface;

// function and(Condition ...$condition)
// {

// }

// function or(Condition ...$condition)
// {

// }

function eq(string $column, mixed $value): Condition
{
    return new Condition($column, '=', $value, true);
}

function ceq(string $column, string $valueColumn): Condition
{
    return new Condition($column, '=', $valueColumn, false);
}

function lt(string $column, mixed $value): Condition
{
    return new Condition($column, '<', $value, true);
}

function clt(string $column, string $valueColumn): Condition
{
    return new Condition($column, '<', $valueColumn, false);
}

function gt(string $column, mixed $value): Condition
{
    return new Condition($column, '>', $value, true);
}

function cgt(string $column, string $valueColumn): Condition
{
    return new Condition($column, '>', $valueColumn, false);
}

function in(string $column, array|SelectQueryInterface $values): Condition
{
    return new Condition($column, 'IN', $values, true);
}

function isNull(string $column): Condition
{
    return new Condition($column, 'IS', 'NULL', false);
}

function isNotNull(string $column): Condition
{
    return new Condition($column, 'IS NOT', 'NULL', false);
}

function lte(string $column, mixed $value): Condition
{
    return new Condition($column, '<=', $value, true);
}

function clte(string $column, string $valueColumn): Condition
{
    return new Condition($column, '<=', $valueColumn, false);
}

function gte(string $column, mixed $value): Condition
{
    return new Condition($column, '>=', $value, true);
}

function cgte(string $column, string $valueColumn): Condition
{
    return new Condition($column, '>=', $valueColumn, false);
}

function notEq(string $column, mixed $value): Condition
{
    return new Condition($column, '!=', $value, true);
}

function cnotEq(string $column, string $valueColumn): Condition
{
    return new Condition($column, '!=', $valueColumn, false);
}
