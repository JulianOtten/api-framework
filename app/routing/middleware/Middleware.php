<?php

namespace App\Routing\Middleware;

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