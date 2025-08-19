<?php

namespace App\Routing\Exceptions;

class NotFoundException extends HttpException {
    public function __construct(array|string|null $data = null, string $message = "Not Found") {
        parent::__construct($message, 404, $data);
    }
}