<?php

namespace App\Http;

use App\Http\Message;
use App\Http\Uri;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements ServerRequestInterface
{
    private string $method;
    private UriInterface $uri;
    private array $queryParams;
    private array $parsedBody;
    private array $attributes = [];
    private array $server;
    private array $cookie;
    private array $files;

    public function __construct()
    {
        parent::__construct();
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $this->createUri();
        $this->queryParams = $_GET;
        $this->parsedBody = $_POST;
        $this->server = $_SERVER;
        $this->cookie = $_COOKIE;
        $this->files = $_FILES;
    }

    public function getRequestTarget(): string
    {
        return $this->uri->getPath();
    }

    public function withRequestTarget($requestTarget): Request
    {
        $new = clone $this;
        $new->uri = $new->uri->withPath($requestTarget);
        // Assuming request target is part of URI and cannot be modified directly
        return $new;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod(string $method): Request
    {
        $new = clone $this;
        $new->method = $method;
        return $new;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false): Request
    {
        $new = clone $this;
        $new->uri = $uri;
        return $new;
    }

    public function getServerParams(): array
    {
        return $this->server;
    }

    public function getCookieParams(): array
    {
        return $this->cookie;
    }

    public function withCookieParams(array $cookies): Request
    {
        $new = clone $this;
        $new->cookie = $cookies;
        // Assuming cookies are set during request creation and cannot be modified
        return $new;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): Request
    {
        $new = clone $this;
        $new->queryParams = $query;
        return $new;
    }

    public function getUploadedFiles(): array
    {
        return $this->files;
    }

    public function withUploadedFiles(array $uploadedFiles): Request
    {
        $new = clone $this;
        $new->files = $uploadedFiles;
        return $new;
    }

    public function getParsedBody(): null|array|object
    {
        return $this->parsedBody;
    }

    public function withParsedBody($data): Request
    {
        $new = clone $this;
        $new->parsedBody = $data;
        return $new;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute($name, $value): Request
    {
        $new = clone $this;
        $new->attributes[$name] = $value;
        return $new;
    }

    public function withoutAttribute($name): Request
    {
        $new = clone $this;
        unset($new->attributes[$name]);
        return $new;
    }

    private function createUri(): Uri
    {
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $path = $_SERVER['REQUEST_URI'];
        $uri = $scheme . '://' . $host . $path;
        return new Uri($uri);
    }
}
