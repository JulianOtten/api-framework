<?php

namespace App\Routing;

use App\Helpers\Helper;

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
        $files = scandir($path);
        foreach ($files as $file) {
            if ($file === "." || $file === "..") {
                continue;
            }

            $filePath = $path . "/" . $file;

            if (is_dir($filePath)) {
                self::loadFiles($filePath);
                continue;
            }

            // Helper::dd($file);
            if (strtolower($file) === "route.php") {
                require_once($filePath);
            }
        }
    }
}
