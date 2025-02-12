<?php

namespace App\Routing\Attributes;

#[\Attribute]
class Path
{
    public function __construct(
        public string $path,
    ) {}

    // public string $path {
    //     get {
    //         return '/' . rtrim($this->path, '/');
    //     }
    // }
}