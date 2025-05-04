<?php

use App\App;
use App\Database\QueryBuilder\Paradigms\MySQL\QueryBuilder;
use App\Database\QueryBuilder\Paradigms\MySQL\SelectQuery;
use App\Database\QueryBuilder\QueryBuilderFactory;

use function App\Database\QueryBuilder\Functions\ceq;
use function App\Database\QueryBuilder\Functions\gt;
use function App\Database\QueryBuilder\Functions\lt;
use function App\Database\QueryBuilder\Functions\eq;
use function App\Database\QueryBuilder\Functions\in;

require_once "vendor/autoload.php";
require_once "constants.php";
require_once "functions/dd.php";

$subQuery = (new SelectQuery('id'))
->from('admins')
->where(eq('role', 'superadmin'));

$query = (new SelectQuery('id', 'name'))
->from('users')
->where(in('id', $subQuery));

d($query->getBinds());
dd($query->build());

$app = new App();
