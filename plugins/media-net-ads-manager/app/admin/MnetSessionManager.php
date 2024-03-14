<?php
namespace Mnet\Admin;

class MnetSessionManager
{
    public static function start()
    {
        if (!session_id()) {
            session_start();
        }
    }

    public static function set($data)
    {
        self::start();
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }


    public static function get($key)
    {
        self::start();
        return !empty($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function invalidate()
    {
        $_SESSION = array();
    }
}
