<?php

namespace App\Http;

class ResponseFactory
{
    public static function createResponse(string $body, HttpStatus $status = HttpStatus::OK, $headers = [])
    {

        if (empty($headers)) {
            $headers = [
                "content-type" => "application/json",
            ];
        }

        $response = new Response();
        $response = $response
            ->withStatus($status->value)
            ->withBody(new Stream(fopen('php://temp', 'r+')));

        foreach ($headers as $name => $value) {
            $response = $response->withHeader($name, $value);
        }

        $response->getBody()->write($body);
        return $response;
    }
}
