<?php

namespace App\Storage\Session;

class SessionManager
{
    public static function get($key)
    {
        return $_SESSION[$key];
    }

    public static function set($key, $data)
    {
        $_SESSION[$key] = $data;
        return true;
    }

    public static function delete($key)
    {
        unset($_SESSION[$key]);
        return true;
    }

    public static function update($key, $data)
    {
        return self::set($key, $data);
    }
}
