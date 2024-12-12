<?php

namespace App\Logging;

use StorageInterface;

class StorageLogger extends AbstractLogger
{

    private StorageInterface $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function log($level, $message, $context = [])
    {
        $interpolate = $this->interpolate($message, $context);
        $this->storage->set('log', [
            "level" => $level,
            "message" => $interpolate,
            "time" => time(),
        ]);
    }
}