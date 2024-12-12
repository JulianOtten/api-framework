<?php

use App\Http\ResponseFactory;
use App\Routing\Router;

Router::get("/users/{id}", function ($id = "fallback") {

    $response = [
        "user_id" => $id,
        "username" => "yukihyo",
    ];
    ResponseFactory::createResponse(json_encode($response, JSON_PRETTY_PRINT))->send();
});

Router::get("/users/{user_id}/order/{order_id}", function ($userId, $orderId) {

    $response = [
        "user_id" => $userId,
        "order_id" => $orderId,
        "username" => "yukihyo",
    ];
    ResponseFactory::createResponse(json_encode($response, JSON_PRETTY_PRINT))->send();
});

Router::post("/users", function () {
    ResponseFactory::createResponse("User Reached")->send();
});
