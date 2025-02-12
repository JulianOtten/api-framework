<?php

namespace App\Routing\Middleware;

use App\Filesystem\File;

class TestMiddleware implements MiddlewareInterface {
    public function __construct() {}

    public function run(): bool
    {
        $file = new File(ROOT . "/logs/test.log");
        $file->create();
        $file->write("THIS IS WORKING\n");
        return true;
    }
}