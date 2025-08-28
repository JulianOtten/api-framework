<?php

namespace App\Routing;

use App\Http\HttpStatus;
use App\Http\Method;
use App\Http\Request;
use App\Http\Response;
use App\Http\ResponseFactory;
use App\Resources\Interfaces\ResourceRoutesInterface;
use App\Routing\Exceptions\HttpException;
use App\Routing\Middleware\MiddlewareInterface;
use Exception;

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

        $attributes = $reflectionClass->getAttributes();

        foreach ($reflectionClass->getMethods() as $method) {
            $methodName = $method->getName();
            $path = $method->getAttributes('App\Routing\Attributes\Path')[0]->newInstance();

            $uri = sprintf(
                "/%s/%s/",
                trim($group->getPath(), '/'),
                trim($path->getPath(), '/')
            );

            $uri = rtrim($uri, '/');

            $route = new Route($uri, [$reflectionClass->newInstance(), $methodName], $path->getMethod());

            $attributes = [...$attributes, ...$method->getAttributes()];

            foreach ($attributes as $attribute) {
                $instance = $attribute->newInstance();
                if ($instance instanceof MiddlewareInterface) {
                    $route->addMiddleware($instance);
                }
            }

            $routes[] = $route; 
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
                try {
                    $response = $route->run();

                    $body = "";
                    if (is_array($response)) {
                        $body = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    }

                    $response = ResponseFactory::createResponse($body, HttpStatus::OK);
                    $response->send();
                    return;
                } catch (HttpException $e) {
                    $this->handleException($e);
                    return;
                } 
                catch (Exception $e) {
                    $response = ResponseFactory::createResponse((string) $e, HttpStatus::INTERNAL_SERVER_ERROR);
                    $response->send();
                    return;
                }
            }
        }

        self::notFound();
    }

    protected function handleException(HttpException $exception): void
    {
        $statusCode = HttpStatus::tryFrom($exception->getCode()) ?? HttpStatus::INTERNAL_SERVER_ERROR;

        $body = $exception->getData();

        if (is_string($body)) {
            $body = [
                'error' => $body,
            ];
        }

        if (is_array($body)) {
            $body = json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        if (empty($body)) {
            $body = "";
        }

        $response = ResponseFactory::createResponse($body, $statusCode);
        $response->send();
    }

    protected static function notFound()
    {
        Response::fromHttpStatus(HttpStatus::NOT_FOUND);
    }
}
