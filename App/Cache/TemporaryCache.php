<?php

namespace App\Cache;

class TemporaryCache
{
    private static $cache = [];

    public static function set($name, $data)
    {
        static::$cache[$name] = $data;
    }

    public static function get($name)
    {
        return static::$cache[$name] ?? false;
    }
}
