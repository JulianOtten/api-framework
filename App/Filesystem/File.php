<?php

namespace App\Filesystem;

class File
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function exists(): bool
    {
        return is_dir($this->path);
    }

    public function create()
    {
        if (!$this->exists()) {
            $dir = new Folder(dirname($this->path));
            $dir->create();
            $this->write("");
        }

        return $this;
    }

    public function delete()
    {
        rmdir($this->path);

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function read()
    {
        return file_get_contents($this->path);
    }

    public function clear()
    {
        $this->write("", false);
        return $this;
    }

    public function write($data, $append = true)
    {
        file_put_contents($this->path, $data, $append ? FILE_APPEND : 0);
        return $this;
    }

    public function getPermission()
    {
        if (!$this->exists()) {
            return '0777';
        }

        return '0777';
    }
}
