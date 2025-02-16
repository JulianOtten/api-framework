<?php

namespace App;

use App\Resources\AbstractResourceController;
use App\Routing\Router;
use App\Util\Env;
use Resources\Ingredient\IngredientController;

class App
{

    private $resources = [
        new IngredientController(),
    ];

    public function __construct()
    {
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
        $routeControllers = array_map(function (AbstractResourceController $controller) {
            return $controller->getController();
        }, $this->getResources());

        // remove null values
        $routeControllers = array_filter($routeControllers);

        $router = new Router($routeControllers);
    }

}