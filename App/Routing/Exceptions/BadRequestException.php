<?php

namespace App\Routing\Exceptions;

class BadRequestException extends HttpException {
    public function __construct(array|string|null $data = null, string $message = "Bad Request") {
        parent::__construct($message, 400, $data);
    }
}