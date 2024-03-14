<?php
defined('ABSPATH') or die('Exit');
/*
 * Plugin Name: Truepush Push Notifications
 * Plugin URI: https://www.truepush.com/
 * Description: Free web push notifications.
 * Version: 1.0.8
 * Author: Truepush  
 * Author URI: https://www.truepush.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
define('TRUEPUSH_URL', plugin_dir_url(__FILE__));
require_once plugin_dir_path(__FILE__).'truepush-public.php';
require_once plugin_dir_path(__FILE__).'truepush-main.php';
require_once plugin_dir_path(__FILE__).'truepush-active.php';

add_action('init', ['Truepush_Install', 'admin_css']);
add_action('init', ['Truepush_Initialize', 'init']);

