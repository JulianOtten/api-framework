<?php

namespace App\Routing\Attributes;

use App\Http\Method;

#[\Attribute]
class Route
{
    public function __construct(
        public Method $method,
        public string $path,
    ) {
    }
}
