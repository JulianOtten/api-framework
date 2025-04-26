<?php

use App\App;
use App\Database\QueryBuilder\Paradigms\MySQL\QueryBuilder;
use App\Database\QueryBuilder\QueryBuilderFactory;

use function App\Database\QueryBuilder\Functions\gt;
use function App\Database\QueryBuilder\Functions\lt;
use function App\Database\QueryBuilder\Functions\eq;

require_once "vendor/autoload.php";
require_once "constants.php";
require_once "functions/dd.php";


$builder = QueryBuilderFactory::fromConnection();
$builder2 = QueryBuilderFactory::fromConnection();

$query = $builder->select(
    'id',
    'systemname',
    'price_excl',
    $builder2->select('SUM(price_excl)')->from('orders_items oi')->where(eq('oi.product_id', 'p.id'))->as('total_sold'),
    'another_column'
)
->from('products p')
->join('products_properties_variants ppv', 'p.id = ppv.product_id')
->where(gt('p.id', 10), lt('p.id', 50))
->and(eq('p.status', 1))
->orderBy('id', 'DESC')
->limit(5, 10)
->build();

dd($query);

$app = new App();
