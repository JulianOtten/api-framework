<?php

use App\Routing\RouteLoader;
use App\Routing\Router;
use App\Util\Env;

// require composer autoloader
require_once "vendor/autoload.php";
require_once "constants.php";
require_once "functions/dd.php";

// very basic setup we need for each call
$env = Env::getInstance();
$env->setup();

// load all the routes
RouteLoader::load();

// actually route to the given path, and run its callback
Router::route();
