<?php

namespace Tests\QueryBuilder;

use PHPUnit\Framework\TestCase;
use App\Database\QueryBuilder\Paradigms\Mysql\SelectQuery;

use function App\Database\QueryBuilder\Functions\eq;
use function App\Database\QueryBuilder\Functions\ceq;
use function App\Database\QueryBuilder\Functions\gt;
use function App\Database\QueryBuilder\Functions\lt;
use function App\Database\QueryBuilder\Functions\in;
use function App\Database\QueryBuilder\Functions\isNull;

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

        $expectedSql = 'SELECT id, name FROM users WHERE ( id = ? )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([1], $query->getBinds());
    }

    public function testSelectQueryWithMultipleConditions()
    {
        $query = (new SelectQuery('id', 'name'))
              ->from('users')
              ->where(eq('id', 1))
              ->and(eq('status', 'active'));

        $expectedSql = "SELECT id, name FROM users WHERE ( id = ? ) AND ( status = ? )";
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([
            1,
            'active'
        ], $query->getBinds());
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

        $expectedSql = 'SELECT id, name FROM users LIMIT ?';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([10], $query->getBinds());
    }

    public function testSelectQueryWithJoin()
    {
        $query = (new SelectQuery('users.id', 'users.name', 'orders.total'))
            ->from('users')
            ->join('orders', ceq('users.id', 'orders.user_id'));

        $expectedSql = 'SELECT users.id, users.name, orders.total FROM users JOIN orders ON users.id = orders.user_id';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithLeftJoin()
    {
        $query = (new SelectQuery('users.id', 'users.name', 'orders.total'))
            ->from('users')
            ->leftJoin('orders', ceq('users.id', 'orders.user_id'));

        $expectedSql = 'SELECT users.id, users.name, orders.total FROM users LEFT JOIN orders ON users.id = orders.user_id';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithGroupBy()
    {
        $query = (new SelectQuery('status', 'COUNT(*) as count'))
            ->from('users')
            ->groupBy('status');

        $expectedSql = 'SELECT status, COUNT(*) as count FROM users GROUP BY status';
        $this->assertEquals($expectedSql, $query->build());
    }

    // public function testSelectQueryWithHaving()
    // {
    //     $query = (new SelectQuery('status', 'COUNT(*) as count'))
    //         ->from('users')
    //         ->groupBy('status')
    //         ->having(gt('count', 10));

    //     $expectedSql = 'SELECT status, COUNT(*) as count FROM users GROUP BY status HAVING count > 10';
    //     $this->assertEquals($expectedSql, $query->build());
    // }

    // public function testSelectQueryWithNestedQuery()
    // {
    //     $subQuery = (new SelectQuery('id'))
    //         ->from('admins')
    //         ->where(eq('role', 'superadmin'));

    //     $query = (new SelectQuery('users.id', 'users.name'))
    //         ->from('users')
    //         ->where(in('users.id', $subQuery));

    //     $expectedSql = 'SELECT users.id, users.name FROM users WHERE users.id IN (SELECT id FROM admins WHERE role = "superadmin")';
    //     $this->assertEquals($expectedSql, $query->build());
    // }

    public function testSelectQueryWithMultipleJoins()
    {
        $query = (new SelectQuery('users.id', 'users.name', 'orders.total', 'products.name as product_name'))
            ->from('users')
            ->join('orders', ceq('users.id', 'orders.user_id'))
            ->join('products', ceq('orders.product_id', 'products.id'));

        $expectedSql = 'SELECT users.id, users.name, orders.total, products.name as product_name FROM users JOIN orders ON users.id = orders.user_id JOIN products ON orders.product_id = products.id';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithIsNullCondition()
    {
        $query = (new SelectQuery('id', 'name'))
            ->from('users')
            ->where(isNull('deleted_at'));

        $expectedSql = 'SELECT id, name FROM users WHERE ( deleted_at IS NULL )';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithOrderByAndLimit()
    {
        $query = (new SelectQuery('id', 'name'))
            ->from('users')
            ->orderBy('created_at', 'DESC')
            ->limit(5);

        $expectedSql = 'SELECT id, name FROM users ORDER BY created_at DESC LIMIT ?';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([5], $query->getBinds());
    }

    public function testSelectQueryWithOffset()
    {
        $query = (new SelectQuery('id', 'name'))
            ->from('users')
            ->orderBy('created_at', 'DESC')
            ->limit(5, 10);

        $expectedSql = 'SELECT id, name FROM users ORDER BY created_at DESC LIMIT ?, ?';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([5, 10], $query->getBinds());
    }

    public function testSelectQueryWithAlias()
    {
        $query = (new SelectQuery('id', 'name'))
            ->from('users')
            ->as('u');

        $expectedSql = '(SELECT id, name FROM users) as u';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithComplexConditions()
    {
        $query = (new SelectQuery('id', 'name'))
            ->from('users')
            ->where(eq('status', 'active'))
            ->and(gt('age', 18))
            ->and(lt('age', 60));

        $expectedSql = "SELECT id, name FROM users WHERE ( status = ? ) AND ( age > ? ) AND ( age < ? )";
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([
            'active',
            18,
            60, 
        ], $query->getBinds());
    }

    public function testSelectQueryWithOrCondition()
    {
        $query = (new SelectQuery('id', 'name'))
            ->from('users')
            ->where(eq('status', 'active'))
            ->and(gt('age', 18), lt('age', 60));

        $expectedSql = "SELECT id, name FROM users WHERE ( status = ? ) AND ( age > ? OR age < ? )";
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([
            'active',
            18,
            60, 
        ], $query->getBinds());
    }

    public function testSelectQueryWithAndOrLogic()
    {
        $query = (new SelectQuery('id', 'name'))
            ->from('users u')
            ->where(eq('u.status', 'active'), eq('u.deleted', 0))
            ->and(gt('u.age', 18), lt('u.age', 60));

        $expectedSql = "SELECT id, name FROM users u WHERE ( u.status = ? OR u.deleted = ? ) AND ( u.age > ? OR u.age < ? )";
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([
            'active',
            0,
            18,
            60, 
        ], $query->getBinds());
    }
}
