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

$app = new App();
