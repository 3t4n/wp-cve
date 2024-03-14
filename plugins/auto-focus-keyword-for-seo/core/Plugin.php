<?php

namespace Pagup\AutoFocusKeyword\Core;

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
        require realpath(plugin_dir_path( __DIR__ ) . "admin/views/{$file}.view.php");
    }

    public static function addon()
    {
        if (
            is_plugin_active('tiktok-booster/tiktok-booster.php') || 
            is_plugin_active('tiktok-booster-premium/tiktok-booster.php') || 
            is_plugin_active('tiktok-booster-freemius/tiktok-booster.php') 
        ) {
            return true;
        } else {
            return false;
        }
    }
    
    public static function domain()
    {
        return "auto-focus-keyword-for-seo";
    }

    public static function dd()
    {
        array_map(function($x) { 
            var_dump($x); 
        }, func_get_args());
        die;
    }
}