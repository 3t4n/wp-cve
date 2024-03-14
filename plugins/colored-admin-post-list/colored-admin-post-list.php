<?php

/*
* Plugin Name: Colored Admin Post List
* Plugin URI: http://wordpress.org/plugins/colored-admin-post-list/
* Description: Highlights the background of draft, pending, future, private, and published posts in the wordpress admin. Also supports custom post statuses!
* Author: rockschtar
* Author URI: http://www.eracer.de
* Version: 3.0.3
* Requires at least: 6.2
* Requires PHP: 8.0
* License: MIT
* Text Domain: colored-admin-post-list
* Domain Path: /languages
*/

use Rockschtar\WordPress\ColoredAdminPostList\Controller\PluginController;

define("CAPL_PLUGIN", plugin_basename(__FILE__));
define("CAPL_PLUGIN_DIR", plugin_dir_path(__FILE__));
define("CAPL_PLUGIN_URL", plugin_dir_url(__FILE__));
define("CAPL_PLUGIN_RELATIVE_DIR", dirname(plugin_basename(__FILE__)));
const CAPL_PLUGIN_FILE = __FILE__;

spl_autoload_register(static function ($class) {
    $namespace = 'Rockschtar\\WordPress\\ColoredAdminPostList\\';
    if (!str_starts_with($class, $namespace)) {
        return;
    }

    $class = str_replace($namespace, '', $class);
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $class . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

PluginController::init();
