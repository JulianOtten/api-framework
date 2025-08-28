<?php

namespace App;

use App\Database\Database;
use App\Error\ErrorHandler;
use App\Resources\AbstractResourceController;
use App\Routing\Router;
use App\Util\Env;
use Resources\Client\ClientController;
use Resources\Service\ServiceController;

class App
{
    private $resources;
    public static ?Database $db = null;

    public function __construct()
    {

        // very basic setup we need for each call
        $env = Env::getInstance();
        $env->setup();

        App::$db = new Database(
            $env->getVariable('MYSQL_HOST'),
            $env->getVariable('MYSQL_DATABASE'),
            $env->getVariable('MYSQL_USER'),
            $env->getVariable('MYSQL_PASSWORD'),
            // $env->getVariable('MYSQL_PORT'),
        );

        $this->resources = [
            new ClientController(),
            new ServiceController(),
        ];

        new ErrorHandler();

        $this->route();
    }

    public function getResources(): array
    {
        return $this->resources;
    }

    private function route()
    {
        $routes = array_map(function (AbstractResourceController $controller) {
            return $controller->getRoutes();
        }, $this->getResources());

        // remove null values
        $routeControllers = array_filter($routes);

        $router = new Router($routeControllers);
    }
}
