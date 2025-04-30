<?php

namespace App\Routing\Middleware;

interface MiddlewareInterface
{
    public function __construct();

    public function run(): bool;
}
