<?php
use App\Http\ResponseFactory;

use App\Routing\Router;


Router::get('/', function () {
    $response = ResponseFactory::createResponse(json_encode([
        "test" => "1",
    ], JSON_PRETTY_PRINT));
    $response->send();
    // dd($response);
});

Router::any('/test', function () {
    $response = ResponseFactory::createResponse(json_encode([
        "any working" => "as intended",
    ], JSON_PRETTY_PRINT));
    $response->send();
    // dd($response);
});
