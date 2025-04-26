<?php

namespace Tests\QueryBuilder;

use PHPUnit\Framework\TestCase;
use App\Database\QueryBuilder\Paradigms\Mysql\SelectQuery;

use function App\Database\QueryBuilder\Functions\eq;

class SelectQueryTest extends TestCase
{
    public function testSelectQueryBasic()
    {
        $query = (new SelectQuery('id', 'name'))->from('users');

        $expectedSql = 'SELECT id, name FROM users';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithWhereCondition()
    {
        $query = (new SelectQuery('id', 'name'))
              ->from('users')
              ->where(eq('id', 1));

        $expectedSql = 'SELECT id, name FROM users WHERE id = 1';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithMultipleConditions()
    {
        $query = (new SelectQuery('id', 'name'))
              ->from('users')
              ->where(eq('id', 1))
              ->and(eq('status', 'active'));

        $expectedSql = "SELECT id, name FROM users WHERE id = 1 AND status = 'active'";
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithOrderBy()
    {
        $query = (new SelectQuery('id', 'name'))
              ->from('users')
              ->orderBy('name', 'ASC');

        $expectedSql = 'SELECT id, name FROM users ORDER BY name ASC';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithLimit()
    {
        $query = (new SelectQuery('id', 'name'))
              ->from('users')
              ->limit(10);

        $expectedSql = 'SELECT id, name FROM users LIMIT 10';
        $this->assertEquals($expectedSql, $query->build());
    }
}
