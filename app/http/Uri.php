<?php

namespace App\Http;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    private $scheme;
    private $host;
    private $path;
    private $query;
    private $fragment;
    private $userInfo;
    private $port;

    public function __construct($uri)
    {
        $parts = parse_url($uri);
        $this->scheme = $parts['scheme'] ?? '';
        $this->host = $parts['host'] ?? '';
        $this->path = $parts['path'] ?? '';
        $this->query = $parts['query'] ?? '';
        $this->fragment = $parts['fragment'] ?? '';
        $this->userInfo = $parts['user'] ?? '';
        if (isset($parts['pass'])) {
            $this->userInfo .= ':' . $parts['pass'];
        }
        $this->port = $parts['port'] ?? null;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getAuthority(): string
    {
        $authority = $this->host;
        if ($this->userInfo) {
            $authority = $this->userInfo . '@' . $authority;
        }
        if ($this->port) {
            $authority .= ':' . $this->port;
        }
        return $authority;
    }

    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): null|int
    {
        return $this->port;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function withScheme($scheme): Uri
    {
        $new = clone $this;
        $new->scheme = $scheme;
        return $new;
    }

    public function withUserInfo($user, $password = null): Uri
    {
        $new = clone $this;
        $new->userInfo = $user;
        if ($password) {
            $new->userInfo .= ':' . $password;
        }
        return $new;
    }

    public function withHost($host): Uri
    {
        $new = clone $this;
        $new->host = $host;
        return $new;
    }

    public function withPort($port): Uri
    {
        $new = clone $this;
        $new->port = $port;
        return $new;
    }

    public function withPath($path): Uri
    {
        $new = clone $this;
        $new->path = $path;
        return $new;
    }

    public function withQuery($query): Uri
    {
        $new = clone $this;
        $new->query = $query;
        return $new;
    }

    public function withFragment($fragment): Uri
    {
        $new = clone $this;
        $new->fragment = $fragment;
        return $new;
    }

    public function __toString(): string
    {
        $uri = $this->scheme . '://';
        if ($this->userInfo) {
            $uri .= $this->userInfo . '@';
        }
        $uri .= $this->host;
        if ($this->port) {
            $uri .= ':' . $this->port;
        }
        $uri .= $this->path;
        if ($this->query) {
            $uri .= '?' . $this->query;
        }
        if ($this->fragment) {
            $uri .= '#' . $this->fragment;
        }
        return $uri;
    }
}
