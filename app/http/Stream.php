<?php

namespace App\Http;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
    private $resource;

    public function __construct($resource)
    {
        if (!is_resource($resource)) {
            throw new \InvalidArgumentException('Invalid resource provided');
        }
        $this->resource = $resource;
    }

    public function __toString()
    {
        if ($this->isSeekable()) {
            $this->rewind();
        }
        return stream_get_contents($this->resource);
    }

    public function close(): void
    {
        fclose($this->resource);
    }

    public function detach()
    {
        $resource = $this->resource;
        $this->resource = null;
        return $resource;
    }

    public function getSize(): null|int
    {
        $stats = fstat($this->resource);
        return $stats['size'] ?? null;
    }

    public function tell(): int
    {
        return ftell($this->resource);
    }

    public function eof(): bool
    {
        return feof($this->resource);
    }

    public function isSeekable(): bool
    {
        $metadata = stream_get_meta_data($this->resource);
        return $metadata['seekable'];
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        fseek($this->resource, $offset, $whence);
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        $metadata = stream_get_meta_data($this->resource);
        $mode = $metadata['mode'];
        return strstr($mode, 'w') || strstr($mode, '+');
    }

    public function write(string $string): int
    {
        return fwrite($this->resource, $string);
    }

    public function isReadable(): bool
    {
        $metadata = stream_get_meta_data($this->resource);
        $mode = $metadata['mode'];
        return strstr($mode, 'r') || strstr($mode, '+');
    }

    public function read(int $length): string
    {
        return fread($this->resource, $length);
    }

    public function getContents(): string
    {
        return stream_get_contents($this->resource);
    }

    public function getMetadata(?string $key = null)
    {
        $metadata = stream_get_meta_data($this->resource);
        return $key === null ? $metadata : ($metadata[$key] ?? null);
    }
}
