<?php

namespace \App\Storage;

interface StorageInterface
{
    public function __construct();
    public function get(string $key);
    public function set(string $key, $data): bool;
    public function delete(string $key): bool;
    public function update(string $key, $data): bool;
}