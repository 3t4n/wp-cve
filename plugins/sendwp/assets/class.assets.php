<?php

namespace SendWP;

class Assets
{
    public static $base_url;
    public static $base_path;

    public static function set_base_url( $base_url )
    {
        self::$base_url = $base_url;
    }

    public static function set_base_path( $base_path )
    {
        self::$base_path = $base_path;
    }

    public static function image_url($filename)
    {
        return self::$base_url . "img/$filename";
    }
}