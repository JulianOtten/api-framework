<?php

namespace App;

use App\Resources\AbstractResourceController;
use App\Routing\Router;
use App\Util\Env;
use Resources\Ingredient\IngredientController;

class App
{
    private $resources;

    public function __construct()
    {

        $this->resources = [
            new IngredientController(),
        ];

        // very basic setup we need for each call
        $env = Env::getInstance();
        $env->setup();

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
