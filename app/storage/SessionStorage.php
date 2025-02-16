<?php

namespace App\Storage;

use App\Storage\Session\SessionManager;
use StorageInterface;

class SessionStorage implements StorageInterface
{
    public function __construct()
    {
    }

    public function get(string $key)
    {
        return SessionManager::get($key);
    }

    public function set(string $key, $data): bool
    {
        return SessionManager::set($key, $data);
    }

    public function delete(string $key): bool
    {
        return SessionManager::delete($key);
    }

    public function update(string $key, $data): bool
    {
        return SessionManager::update($key, $data);
    }
}
