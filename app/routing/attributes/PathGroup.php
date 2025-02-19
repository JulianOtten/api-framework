<?php

namespace App\Routing\Attributes;

#[\Attribute]
class PathGroup
{
    public function __construct(
        public string $path,
    ) {
    }

    public function getPath()
    {
        return $this->path;
    }
}
