<?php

/**
 * Plugin Name: Nextsale for WooCommerce
 * Plugin URI: https://nextsale.io/wordpress-plugin
 * Description: All-in-one Growth Management Platform that helps marketers to increase website conversions by providing Social Proof & Urgency tools.
 * Author: Nextsale
 * Author URI: https://nextsale.io
 * Version: 1.0.8
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: nextsale
 * Requires PHP: 5.6
 * WC requires at least: 3.0.0
 * WC tested up to: 6.3.1
 */

// Block direct access
defined("ABSPATH") or die;

// Load vendor autoload
require __DIR__ . '/vendor/autoload.php';

// Config
$config = require __DIR__ . '/config.php';
foreach ($config as $key => $value) {
    putenv(strtoupper($key) . '=' . $value);
}

// Register activate and deactivate hooks
register_activation_hook(__FILE__, [App\Base\Activate::class, 'invoke']);
register_deactivation_hook(__FILE__, [App\Base\Deactivate::class, 'invoke']);

// Register plugin services
if (class_exists('App\\Init')) {
    App\Init::registerServices();
}
