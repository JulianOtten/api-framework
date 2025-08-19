<?php

namespace App\Routing\Exceptions;

use Exception;

class HttpException extends \Exception {
    private array|string|null $data;

    public function __construct(string $message = "", int $code = 500, array|string|null $data = null) {
        parent::__construct($message, $code);
        $this->data = $data;
    }

    public function getData(): array|string|null {
        return $this->data;
    }
}