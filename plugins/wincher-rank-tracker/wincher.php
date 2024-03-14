<?php
/*
Plugin Name: Wincher Rank Tracker
Plugin URI: https://wordpress.org/plugins/wincher-rank-tracker
Description: Free search engine ranking tool. Activate Wincher Rank Tracker to get a grip of your SEO and Google rankings today!
Version: 3.0.6
Author: Wincher
Author URI: https://www.wincher.com
*/

namespace Wincher;

if (!defined('WPINC')) {
    exit;
}

define('WINCHER_PLUGIN_BASE_PATH', plugin_dir_path(__FILE__));
define('WINCHER_PLUGIN_BASE_URL', plugin_dir_url(__FILE__));

// Require the composer autoloader
require WINCHER_PLUGIN_BASE_PATH . 'vendor/autoload.php';

$plugin = new Plugin();

register_activation_hook(__FILE__, [$plugin, 'activate']);
register_deactivation_hook(__FILE__, [$plugin, 'deactivate']);
