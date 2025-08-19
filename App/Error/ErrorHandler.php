<?php

namespace App\Error;

use App\Http\HttpStatus;
use App\Http\ResponseFactory;

class ErrorHandler
{
    public function __construct()
    {
        set_error_handler([$this, 'handle'], E_ALL);
    }

    /**
     * Custom error handler.
     *
     * @param int    $severity Error severity level
     * @param string $message  Error message
     * @param string $file     File in which the error occurred
     * @param int    $line     Line number of the error
     * @return bool
     */
    public function handle(int $severity, string $message, string $file, int $line): bool
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        array_shift($trace); // remove current handle() call
        array_shift($trace); // remove internal handler wrapper

        $errorData = [
            'error' => [
                'severity' => $severity,
                'message'  => $message,
                'file'     => $file,
                'line'     => $line,
                'trace'    => $trace,
            ],
        ];

        $response = ResponseFactory::createResponse(
            json_encode($errorData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            HttpStatus::INTERNAL_SERVER_ERROR
        );

        $response->send();

        return true;
    }

    public function __destruct()
    {
        restore_error_handler();
    }
}
