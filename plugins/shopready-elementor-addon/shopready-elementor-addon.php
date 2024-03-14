<?php

/**
 * Plugin Name: ShopReady - WooCommerce Builder
 * Description: WooCommerce Builder for Elementor, Products Compare, UpSell, Variation Swatches, Wishlist, QuickView
 * Plugin URI: 	https://profiles.wordpress.org/quomodosoft
 * Version: 	3.3
 * Requires at least: 5.5
 * Tested up to: 6.4.1
 * Author: 		quomodosoft
 * Author URI: 	http://quomodosoft.com
 * License:  	apache-2.0+
 * License URI: http://www.apache.org/licenses/LICENSE-2.0
 * Text Domain: shopready-elementor-addon
 * Domain Path: /languages
 * Elementor tested up to: 3.17.3
 */

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

if (defined('SHOP_READY')) {
    /**
     * The plugin was already loaded (maybe as another plugin with different directory name)
     */
} else {

    require __DIR__ . '/vendor/autoload.php';

    /*
     **
     *** 
     *** 1. Used for security
     *** 2. Used to help know where we are on the filesystem.
     *** 
     **
     */

    define('SHOP_READY', true);
    define('SHOP_READY_VERSION', '2.4');
    define('SHOP_READY_ASSET_MINIMIZE', true); // uncomment for minimize version 
    define('SHOP_READY_LITE', true);
    define('SHOP_READY_ROOT', __FILE__);
    define('SHOP_READY_URL', plugins_url('/', SHOP_READY_ROOT));
    define('SHOP_READY_DIR_PATH', plugin_dir_path(SHOP_READY_ROOT));
    define('SHOP_READY_ADDONS_DIR_URL', SHOP_READY_URL . 'src/extension');
    define('SHOP_READY_ADDONS_DIR_PATH', SHOP_READY_DIR_PATH . 'src/extension');
    define('SHOP_READY_PLUGIN_BASE', plugin_basename(SHOP_READY_ROOT));
    define('SHOP_READY_ITEM_NAME', esc_html__('ShopReady', 'shopready-elementor-addon'));
    define('SHOP_READY_PUBLIC_ROOT_IMG', SHOP_READY_URL . 'assets/public/images/');
    define('SHOP_READY_PUBLIC_ROOT_JS', SHOP_READY_URL . 'assets/public/js/');
    define('SHOP_READY_PUBLIC_ROOT_CSS', SHOP_READY_URL . 'assets/public/css/');
    define('SHOP_READY_DEMO_URL', 'https://quomodosoft.com/plugins/shopready/');
    define('SHOP_READY_SETTING_PATH', 'shop-ready-elements-dashboard');
    /*
     ****
     ***** Now let's include the bootloader file
     ****
     */
    add_action('plugins_loaded', 'shop_ready_action_init_src', 100);
    function shop_ready_action_init_src()
    {
        static $loader_count = 0;
        load_plugin_textdomain('shopready-elementor-addon');
        do_action('shop_ready_before_bootstrap');
        if (!$loader_count) {
            require SHOP_READY_DIR_PATH . '/src/system/boot.php';
            require SHOP_READY_DIR_PATH . '/src/extension/init.php';
        }
        $loader_count++;

        do_action('shop_ready_after_bootstrap');
    }
    register_activation_hook(__FILE__, function () {
        require SHOP_READY_DIR_PATH . '/src/system/activate.php';
    });

    register_deactivation_hook(__FILE__, 'shop_ready_register_deactivation_hook');

    function shop_ready_register_deactivation_hook()
    {
        wp_clear_scheduled_hook('qs_partial_payment_woocommerce_send_email_digest');
    }
}
