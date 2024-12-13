<?php

namespace App\util\Attributes;

#[\Attribute]
class ArrayType
{
    public function __construct(public string $className) {}
}