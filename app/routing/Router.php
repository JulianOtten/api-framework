<?php

namespace App\Routing;

use App\Filesystem\File;
use App\Http\HttpStatus;
use App\Http\Method;
use App\Http\Request;
use App\Http\Response;
use App\Http\ResponseFactory;
use App\middleware\Middleware;
use App\middleware\MiddlewareInterface;
use App\Middleware\TestMiddleware;
use App\Routing\Attributes\Path;
use App\Routing\Interfaces\RoutableInterface;
use ReflectionClass;

class Router
{
    private static array $routes = [];

    public static function route()
    {
        $request = self::getRequest();

        $method = $request->getMethod();
        $method = Method::tryFrom(ucfirst(strtolower($method)));

        if (is_null($method)) {
            Response::fromHttpStatus(HttpStatus::METHOD_NOT_ALLOWED);
            return;
        }

        $routes = [...self::$routes[$method->value] ?? [], ...self::$routes[Method::Any->value]];

        foreach ($routes as $route) {
            if ($route->match()) {
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

    /**
     * Base request sent to the server, which CAN be modified by the router, purely for routing purposes
     *
     * @return Request $request;
     */
    public static function getRequest(): Request
    {
        $request = new Request();

        // implement redirect logic here
        // if the current uri is a registerd redirect, call the ->withUri method and change it

        return $request;
    }


    protected static function match(string $path, callable $callback, Method $method = Method::Get): Route
    {
        $route = new Route($path, $callback, $method);

        if (!isset(self::$routes[$method->value])) {
            self::$routes[$method->value] = [];
        }

        self::$routes[$method->value][] = $route;
        return $route;
    }

    public static function get(string $path, callable|RoutableInterface $callback): Route
    {
        if($callback instanceof RoutableInterface) {
            $callback = $callback->get;
        }
        return self::match($path, $callback, Method::Get);
    }

    public static function post(string $path, callable|RoutableInterface $callback): Route
    {
        return self::match($path, $callback, Method::Post);
    }

    public static function put(string $path, callable|RoutableInterface $callback): Route
    {
        return self::match($path, $callback, Method::Put);
    }

    public static function patch(string $path, callable|RoutableInterface $callback): Route
    {
        return self::match($path, $callback, Method::Patch);
    }

    public static function delete(string $path, callable|RoutableInterface $callback): Route
    {
        return self::match($path, $callback, Method::Delete);
    }

    public static function options(string $path, callable|RoutableInterface $callback): Route
    {
        return self::match($path, $callback, Method::Options);
    }

    public static function any(string $path, callable $callback): Route
    {
        return self::match($path, $callback, Method::Any);
    }

    public static function registerController(RoutableInterface $controller)
    {
        $reflectionClass = new ReflectionClass($controller);

        $classAttributes = $reflectionClass->getAttributes();

        $path = "";
        foreach($classAttributes as $attr) {
            $instance = $attr->newInstance();
            
            if($instance instanceof Path)
            {
                $path = $instance->path;
            }
        }

        $methods = $reflectionClass->getMethods();
        foreach($methods as $method) {

        }
    }
}
