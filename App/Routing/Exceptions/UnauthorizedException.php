<?php

namespace App\Routing\Exceptions;

class UnauthorizedException extends HttpException {
    public function __construct(array|string|null $data = null, string $message = "Unauthorized") {
        parent::__construct($message, 401, $data);
    }
}