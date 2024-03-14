<?php
/**
 *
 *   Plugin Name:  Contribuinte Checkout
 *   Description:  Add VAT information to your orders
 *   Version:      1.0.50
 *   Tested up to: 6.2.0
 *   WC tested up to: 7.5.1
 *
 *   Author:       moloni.pt
 *   Author URI:   https://moloni.pt
 *   License:      GPL2
 *   License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 *   Text Domain:  contribuinte-checkout
 *   Domain Path:  /languages
 */

namespace Checkout\Contribuinte;

//Deny direct access
if (!defined('ABSPATH')) {
    exit;
}

//Starts autoloader, gets namespaces
$composer_autoloader = __DIR__ . '/vendor/autoload.php';
if (is_readable($composer_autoloader)) {
    /** @noinspection PhpIncludeInspection */
    require $composer_autoloader;
}

//Define file variable to be used to load translations
if (!defined('CONTRIBUINTE_CHECKOUT_PLUGIN_FILE')) {
    define('CONTRIBUINTE_CHECKOUT_PLUGIN_FILE', __FILE__);
}

//Register installation hook to run Install class static method (run())
register_activation_hook(__FILE__, 'Checkout\Contribuinte\Activators\Install::run');

//Start this plugin
add_action('plugins_loaded', 'Checkout\Contribuinte\Plugin::init');
