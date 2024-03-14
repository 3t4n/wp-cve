<?php
/**
 * Plugin name: WooCommerce Category Discount
 * Plugin URI: http://www.flycart.org
 * Description: Simple plugin to apply discount for woocommerce categories
 * Author: Flycart Technologies LLP
 * Author URI: https://www.flycart.org
 * Version: 1.0.4
 * Text Domain: woocommerce-category-discount
 * Slug: wc-category-discount
 * Domain Path: /i18n/languages/
 * Plugin URI: http://www.flycart.org
 * Requires at least: 4.6.1
 * WC requires at least: 2.5
 * WC tested up to: 3.5
 */

namespace Wcd;
if (!defined('ABSPATH')) exit;

/**
 * Define the text domain
 */
if (!defined('WCD_TEXT_DOMAIN'))
    define('WCD_TEXT_DOMAIN', 'woocommerce-category-discount');

/**
 * Current version of our app
 */
if (!defined('WCD_VERSION'))
    define('WCD_VERSION', '1.0.4');

/**
 * Check and abort if PHP version is is less them 5.6 and does not met the required woocommerce version
 */
register_activation_hook(__FILE__, function () {
    if (version_compare(phpversion(), '5.6', '<')) {
        exit(__('Woocommerce category discount requires minimum PHP version of 5.6', RNOC_TEXT_DOMAIN));
    }
    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        exit(__('Woocommerce must installed and activated in-order to use Woocommerce category discount!', RNOC_TEXT_DOMAIN));
    } else {
        if (!function_exists('get_plugins'))
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        $plugin_folder = get_plugins('/' . 'woocommerce');
        $plugin_file = 'woocommerce.php';
        $wc_installed_version = NULL;
        $wc_required_version = '2.5';
        if (isset($plugin_folder[$plugin_file]['Version'])) {
            $wc_installed_version = $plugin_folder[$plugin_file]['Version'];
        }
        if (version_compare($wc_required_version, $wc_installed_version, '>=')) {
            exit(__('Woocommerce category discount requires minimum Woocommerce version of ', RNOC_TEXT_DOMAIN) . ' ' . $wc_required_version . '. ' . __('But your Woocommerce version is ', RNOC_TEXT_DOMAIN) . ' ' . $wc_installed_version);
        }
    }
});
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    wp_die('Unable to find packages!');
}
require __DIR__ . '/vendor/autoload.php';

/**
 * Set base file URL
 */
if (!defined('WCD_BASE_FILE'))
    define('WCD_BASE_FILE', plugin_basename(__FILE__));
if (!defined('WCD_PLUGINS_URL'))
    define('WCD_PLUGINS_URL', plugin_dir_url(__FILE__));

use Wcd\DiscountRules\Main;

Main::instance();
