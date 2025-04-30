<?php

namespace App\Routing\Attributes;

use App\Http\Method;

#[\Attribute]
class Path
{
    public function __construct(
        public string $path,
        public Method $method
    ) {
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getMethod()
    {
        return $this->method;
    }
}
