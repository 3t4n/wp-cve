<?php
/**
 * Plugin Name: Wishlist and Compare for WooCommerce
 * Description: Add wishlist and compare features to your woocommerce website.
 * Author:      ThemeHigh
 * Version:     1.3.1
 * Author URI:  https://www.themehigh.com
 * Plugin URI:  https://www.themehigh.com
 * Text Domain: wishlist-and-compare
 * Domain Path: /languages
 * WC requires at least: 6.0
 * WC tested up to: 8.0
 */

if (!defined('ABSPATH')) {
    die;
}

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    include_once dirname(__FILE__) . '/vendor/autoload.php';
    include_once dirname(__FILE__) . '/vendor/wp_namespace_autoloader.php';
    $autoloader = new WP_Namespace_Autoloader( array(    
        'directory'          => __DIR__, 
        'namespace_prefix'   => 'THWWC',  
        'classes_dir'        => 'inc',
    ) );
    $autoloader->init();
}

if (!function_exists('is_woocommerce_active')) {
    /** 
    * Function to check if woocommerce is active
    *
    * @return true
    */
    function is_woocommerce_active()
    {
        $active_plugins = (array) get_option('active_plugins', array());
        if (is_multisite()) {
            $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
        }
        return in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins);
    }
}
$active_plugins = (array) get_option('active_plugins', array());
if (is_woocommerce_active() && !in_array('wishlist-and-compare-pro/wishlist-and-compare-pro.php', $active_plugins)) {
    !defined('THWWC_VERSION') && define('THWWC_VERSION', '1.3.1');
    !defined('THWWC_SOFTWARE_TITLE') && define('THWWC_SOFTWARE_TITLE', 'Wishlist and Compare for WooCommerce');
    !defined('THWWC_FILE') && define('THWWC_FILE', __FILE__);
    !defined('THWWC_PATH') && define('THWWC_PATH', plugin_dir_path( __FILE__ ));
    !defined('THWWC_URL') && define('THWWC_URL', plugins_url( '/', __FILE__ ));
    !defined('THWWC_BASE_NAME') && define('THWWC_BASE_NAME', plugin_basename( __FILE__ ));
    /**
     * The code that runs during plugin activation.
     *
     * @return void
     */
    function thwwc_activate()
    {
        THWWC\base\THWWC_Activate::activate();
    }
    register_activation_hook(__FILE__, 'thwwc_activate');

    /**
     * The code that runs during plugin deactivation.
     *
     * @return void
     */
    function thwwc_deactivate()
    {
        THWWC\base\THWWC_Deactivate::deactivate();
    }
    register_deactivation_hook(__FILE__, 'thwwc_deactivate');

    if (class_exists('THWWC\\init')) {
        THWWC\Init::register_services();
    }

    add_action( 'before_woocommerce_init', 'thwwc_before_woocommerce_init_hpos' );
    function thwwc_before_woocommerce_init_hpos() {
        if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    }
}