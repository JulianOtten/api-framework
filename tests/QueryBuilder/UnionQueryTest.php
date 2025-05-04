<?php

namespace Tests\QueryBuilder;

use PHPUnit\Framework\TestCase;
use App\Database\QueryBuilder\Paradigms\Mysql\SelectQuery;
use App\Database\QueryBuilder\Paradigms\Mysql\UnionQuery;
use App\Database\QueryBuilder\QueryBuilderFactory;

use function App\Database\QueryBuilder\Functions\eq;

class UnionQueryTest extends TestCase
{
    public function testUnionQueryBasicWithBinds()
    {
        $builder = QueryBuilderFactory::fromConnection();

        $select1 = $builder->select('systemname')->from('products')->where(eq('id', 1));
        $select2 = $builder->select('systemname')->from('orders')->where(eq('id', 1));
        $select3 = $builder->select('systemname')->from('something')->where(eq('id', 1));

        $union = $builder->union($select1, $select2, $select3);

        $expectedSql = 'SELECT systemname FROM products WHERE ( id = ? ) UNION SELECT systemname FROM orders WHERE ( id = ? ) UNION SELECT systemname FROM something WHERE ( id = ? )';
        $this->assertEquals($expectedSql, $union->build());
        $this->assertEquals([1,1,1], $union->getBinds());
    }

    public function testUnionQueryWithLimit()
    {
        $builder = QueryBuilderFactory::fromConnection();

        $select1 = $builder->select('systemname')->from('products')->where(eq('id', 1));
        $select2 = $builder->select('systemname')->from('orders')->where(eq('id', 1));
        $select3 = $builder->select('systemname')->from('something')->where(eq('id', 1));

        $union = $builder->union($select1, $select2, $select3)->limit(5);

        $expectedSql = 'SELECT systemname FROM products WHERE ( id = ? ) UNION SELECT systemname FROM orders WHERE ( id = ? ) UNION SELECT systemname FROM something WHERE ( id = ? ) LIMIT ?';
        $this->assertEquals($expectedSql, $union->build());
        $this->assertEquals([1, 1, 1, 5], $union->getBinds());
    }

    public function testUnionQueryWithOrderBy()
    {
        $builder = QueryBuilderFactory::fromConnection();

        $select1 = $builder->select('systemname')->from('products')->where(eq('id', 1));
        $select2 = $builder->select('systemname')->from('orders')->where(eq('id', 1));
        $select3 = $builder->select('systemname')->from('something')->where(eq('id', 1));

        $union = $builder->union($select1, $select2, $select3)->OrderBy('systemname', 'ASC');

        $expectedSql = 'SELECT systemname FROM products WHERE ( id = ? ) UNION SELECT systemname FROM orders WHERE ( id = ? ) UNION SELECT systemname FROM something WHERE ( id = ? ) ORDER BY systemname ASC';
        $this->assertEquals($expectedSql, $union->build());
        $this->assertEquals([1, 1, 1], $union->getBinds());
    }

    public function testUnionQueryWithOrderByAndLimit()
    {
        $builder = QueryBuilderFactory::fromConnection();

        $select1 = $builder->select('systemname')->from('products')->where(eq('id', 1));
        $select2 = $builder->select('systemname')->from('orders')->where(eq('id', 1));
        $select3 = $builder->select('systemname')->from('something')->where(eq('id', 1));

        $union = $builder->union($select1, $select2, $select3)->OrderBy('systemname', 'ASC')->limit(5);

        $expectedSql = 'SELECT systemname FROM products WHERE ( id = ? ) UNION SELECT systemname FROM orders WHERE ( id = ? ) UNION SELECT systemname FROM something WHERE ( id = ? ) ORDER BY systemname ASC LIMIT ?';
        $this->assertEquals($expectedSql, $union->build());
        $this->assertEquals([1, 1, 1, 5], $union->getBinds());
    }

    public function testUnionQueryWithSubquery()
    {
        $builder = QueryBuilderFactory::fromConnection();

        $subQuery = $builder->select('id', 'name')
            ->from('users')
            ->where(eq('status', 'active'));

        $select1 = $builder->select('id', 'name')->from($subQuery->as('active_users'));
        $select2 = $builder->select('id', 'name')->from('admins');

        $union = $builder->union($select1, $select2);

        $expectedSql = 'SELECT id, name FROM (SELECT id, name FROM users WHERE ( status = ? )) as active_users UNION SELECT id, name FROM admins';
        $this->assertEquals($expectedSql, $union->build());
        $this->assertEquals(['active'], $union->getBinds());
    }

    public function testUnionQueryWithUnionAsSubquery()
    {
        $builder = QueryBuilderFactory::fromConnection();

        $select1 = $builder->select('id', 'name')->from('users')->where(eq('status', 'active'));
        $select2 = $builder->select('id', 'name')->from('admins')->where(eq('role', 'manager'));

        $subUnion = $builder->union($select1, $select2)->as('combined_users');

        $select3 = $builder->select('id', 'name')->from($subUnion);
        $select4 = $builder->select('id', 'name')->from('guests');

        $union = $builder->union($select3, $select4);

        $expectedSql = 'SELECT id, name FROM (SELECT id, name FROM users WHERE ( status = ? ) UNION SELECT id, name FROM admins WHERE ( role = ? )) as combined_users UNION SELECT id, name FROM guests';
        $this->assertEquals($expectedSql, $union->build());
        $this->assertEquals(['active', 'manager'], $union->getBinds());
    }
}
