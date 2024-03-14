<?php
namespace Pagup\Pctag\Core;
use Pagup\Pctag\Core\Plugin;

class Asset 
{

    public static function style( $name, $file )
    {
        wp_register_style( $name, Plugin::url("admin/assets/{$file}"), array(), filemtime( Plugin::path("admin/assets/{$file}") ) );

        wp_enqueue_style( $name );

    }

    public static function style_remote( $name, $file )
    {
        wp_register_style( $name, "{$file}" );

        wp_enqueue_style( $name );

    }

    public static function script( $name, $file, $inc = [], $footer = false)
    {
        wp_register_script( $name, Plugin::url($file), $inc, filemtime( Plugin::path($file) ), $footer );

        wp_enqueue_script( $name );

    }

}