<?php

namespace App\Storage;

use StorageInterface;

/**
 * Write only storage should do exactly as it says, Only perform write actions.
 * These actions are meant to be a write and forget implementation,
 * like logging information to a file or log table in a database.
 *
 * data retrieving SHOULD always return false, and data manipulation SHOULD always return true.
 */
abstract class AbstractWriteOnlyStorage implements StorageInterface
{
    abstract public function set(string $key, $data): bool;

    public function get(string $key)
    {
        return false;
    }

    public function delete(string $key): bool
    {
        return false;
    }

    public function update(string $key, $data): bool
    {
        return false;
    }
}
