<?php

namespace Tests\QueryBuilder;

use PHPUnit\Framework\TestCase;
use App\Database\QueryBuilder\Paradigms\MySQL\SelectQuery;

use function App\Database\QueryBuilder\Functions\eq;
use function App\Database\QueryBuilder\Functions\ceq;
use function App\Database\QueryBuilder\Functions\cgt;
use function App\Database\QueryBuilder\Functions\cgte;
use function App\Database\QueryBuilder\Functions\clt;
use function App\Database\QueryBuilder\Functions\clte;
use function App\Database\QueryBuilder\Functions\cnotEq;
use function App\Database\QueryBuilder\Functions\gt;
use function App\Database\QueryBuilder\Functions\gte;
use function App\Database\QueryBuilder\Functions\lt;
use function App\Database\QueryBuilder\Functions\in;
use function App\Database\QueryBuilder\Functions\isNotNull;
use function App\Database\QueryBuilder\Functions\isNull;
use function App\Database\QueryBuilder\Functions\lte;
use function App\Database\QueryBuilder\Functions\notEq;

class SelectQueryTest extends TestCase
{
    public function testSelectQueryBasic()
    {
        $query = (new SelectQuery('id', 'name'))->from('users');

        $expectedSql = 'SELECT id, name FROM users';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectWithMultipleSelectMethodsQueryBasic()
    {
        $query = (new SelectQuery('id', 'name'))->select('price')->from('users');

        $expectedSql = 'SELECT id, name, price FROM users';
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

    public function testSelectQueryWithDistinct()
    {
        $query = (new SelectQuery('DISTINCT name'))
            ->from('users');

        $expectedSql = 'SELECT DISTINCT name FROM users';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithMultipleGroupBy()
    {
        $query = (new SelectQuery('status', 'role', 'COUNT(*) as count'))
            ->from('users')
            ->groupBy('status', 'role');

        $expectedSql = 'SELECT status, role, COUNT(*) as count FROM users GROUP BY status, role';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithHavingAndGroupBy()
    {
        $query = (new SelectQuery('status', 'COUNT(*) as count'))
            ->from('users')
            ->groupBy('status')
            ->having(gt('count', 5));

        $expectedSql = 'SELECT status, COUNT(*) as count FROM users GROUP BY status HAVING ( count > ? )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([5], $query->getBinds());
    }

    public function testSelectQueryWithHavingAndOrderBy()
    {
        $query = (new SelectQuery('status', 'COUNT(*) as count'))
            ->from('users')
            ->groupBy('status')
            ->having(gt('count', 5))
            ->orderBy('count', 'DESC');

        $expectedSql = 'SELECT status, COUNT(*) as count FROM users GROUP BY status HAVING ( count > ? ) ORDER BY count DESC';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([5], $query->getBinds());
    }

    public function testSelectQueryWithHavingAndLimit()
    {
        $query = (new SelectQuery('status', 'COUNT(*) as count'))
            ->from('users')
            ->groupBy('status')
            ->having(gt('count', 5))
            ->limit(10);

        $expectedSql = 'SELECT status, COUNT(*) as count FROM users GROUP BY status HAVING ( count > ? ) LIMIT ?';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([5, 10], $query->getBinds());
    }

    public function testSelectQueryWithWhereAndHaving()
    {
        $query = (new SelectQuery('status', 'COUNT(*) as count'))
            ->from('users')
            ->where(eq('is_active', true))
            ->groupBy('status')
            ->having(gt('count', 5));

        $expectedSql = 'SELECT status, COUNT(*) as count FROM users WHERE ( is_active = ? ) GROUP BY status HAVING ( count > ? )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([true, 5], $query->getBinds());
    }

    public function testSelectQueryWithHavingMultipleConditions()
    {
        $query = (new SelectQuery('status', 'COUNT(*) as count'))
            ->from('users')
            ->groupBy('status')
            ->having(gt('count', 5))
            ->having(lt('count', 20));

        $expectedSql = 'SELECT status, COUNT(*) as count FROM users GROUP BY status HAVING ( count > ? ) AND ( count < ? )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([5, 20], $query->getBinds());
    }

    public function testSelectQueryWithWhereHavingOrderByAndLimit()
    {
        $query = (new SelectQuery('status', 'COUNT(*) as count'))
            ->from('users')
            ->where(eq('is_active', true))
            ->groupBy('status')
            ->having(gt('count', 5))
            ->orderBy('count', 'DESC')
            ->limit(10);

        $expectedSql = 'SELECT status, COUNT(*) as count FROM users WHERE ( is_active = ? ) GROUP BY status HAVING ( count > ? ) ORDER BY count DESC LIMIT ?';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([true, 5, 10], $query->getBinds());
    }

    public function testSelectQueryWithSubqueryInSelect()
    {
        $subQuery = (new SelectQuery('COUNT(*)'))
            ->from('orders')
            ->where(ceq('orders.user_id', 'users.id'));

        $query = (new SelectQuery('id', 'name', $subQuery->as('order_count')))
            ->from('users');

        $expectedSql = 'SELECT id, name, (SELECT COUNT(*) FROM orders WHERE ( orders.user_id = users.id )) as order_count FROM users';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithSubqueryInWhere()
    {
        $subQuery = (new SelectQuery('id'))
            ->from('admins')
            ->where(eq('role', 'superadmin'));

        $query = (new SelectQuery('id', 'name'))
            ->from('users')
            ->where(in('id', $subQuery));

        $expectedSql = 'SELECT id, name FROM users WHERE ( id IN (SELECT id FROM admins WHERE ( role = ? )) )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals(['superadmin'], $query->getBinds());
    }

    public function testSelectQueryWithArrayInWhere()
    {
        $query = (new SelectQuery('id', 'name'))
            ->from('users')
            ->where(in('id', [1,2,8,16,32,107]));

        $expectedSql = 'SELECT id, name FROM users WHERE ( id IN ( ?, ?, ?, ?, ?, ? ) )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([1,2,8,16,32,107], $query->getBinds());
    }

    public function testSelectQueryWithMultipleOrderBy()
    {
        $query = (new SelectQuery('id', 'name'))
            ->from('users')
            ->orderBy('name', 'ASC')
            ->orderBy('created_at', 'DESC');

        $expectedSql = 'SELECT id, name FROM users ORDER BY name ASC, created_at DESC';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithJoinAndAlias()
    {
        $query = (new SelectQuery('u.id', 'u.name', 'o.total'))
            ->from('users u')
            ->join('orders o', ceq('u.id', 'o.user_id'));

        $expectedSql = 'SELECT u.id, u.name, o.total FROM users u JOIN orders o ON u.id = o.user_id';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithComplexJoinConditions()
    {
        $query = (new SelectQuery('u.id', 'u.name', 'o.total'))
            ->from('users u')
            ->join('orders o', ceq('u.id', 'o.user_id'), gt('o.total', 100));

        $expectedSql = 'SELECT u.id, u.name, o.total FROM users u JOIN orders o ON u.id = o.user_id AND o.total > ?';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([100], $query->getBinds());
    }

    public function testSelectQueryWithNestedJoins()
    {
        $query = (new SelectQuery('u.id', 'u.name', 'p.name as product_name'))
            ->from('users u')
            ->join('orders o', ceq('u.id', 'o.user_id'))
            ->join('products p', ceq('o.product_id', 'p.id'));

        $expectedSql = 'SELECT u.id, u.name, p.name as product_name FROM users u JOIN orders o ON u.id = o.user_id JOIN products p ON o.product_id = p.id';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithRawSQL()
    {
        $query = (new SelectQuery('id', 'name', 'NOW() as current_time'))
            ->from('users');

        $expectedSql = 'SELECT id, name, NOW() as current_time FROM users';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithEmptyWhere()
    {
        $query = (new SelectQuery('id', 'name'))
            ->from('users');

        $expectedSql = 'SELECT id, name FROM users';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithMultipleAliases()
    {
        $query = (new SelectQuery('u.id as user_id', 'u.name as user_name', 'o.total as order_total'))
            ->from('users u')
            ->join('orders o', ceq('u.id', 'o.user_id'));

        $expectedSql = 'SELECT u.id as user_id, u.name as user_name, o.total as order_total FROM users u JOIN orders o ON u.id = o.user_id';
        $this->assertEquals($expectedSql, $query->build());
    }

    /**
     * The reason this should fail, is because we want to disallow as many
     * sql related character as possible to make the chance of sql injection
     * as low as possible
     *
     * @return void
     */
    public function testSelectQueryWithEscapedIdentifiers()
    {
        $query = (new SelectQuery('`id`', '`name`'))
            ->from('`users`');

        $expectedSql = 'SELECT `id`, `name` FROM `users`';
        $this->assertEquals("", $query->build());
    }

    public function testSelectQueryWithBooleanConditions()
    {
        $query = (new SelectQuery('id', 'name'))
            ->from('users')
            ->where(eq('is_active', true));

        $expectedSql = 'SELECT id, name FROM users WHERE ( is_active = ? )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([true], $query->getBinds());
    }

    public function testSelectQueryWithNullValuesInWhere()
    {
        $query = (new SelectQuery('id', 'name'))
            ->from('users')
            ->where(isNull('deleted_at'));

        $expectedSql = 'SELECT id, name FROM users WHERE ( deleted_at IS NULL )';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithNotNullValuesInWhere()
    {
        $query = (new SelectQuery('id', 'name'))
            ->from('users')
            ->where(eq('deleted_at', null));

        $expectedSql = 'SELECT id, name FROM users WHERE ( deleted_at = ? )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([null], $query->getBinds());
    }

    public function testSelectQueryWithEmptyColumns()
    {
        $query = (new SelectQuery())
            ->from('users');

        $expectedSql = 'SELECT * FROM users';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithMultipleSubqueries()
    {
        $subQuery1 = (new SelectQuery('COUNT(*)'))
            ->from('orders')
            ->where(ceq('orders.user_id', 'users.id'));

        $subQuery2 = (new SelectQuery('SUM(total)'))
            ->from('orders')
            ->where(ceq('orders.user_id', 'users.id'));

        $query = (new SelectQuery('id', 'name', $subQuery1->as('order_count'), $subQuery2->as('total_spent')))
            ->from('users');

        $expectedSql = 'SELECT id, name, (SELECT COUNT(*) FROM orders WHERE ( orders.user_id = users.id )) as order_count, (SELECT SUM(total) FROM orders WHERE ( orders.user_id = users.id )) as total_spent FROM users';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithSubqueryInJoin()
    {
        $subQuery = (new SelectQuery('id', 'user_id', 'SUM(total) as total_spent'))
            ->from('orders')
            ->groupBy('user_id')
            ->as('o');

        $query = (new SelectQuery('u.id', 'u.name', 'o.total_spent'))
            ->from('users u')
            ->join($subQuery, ceq('u.id', 'o.user_id'));

        $expectedSql = 'SELECT u.id, u.name, o.total_spent FROM users u JOIN (SELECT id, user_id, SUM(total) as total_spent FROM orders GROUP BY user_id) as o ON u.id = o.user_id';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithSubqueryInLeftJoin()
    {
        $subQuery = (new SelectQuery('id', 'user_id', 'COUNT(*) as order_count'))
            ->from('orders')
            ->groupBy('user_id')
            ->as('o');

        $query = (new SelectQuery('u.id', 'u.name', 'o.order_count'))
            ->from('users u')
            ->leftJoin($subQuery, ceq('u.id', 'o.user_id'));

        $expectedSql = 'SELECT u.id, u.name, o.order_count FROM users u LEFT JOIN (SELECT id, user_id, COUNT(*) as order_count FROM orders GROUP BY user_id) as o ON u.id = o.user_id';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithSubqueryInRightJoin()
    {
        $subQuery = (new SelectQuery('id', 'user_id', 'AVG(total) as avg_spent'))
            ->from('orders')
            ->groupBy('user_id')
            ->as('o');

        $query = (new SelectQuery('u.id', 'u.name', 'o.avg_spent'))
            ->from('users u')
            ->rightJoin($subQuery, ceq('u.id', 'o.user_id'));

        $expectedSql = 'SELECT u.id, u.name, o.avg_spent FROM users u RIGHT JOIN (SELECT id, user_id, AVG(total) as avg_spent FROM orders GROUP BY user_id) as o ON u.id = o.user_id';
        $this->assertEquals($expectedSql, $query->build());
    }

    public function testSelectQueryWithSubqueryInJoinAndWhere()
    {
        $subQuery = (new SelectQuery('id', 'user_id', 'SUM(total) as total_spent'))
            ->from('orders')
            ->groupBy('user_id')
            ->as('o');

        $query = (new SelectQuery('u.id', 'u.name', 'o.total_spent'))
            ->from('users u')
            ->join($subQuery, ceq('u.id', 'o.user_id'))
            ->where(gt('o.total_spent', 100));

        $expectedSql = 'SELECT u.id, u.name, o.total_spent FROM users u JOIN (SELECT id, user_id, SUM(total) as total_spent FROM orders GROUP BY user_id) as o ON u.id = o.user_id WHERE ( o.total_spent > ? )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([100], $query->getBinds());
    }

    public function testSelectQueryWithSubqueryInJoinAndMultipleConditions()
    {
        $subQuery = (new SelectQuery('id', 'user_id', 'SUM(total) as total_spent'))
            ->from('orders')
            ->groupBy('user_id')
            ->as('o');

        $query = (new SelectQuery('u.id', 'u.name', 'o.total_spent'))
            ->from('users u')
            ->join($subQuery, ceq('u.id', 'o.user_id'))
            ->where(gt('o.total_spent', 100))
            ->and(lt('o.total_spent', 500));

        $expectedSql = 'SELECT u.id, u.name, o.total_spent FROM users u JOIN (SELECT id, user_id, SUM(total) as total_spent FROM orders GROUP BY user_id) as o ON u.id = o.user_id WHERE ( o.total_spent > ? ) AND ( o.total_spent < ? )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([100, 500], $query->getBinds());
    }

    public function testSelectQueryWithSubqueryBindsCombined()
    {
        $subQuery1 = (new SelectQuery('SUM(total)'))
            ->from('orders')
            ->where(gt('total', 25))
            ->as('total_spent');

        $subQuery2 = (new SelectQuery('id', 'user_id', 'SUM(total) as total_spent'))
            ->from('orders')
            ->where(gt('total', 50))
            ->groupBy('user_id')
            ->as('o');

        $query = (new SelectQuery('u.id', 'u.name', 'o.total_spent', $subQuery1))
            ->from('users u')
            ->join($subQuery2, ceq('u.id', 'o.user_id'))
            ->where(gt('o.total_spent', 100));

        $expectedSql = 'SELECT u.id, u.name, o.total_spent, (SELECT SUM(total) FROM orders WHERE ( total > ? )) as total_spent FROM users u JOIN (SELECT id, user_id, SUM(total) as total_spent FROM orders WHERE ( total > ? ) GROUP BY user_id) as o ON u.id = o.user_id WHERE ( o.total_spent > ? )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([25, 50, 100], $query->getBinds());
    }

    public function testSelectQueryWithOrderBySubQueryWithBind()
    {
        $subQuery = (new SelectQuery('COUNT(*)'))
            ->from('orders')
            ->where(ceq('orders.user_id', 'users.id'))
            ->and(gt('orders.total', 200));

        $query = (new SelectQuery('id', 'name'))
            ->from('users')
            ->orderBy($subQuery, 'DESC');

        $expectedSql = 'SELECT id, name FROM users ORDER BY (SELECT COUNT(*) FROM orders WHERE ( orders.user_id = users.id ) AND ( orders.total > ? )) DESC';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([200], $query->getBinds());
    }

    public function testSelectQueryWithNestedSubqueries()
    {
        $innerSubQuery1 = (new SelectQuery('SUM(total)'))
            ->from('payments')
            ->where(gt('payments.amount', 50));

        $innerSubQuery2 = (new SelectQuery('COUNT(*)'))
            ->from('reviews')
            ->where(ceq('reviews.user_id', 'users.id'));

        $subQuery1 = (new SelectQuery($innerSubQuery1->as('total_payments')))
            ->from('orders')
            ->where(ceq('orders.user_id', 'users.id'));

        $subQuery2 = (new SelectQuery($innerSubQuery2->as('review_count')))
            ->from('users');

        $joinSubQuery = (new SelectQuery('id', 'user_id', $subQuery1->as('total_orders')))
            ->from('orders')
            ->groupBy('user_id')
            ->as('o');

        $query = (new SelectQuery('u.id', 'u.name', $subQuery2->as('nested_review_count'), 'o.total_orders'))
            ->from('users u')
            ->join($joinSubQuery, ceq('u.id', 'o.user_id'))
            ->where(gt('o.total_orders', 100));

        $expectedSql = 'SELECT u.id, u.name, (SELECT (SELECT COUNT(*) FROM reviews WHERE ( reviews.user_id = users.id )) as review_count FROM users) as nested_review_count, o.total_orders FROM users u JOIN (SELECT id, user_id, (SELECT (SELECT SUM(total) FROM payments WHERE ( payments.amount > ? )) as total_payments FROM orders WHERE ( orders.user_id = users.id )) as total_orders FROM orders GROUP BY user_id) as o ON u.id = o.user_id WHERE ( o.total_orders > ? )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([50, 100], $query->getBinds());
    }

    public function testSelectQueryWithSubqueryInInCondition()
    {
        $subQuery = (new SelectQuery('id'))
            ->from('admins')
            ->where(eq('role', 'manager'));

        $query = (new SelectQuery('id', 'name'))
            ->from('users')
            ->where(in('id', $subQuery));

        $expectedSql = 'SELECT id, name FROM users WHERE ( id IN (SELECT id FROM admins WHERE ( role = ? )) )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals(['manager'], $query->getBinds());
    }

    public function testSelectQueryWithJoinAndInCondition()
    {
        $query = (new SelectQuery('u.id', 'u.name', 'o.total'))
            ->from('users u')
            ->join('orders o', ceq('u.id', 'o.user_id'))
            ->where(in('u.id', [1, 2, 3]));

        $expectedSql = 'SELECT u.id, u.name, o.total FROM users u JOIN orders o ON u.id = o.user_id WHERE ( u.id IN ( ?, ?, ? ) )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([1, 2, 3], $query->getBinds());
    }

    public function testSelectQueryWithNestedInConditions()
    {
        $innerSubQuery = (new SelectQuery('id'))
            ->from('admins')
            ->where(eq('role', 'superadmin'));

        $outerSubQuery = (new SelectQuery('user_id'))
            ->from('orders')
            ->where(in('user_id', $innerSubQuery));

        $query = (new SelectQuery('id', 'name'))
            ->from('users')
            ->where(in('id', $outerSubQuery));

        $expectedSql = 'SELECT id, name FROM users WHERE ( id IN (SELECT user_id FROM orders WHERE ( user_id IN (SELECT id FROM admins WHERE ( role = ? )) )) )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals(['superadmin'], $query->getBinds());
    }

    public function testSelectQueryWithInConditionAndMultipleJoins()
    {
        $query = (new SelectQuery('u.id', 'u.name', 'p.name as product_name'))
            ->from('users u')
            ->join('orders o', ceq('u.id', 'o.user_id'))
            ->join('products p', ceq('o.product_id', 'p.id'))
            ->where(in('u.id', [10, 20, 30]));

        $expectedSql = 'SELECT u.id, u.name, p.name as product_name FROM users u JOIN orders o ON u.id = o.user_id JOIN products p ON o.product_id = p.id WHERE ( u.id IN ( ?, ?, ? ) )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([10, 20, 30], $query->getBinds());
    }

    public function testSelectQueryWithInConditionInsideJoin()
    {
        $query = (new SelectQuery('u.id', 'u.name', 'p.name as product_name'))
            ->from('users u')
            ->join('orders o', ceq('u.id', 'o.user_id'))
            ->join('products p', ceq('o.product_id', 'p.id'), in('p.id', [1,2,3,4,5,6]));

        $expectedSql = 'SELECT u.id, u.name, p.name as product_name FROM users u JOIN orders o ON u.id = o.user_id JOIN products p ON o.product_id = p.id AND p.id IN ( ?, ?, ?, ?, ?, ? )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([1,2,3,4,5,6], $query->getBinds());
    }

    public function testSelectQueryWithInConditionAndGroupBy()
    {
        $query = (new SelectQuery('status', 'COUNT(*) as count'))
            ->from('users')
            ->where(in('status', ['active', 'inactive']))
            ->groupBy('status');

        $expectedSql = 'SELECT status, COUNT(*) as count FROM users WHERE ( status IN ( ?, ? ) ) GROUP BY status';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals(['active', 'inactive'], $query->getBinds());
    }

    public function testSelectQueryWithInConditionAndHaving()
    {
        $query = (new SelectQuery('status', 'COUNT(*) as count'))
            ->from('users')
            ->where(in('status', ['active', 'inactive']))
            ->groupBy('status')
            ->having(gt('count', 5));

        $expectedSql = 'SELECT status, COUNT(*) as count FROM users WHERE ( status IN ( ?, ? ) ) GROUP BY status HAVING ( count > ? )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals(['active', 'inactive', 5], $query->getBinds());
    }

    public function testSelectQueryWithAllConditionsInWhere()
    {
        $query = (new SelectQuery('id', 'name'))
            ->from('users')
            ->where(eq('status', 'active'))
            ->and(ceq('role', 'admin'))
            ->and(lt('age', 30))
            ->and(clt('created_at', 'updated_at'))
            ->and(gt('salary', 50000))
            ->and(cgt('bonus', 'salary'))
            ->and(in('id', [1, 2, 3]))
            ->and(isNull('deleted_at'))
            ->and(isNotNull('created_at'))
            ->and(lte('experience', 5))
            ->and(clte('start_date', 'end_date'))
            ->and(gte('rating', 4.5))
            ->and(cgte('review_count', 'threshold'))
            ->and(notEq('status', 'inactive'))
            ->and(cnotEq('role', 'guest'));

        $expectedSql = 'SELECT id, name FROM users WHERE ( status = ? ) AND ( role = admin ) AND ( age < ? ) AND ( created_at < updated_at ) AND ( salary > ? ) AND ( bonus > salary ) AND ( id IN ( ?, ?, ? ) ) AND ( deleted_at IS NULL ) AND ( created_at IS NOT NULL ) AND ( experience <= ? ) AND ( start_date <= end_date ) AND ( rating >= ? ) AND ( review_count >= threshold ) AND ( status != ? ) AND ( role != guest )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals(['active', 30, 50000, 1, 2, 3, 5, 4.5, 'inactive'], $query->getBinds());
    }

    public function testSelectQueryWithAllConditionsInJoin()
    {
        $query = (new SelectQuery('u.id', 'u.name', 'o.total'))
            ->from('users u')
            ->join('orders o', ceq('u.id', 'o.user_id'))
            ->join('products p', ceq('o.product_id', 'p.id'), gt('p.price', 100))
            ->join('categories c', in('c.id', [1, 2, 3]), isNotNull('c.name'));

        $expectedSql = 'SELECT u.id, u.name, o.total FROM users u JOIN orders o ON u.id = o.user_id JOIN products p ON o.product_id = p.id AND p.price > ? JOIN categories c ON c.id IN ( ?, ?, ? ) AND c.name IS NOT NULL';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([100, 1, 2, 3], $query->getBinds());
    }

    public function testSelectQueryWithAllConditionsInHaving()
    {
        $query = (new SelectQuery('status', 'COUNT(*) as count', 'AVG(salary) as avg_salary'))
            ->from('users')
            ->groupBy('status')
            ->having(gt('count', 10))
            ->having(lte('avg_salary', 50000))
            ->having(notEq('status', 'inactive'));

        $expectedSql = 'SELECT status, COUNT(*) as count, AVG(salary) as avg_salary FROM users GROUP BY status HAVING ( count > ? ) AND ( avg_salary <= ? ) AND ( status != ? )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([10, 50000, 'inactive'], $query->getBinds());
    }

    public function testSelectQueryWithMixedConditionsInWhereAndJoin()
    {
        $query = (new SelectQuery('u.id', 'u.name', 'o.total', 'p.name as product_name'))
            ->from('users u')
            ->join('orders o', ceq('u.id', 'o.user_id'))
            ->join('products p', ceq('o.product_id', 'p.id'), gt('p.price', 100))
            ->where(in('u.id', [1, 2, 3]))
            ->and(isNull('u.deleted_at'))
            ->and(notEq('u.status', 'inactive'));

        $expectedSql = 'SELECT u.id, u.name, o.total, p.name as product_name FROM users u JOIN orders o ON u.id = o.user_id JOIN products p ON o.product_id = p.id AND p.price > ? WHERE ( u.id IN ( ?, ?, ? ) ) AND ( u.deleted_at IS NULL ) AND ( u.status != ? )';
        $this->assertEquals($expectedSql, $query->build());
        $this->assertEquals([100, 1, 2, 3, 'inactive'], $query->getBinds());
    }
}
