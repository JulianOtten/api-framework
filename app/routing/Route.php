<?php

namespace App\Routing;

use App\Http\Method;
use App\Http\Request;
use Exception;

class Route
{
    private $path = null;
    private $callback = null;
    private $method = null;

    public function __construct(string $path, callable $callback, Method $method)
    {
        $this->path = $path;
        $this->callback = $callback;
        $this->method = $method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): Method
    {
        return $this->method;
    }

    public function run(...$args)
    {

        $reflectionClass = new \ReflectionMethod(Router::class, 'get');
        $attributes = $reflectionClass->getAttributes();
        dd($attributes);
        foreach ($attributes as $attribute) {
            $instance = $attribute->newInstance();
            if(!$instance->run()) {
                throw new Exception("fuck you no access");
            }
        }

        $args = [...$args, ...array_values($this->getVariables())];
        return call_user_func($this->callback, ...$args);
    }

    public function match(): bool
    {
        $request = Router::getRequest();
        $requestUrl = $request->getUri()->getPath();
        $urlSplit = explode("/", $requestUrl);
        $pathSplit = explode("/", $this->getPath());

        if (count($urlSplit) !== count($pathSplit)) {
            return false;
        }

        foreach ($pathSplit as $index => $subPath) {
            if ($this->isVariable($subPath)) {
                continue;
            }

            if ($subPath !== $urlSplit[$index]) {
                return false;
            }
        }

        return true;
    }

    public function getVariables(): array
    {
        $request = Router::getRequest();
        $requestUrl = $request->getUri()->getPath();
        $urlSplit = explode("/", $requestUrl);
        $pathSplit = explode("/", $this->getPath());

        $variables = [];
        foreach ($pathSplit as $index => $subPath) {
            if (!$this->isVariable($subPath)) {
                continue;
            }

            $key = preg_replace("/{(\w)}/", "$1", $subPath);
            $variables[$key] = $urlSplit[$index];
        }

        return $variables;
    }

    private function isVariable($string): bool
    {
        return str_starts_with($string, "{") && str_ends_with($string, "}");
    }
}
