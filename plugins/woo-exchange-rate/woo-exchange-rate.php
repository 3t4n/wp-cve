<?php
/*
 * Plugin Name: Woo Exchange Rate
 * Description: Allows to add exchange rates for WooCommerce 
 * 
 * Text Domain: woo-exchange-rate
 * 
 * Version: 17.4
 * Author: Pavel Kolomeitsev
 * License: https://opensource.org/licenses/GPL-2.0 GNU Public License
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

//Help handle assets
define('WOOER_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Includes autoloader
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Check if woocommerce plugin is enabled
 */
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    add_action('admin_notices', 'wooer_startup_error');
    return false;
}

/**
 * Plugin setup hooks
 */
register_activation_hook(__FILE__, 'wooer_install');
register_uninstall_hook(__FILE__, 'wooer_uninstall');


use WOOER\Main;
return new Main();