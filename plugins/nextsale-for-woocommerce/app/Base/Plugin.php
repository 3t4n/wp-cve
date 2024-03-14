<?php

namespace App\Base;

class Plugin
{
    public static $plugin_path;
    public static $plugin_url;
    public static $plugin;

    public function __construct()
    {
        self::$plugin_path = plugin_dir_path(realpath(__DIR__ . '/../../nextsale.php'));
        self::$plugin_url = plugin_dir_url(realpath(__DIR__ . '/../../nextsale.php'));
        self::$plugin = plugin_basename(realpath(__DIR__ . '/../../nextsale.php'));
    }
}
