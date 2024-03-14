<?php

namespace XCurrency\WpMVC\View;

\defined('ABSPATH') || exit;
use XCurrency\WpMVC\App;
class View
{
    public static function render(string $file, array $args = []) : void
    {
        \extract($args);
        include static::get_path($file);
    }
    public static function get(string $file, array $args = []) : string
    {
        \ob_start();
        \extract($args);
        include static::get_path($file);
        return \ob_get_clean();
    }
    public static function get_path(string $file) : string
    {
        if (empty(\pathinfo($file)['extension'])) {
            $file .= '.php';
        }
        $file = \ltrim($file, '/');
        return App::get_dir("resources/views/{$file}");
    }
}
