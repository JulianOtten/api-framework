<?php

use App\Routing\Router;

Router::get("/products", function () {
    return "Products get!";
});
