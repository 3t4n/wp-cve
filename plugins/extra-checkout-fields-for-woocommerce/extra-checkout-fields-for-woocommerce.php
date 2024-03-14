<?php
/**
 * Plugin Name: Checkout Field Editor and Manager for WooCommerce
 * Plugin URI: https://themeparrot.com
 * Description: Helps to edit WooCommerce checkout fields, add custom checkout fields and more.
 * Version: 1.1.2
 * Author: Themeparrot
 * Author URI: https://themeparrot.com
 * Text Domain: extra-checkout-fields-for-woocommerce
 * Domain Path: /i18n/languages
 * Requires at least: 4.9.0
 * Requires PHP: 5.6
 * WC requires at least: 3.4.0
 * WC tested up to: 5.5.2
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// This plugin File
defined('ECFFW_PLUGIN_FILE') or define('ECFFW_PLUGIN_FILE', __FILE__);

// This plugin Name
defined('ECFFW_PLUGIN_NAME') or define('ECFFW_PLUGIN_NAME', 'Checkout Field Editor and Manager for WooCommerce');

// This plugin Version
defined('ECFFW_PLUGIN_VERSION') or define('ECFFW_PLUGIN_VERSION', '1.1.2');

require_once 'config.php'; // For define constants

// Autoload mapping for a PHP autoloader
require_once ECFFW_PLUGIN_PATH . '/vendor/autoload.php';

new ECFFW\App\Boot(); // Start plugin
