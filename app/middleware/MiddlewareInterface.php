<?php

namespace App\middleware;

interface MiddlewareInterface
{
    public function __construct();

    public function run(): bool;
}