<?php
/*
Plugin Name: Fluent Connect
Description: Connect FluentCRM with ThriveCart
Version: 0.3
Author: wpmanageninja
Author URI: https://wpmanageninja.com
Plugin URI: https://github.com/WPManageNinja/fluent-connect
License: GPLv2 or later
Text Domain: fluent-connect
Domain Path: /language
*/


defined('ABSPATH') or die;

define('FLUENT_CONNECT_PLUGIN_VERSION', '0.3');
define('FLUENT_CONNECT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FLUENT_CONNECT_PLUGIN_PATH', plugin_dir_path(__FILE__));

require __DIR__.'/vendor/autoload.php';

call_user_func(function($bootstrap) {
    $bootstrap(__FILE__);
}, require(__DIR__.'/boot/app.php'));
