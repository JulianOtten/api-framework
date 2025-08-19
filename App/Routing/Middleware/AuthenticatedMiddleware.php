<?php

namespace App\Routing\Middleware;

use App\Database\QueryBuilder\Paradigms\MySQL\QueryBuilder;
use App\Database\QueryBuilder\QueryBuilderFactory;
use App\Filesystem\File;
use App\Http\Request;
use App\Routing\Exceptions\UnauthorizedException;
use Resources\Client\ClientModal;

#[\Attribute]
class AuthenticatedMiddleware implements MiddlewareInterface
{
    public function __construct()
    {
    }

    public function run(): bool
    {
        $request = new Request();

        $key = $request->getHeader('api-key');

        $client = ClientModal::getByApiToken($key);

        if(empty($client)) {
            throw new UnauthorizedException([
                "message" => "Please provide a valid api key"
            ]);
        }

        return true;
    }
}
