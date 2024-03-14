<?php
namespace Pagup\Pctag\Core;

class Plugin
{
    public static function url($filePath)
    {
        return plugins_url('', __DIR__ ) . "/{$filePath}";
    }

    public static function path($filePath)
    {
        return plugin_dir_path( __DIR__ ) . "{$filePath}";
    }

    public static function view($file, $data = [])
    {
        extract($data);
        require static::path("admin/views/{$file}.view.php");
    }

    public static function dd()
    {
        array_map(function($x) { 
            var_dump($x); 
        }, func_get_args());
        die;
    }
}