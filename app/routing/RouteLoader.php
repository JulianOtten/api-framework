<?php

namespace App\Routing;

use App\Filesystem\File;
use App\Filesystem\Folder;
use App\Helpers\Helper;
use Directory;

class RouteLoader
{
    public static function load()
    {
        new RouteLoader();
    }

    public function __construct()
    {
        return $this->loadFiles(ROOT . '/routes');
    }

    public function loadFiles($path)
    {
        $dir = new Folder($path);
        foreach($dir->getItems() as $item) 
        {
            if($item instanceof File) {
                require_once $item->getPath();
                continue;
            }

            self::loadFiles($item->getPath());
        }
    }
}
