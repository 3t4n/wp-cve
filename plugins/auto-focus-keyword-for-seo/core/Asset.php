<?php
namespace Pagup\AutoFocusKeyword\Core;
use Pagup\AutoFocusKeyword\Core\Plugin;

class Asset 
{

    public static function style( $name, $file )
    {
        wp_register_style( $name, Plugin::url($file), array(), filemtime( Plugin::path($file) ) );

        wp_enqueue_style( $name );

    }

    public static function style_remote( $name, $file )
    {
        wp_register_style( $name, "{$file}" );

        wp_enqueue_style( $name );

    }

    public static function script( $name, $file, $array = [], $footer = false )
    {
        wp_register_script( $name, Plugin::url($file), $array, filemtime( Plugin::path($file) ), $footer );

        wp_enqueue_script( $name );

    }

    public static function script_remote( $name, $file, $array = [], $ver = false, $footer = false )
    {
        wp_register_script( $name, $file, $array, $ver, $footer );
        wp_enqueue_script( $name );
    }

}