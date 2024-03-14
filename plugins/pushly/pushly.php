<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @wordpress-plugin
 * Plugin Name:       Pushly
 * Plugin URI:        http://pushly.com
 * Description:       Provide Pushly push notification capability to WordPress installations
 * Version:           1.1.3
 * Author:            Pushly
 * Author URI:        http://pushly.com/
 * License:           GPLv2
 * Text Domain:       pushly
 */

defined('ABSPATH') or die('This page may not be accessed directly.');

define('PUSHLY_PLUGIN_URL_ROOT', plugin_dir_url(__FILE__));
define('PUSHLY_PLUGIN_PATH_ROOT', plugin_dir_path(__FILE__));

require_once PUSHLY_PLUGIN_PATH_ROOT . 'classes/pushly-admin.php';
require_once PUSHLY_PLUGIN_PATH_ROOT . 'classes/pushly-public.php';

add_action('init', array('Pushly_Admin', 'init'));
add_action('init', array('Pushly_Public', 'init'));
