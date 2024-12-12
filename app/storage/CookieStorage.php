<?php

namespace App\Storage;

use StorageInterface;

class CookieStorage implements StorageInterface
{
    public function __construct()
    {
        
    }

    public function get(string $key)
    {
        return $_COOKIE[$key];
    }

    public function set(string $key, $data): bool
    {
        $_COOKIE[$key] = $data;
        return setcookie($key, $data, time() + 3600 * 24 * 30);
    }

    public function delete(string $key): bool
    {
        return setcookie($key, "", time() - 3600 * 24 * 30);
    }

    public function update(string $key, $data): bool
    {
        return $this->set($key, $data);
    }
}
