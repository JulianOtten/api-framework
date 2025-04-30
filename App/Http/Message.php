<?php

namespace App\Http;

use App\Http\Stream;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Message implements MessageInterface
{
    protected string $protocol;
    protected array $headers;
    protected StreamInterface $body;

    public function __construct()
    {
        $this->headers = $this->createHeaders();
        $this->body = $this->createBody();
        $this->protocol = $_SERVER['SERVER_PROTOCOL'];
    }

    public function getProtocolVersion(): string
    {
        return $this->protocol;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        $new = clone $this;
        $new->protocol = $version;
        return $new;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        $name = $this->parseHeaderKey($name);
        return isset($this->headers[$name]);
    }

    public function getHeader(string $name): array
    {
        $name = $this->parseHeaderKey($name);
        return $this->headers[$name] ?? [];
    }

    public function getHeaderLine(string $name): string
    {
        $name = $this->parseHeaderKey($name);
        return implode(', ', $this->getHeader($name));
    }

    public function withHeader($name, $value): MessageInterface
    {
        $name = $this->parseHeaderKey($name);
        $new = clone $this;
        $new->headers[$name] = (array)$value;
        return $new;
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $name = $this->parseHeaderKey($name);
        $new = clone $this;
        $new->headers[$name][] = $value;
        return $new;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        $name = $this->parseHeaderKey($name);
        $new = clone $this;
        unset($new->headers[$name]);
        return $new;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }

    /**
     * Returns the formatted version of a given key, to comform to psr-7 standard of case insensitive header searching.
     *
     * @param string $key
     * @return string
     */
    private function parseHeaderKey(string $key): string
    {
        if (strpos($key, 'HTTP_') === 0) {
            $key = substr($key, 5);
        }

        return str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
    }

    private function createHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $name = $this->parseHeaderKey($key);
                $headers[$name] = [$value];
            }
        }
        return $headers;
    }

    protected function createBody()
    {
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, file_get_contents('php://input'));
        rewind($stream);
        return new Stream($stream);
    }
}
