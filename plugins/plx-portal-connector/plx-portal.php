<?php

/**
 * Plugin Name: PLX Portal Connector
 * Description: Allows the Portal system to connect your WordPress site and provides some useful content management tools
 * Version: 2.0.2
 * Author: Purplex
 * Author URI: http://purplexmarketing.com
 * License: GPL3
 */

use PlxPortal\Plugin;

/*

												 TM
████████╗██╗     ███╗   ███╗
██╔═══██║██║      ███╗ ███╔╝
████████║██║       ██████╔╝
██╔═════╝██║      ███╔╝███╗
██║      ███████╗███╔╝  ███╗
╚═╝      ╚══════╝╚══╝   ╚══╝
    POWER YOUR WORDPRESS
       http://plx.mk

*/

if (!defined('ABSPATH')) {
    die;
}

define('PLX_PORTAL_PLUGIN', __FILE__);
define('PLX_PORTAL_PLUGIN_BASENAME', plugin_basename(PLX_PORTAL_PLUGIN));
define('PLX_PORTAL_PLUGIN_NAME', trim(dirname(PLX_PORTAL_PLUGIN_BASENAME), '/'));
define('PLX_PORTAL_PLUGIN_DIR', untrailingslashit(dirname(PLX_PORTAL_PLUGIN)));
define('PLX_PORTAL_PLUGIN_URL', untrailingslashit(plugins_url('', PLX_PORTAL_PLUGIN)));

// Function
require_once('includes/functions.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

// Autoloader classes
spl_autoload_register(function ($class) {
    if (false !== strpos($class, 'PlxPortal')) {
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        $class_file = PLX_PORTAL_PLUGIN_DIR . '/' . $class . '.php';

        if (file_exists($class_file)) {
            require $class_file;
        }
    }
});

$plugin = new PlxPortal\Config\PlxPortal();
