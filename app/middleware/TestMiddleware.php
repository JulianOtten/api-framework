<?php

namespace App\Middleware;

use App\Filesystem\File;

class TestMiddleware implements MiddlewareInterface {
    public function __construct() {}

    public function run(): bool
    {
        $file = new File(ROOT . "/test.log");
        $file->create();
        $file->write('THIS IS WORKING');
        return true;
    }
}