<?php

namespace App\Filesystem;

class Folder
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
            mkdir($this->path, $this->getPermission(), true);
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

    /**
     * Returns an array of directories or files
     */
    public function getItems(): array
    {
        $items = scandir($this->path);

        $return = [];
        foreach ($items as $item) {
            if ($item == "." || $item == "..") {
                continue;
            }

            $path = $this->path . '/' . $item;

            if (is_dir($path)) {
                $return[] = new Folder($path);
                continue;
            }

            $return[] = new File($path);
        }

        return $return;
    }

    public function getPermission()
    {
        if (!$this->exists()) {
            return '0777';
        }

        return '0777';
    }
}
