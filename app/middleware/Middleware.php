<?php

namespace App\middleware;

#[\Attribute]
class Middleware
{
    public function __construct(public MiddlewareInterface $middleware)
    {

    }

    public function run() 
    {
        return $this->middleware->run();
    }
}