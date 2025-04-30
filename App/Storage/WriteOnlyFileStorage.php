<?php

namespace App\Storage;

use StorageInterface;

class WriteOnlyFileStorage extends AbstractWriteOnlyStorage implements StorageInterface
{
    private string $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function set(string $key, $data): bool
    {
        $this->createFile();
        file_put_contents($this->file, $data);
        return true;
    }

    protected function createFile()
    {
        $dir = dirname($this->file);
        if (!is_dir($dir)) {
            mkdir($dir, 777, true);
        }
    }
}
