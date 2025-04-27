<?php

use App\App;
use App\Database\QueryBuilder\Paradigms\MySQL\QueryBuilder;
use App\Database\QueryBuilder\QueryBuilderFactory;

use function App\Database\QueryBuilder\Functions\ceq;
use function App\Database\QueryBuilder\Functions\gt;
use function App\Database\QueryBuilder\Functions\lt;
use function App\Database\QueryBuilder\Functions\eq;

require_once "vendor/autoload.php";
require_once "constants.php";
require_once "functions/dd.php";


$builder = QueryBuilderFactory::fromConnection();
$builder2 = QueryBuilderFactory::fromConnection();

$start = microtime(true);

$query = $builder->select(
    'id',
    'systemname',
    'price_excl',
    // $builder2->select('SUM(price_excl)')->from('orders_items oi')->where(eq('oi.product_id', 'p.id'))->as('total_sold'),
    'another_column'
)
->from('products p')
->join('products_properties_variants ppv', ceq('p.id', 'ppv.product_id'))
->leftJoin('products_properties_variants_values ppvv', ceq('ppv.id', 'ppvv.product_variant_id'), lt('ppv.id', 10))
->rightJoin('products_properties_variants ppv', ceq('p.id', 'ppv.product_id'))
->where(gt('p.id', 10), lt('p.id', 50))
->and(eq('p.status', 1))
->orderBy('id', 'DESC')
->limit(5, 10);

d($query->getBinds());

d($query->build());

dd(microtime(true) - $start);

$app = new App();
