<?php
/*
Plugin Name: iThemeland WooCommerce Bulk Orders Editing Lite
Plugin URI: https://ithemelandco.com/plugins/wordpress-bulk-orders-editing
Description: Editing Date in WordPress is very painful. Be professionals with managing data in the reliable and flexible way by WooCommerce Bulk Order Editor.
Author: iThemelandco
Tested up to: WP 5.3
Requires PHP: 5.4
Tags: woocommerce,woocommerce bulk edit,bulk edit,bulk,orders bulk editor
Text Domain: ithemeland-woocommerce-bulk-orders-editing-lite
Domain Path: /languages
WC requires at least: 3.3.1
WC tested up to: 3.8
Version: 2.2.2
Author URI: https://www.ithemelandco.com
*/

defined('ABSPATH') || exit();

if (defined('WOBEL_NAME')) {
    return false;
}

require_once __DIR__ . '/vendor/autoload.php';

define('WOBEL_NAME', 'ithemeland-woocommerce-bulk-orders-editing-lite');
define('WOBEL_LABEL', 'iThemeland WooCommerce Bulk Orders Editing Lite');
define('WOBEL_DESCRIPTION', 'Be professionals with managing data in the reliable and flexible way!');
define('WOBEL_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('WOBEL_PLUGIN_MAIN_PAGE', admin_url('admin.php?page=wobel'));
define('WOBEL_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('WOBEL_LIB_DIR', trailingslashit(WOBEL_DIR . 'classes/lib'));
define('WOBEL_VIEWS_DIR', trailingslashit(WOBEL_DIR . 'views'));
define('WOBEL_LANGUAGES_DIR', dirname(plugin_basename(__FILE__)) . '/languages/');
define('WOBEL_ASSETS_DIR', trailingslashit(WOBEL_DIR . 'assets'));
define('WOBEL_ASSETS_URL', trailingslashit(WOBEL_URL . 'assets'));
define('WOBEL_CSS_URL', trailingslashit(WOBEL_ASSETS_URL . 'css'));
define('WOBEL_IMAGES_URL', trailingslashit(WOBEL_ASSETS_URL . 'images'));
define('WOBEL_JS_URL', trailingslashit(WOBEL_ASSETS_URL . 'js'));
define('WOBEL_VERSION', '2.2.2');
define('WOBEL_UPGRADE_URL', 'https://ithemelandco.com/plugins/woocommerce-bulk-orders-editing?utm_source=free_plugins&utm_medium=plugin_links&utm_campaign=user-lite-buy');
define('WOBEL_UPGRADE_TEXT', 'Download Pro Version');

register_activation_hook(__FILE__, ['wobel\classes\bootstrap\WOBEL', 'activate']);
register_deactivation_hook(__FILE__, ['wobel\classes\bootstrap\WOBEL', 'deactivate']);

add_action('init', ['wobel\classes\bootstrap\WOBEL', 'wobel_wp_init']);

add_action('plugins_loaded', function () {
    if (!class_exists('WooCommerce')) {
        wobel\classes\bootstrap\WOBEL::wobel_woocommerce_required();
    } else {

        \wobel\classes\bootstrap\WOBEL::init();
    }
});
