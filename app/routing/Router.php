<?php

namespace App\Routing;

use App\Http\HttpStatus;
use App\Http\Method;
use App\Http\Request;
use App\Http\Response;
use App\Resources\Interfaces\ResourceRoutesInterface;

class Router
{
    public function __construct(private $routeControllers = [], private $routes = [])
    {
        $this->setRoutes();
        $this->route();
    }

    private function setRoutes()
    {
        foreach ($this->routeControllers as $controller) {
            $this->routes = [...$this->routes, ...$this->controllerToRoutes($controller)];
        }
    }

    private function controllerToRoutes(ResourceRoutesInterface $controller)
    {
        $routes = [];

        $reflectionClass = new \ReflectionClass($controller);

        $group = $reflectionClass->getAttributes('App\Routing\Attributes\PathGroup')[0]->newInstance();

        foreach ($reflectionClass->getMethods() as $method) {
            $methodName = $method->getName();
            $path = $method->getAttributes('App\Routing\Attributes\Path')[0]->newInstance();;

            $uri = sprintf("/%s/%s/", 
                trim($group->getPath(), '/'),
                trim($path->getPath(), '/')
            );

            $uri = rtrim($uri, '/');

            $routes[] = new Route($uri, [$reflectionClass->newInstance(), $methodName], $path->getMethod());
        }

        return $routes;
    }

    public function route()
    {
        $request = new Request();

        $method = $request->getMethod();
        $method = Method::tryFrom(ucfirst(strtolower($method)));

        if (is_null($method)) {
            Response::fromHttpStatus(HttpStatus::METHOD_NOT_ALLOWED);
            return;
        }

        foreach ($this->routes as $route) {
            if ($route->match($method)) {
                $route->run();
                return;
            }
        }

        self::notFound();
    }

    protected static function notFound()
    {
        Response::fromHttpStatus(HttpStatus::NOT_FOUND);
    }
}
