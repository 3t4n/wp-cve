<?php
/**
 * @package   	      WP E-Signature - WooCommerce
 * @contributors	  Kevin Michael Gray (Approve Me), Abu Shoaib (Approve Me)
 * @wordpress-plugin
 * Plugin Name:       WP E-Signature - WooCommerce by ApproveMe.com
 * Plugin URI:        http://aprv.me/2l9JldC
 * Description:       This add-on lets you require customers sign one (or more) legally binding contracts before they can complete their WooCommerce checkout process.
 * Version:           1.7.9
 * Author:            ApproveMe.com
 * Author URI:        http://aprv.me/2l9JldC
 * Text Domain:       esig-woocommerce
 * Domain Path:       /languages
 * License/Terms & Conditions: https://www.approveme.com/terms-conditions/
 * Privacy Policy: https://www.approveme.com/privacy-policy/
 * WC tested up to: 8.2
 * WC requires at least: 3.7
 */
// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('ESIGN_WOO_PATH', dirname(__FILE__));
define('ESIGN_WOO_URI', plugins_url("admin", __FILE__));

/* ----------------------------------------------------------------------------*
 * Public-Facing Functionality
 * ---------------------------------------------------------------------------- */
require_once(plugin_dir_path(__FILE__) . 'includes/class-esig-woo-signature.php');
require_once( plugin_dir_path(__FILE__) . 'includes/woocommerce-esig.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */

register_activation_hook(__FILE__, array('ESIG_WOOCOMMERCE', 'activate'));
register_deactivation_hook(__FILE__, array('ESIG_WOOCOMMERCE', 'deactivate'));


require_once( plugin_dir_path( __FILE__ ) . 'includes/esig-woocommerce-functions.php' );
require_once( plugin_dir_path(__FILE__) . 'admin/about/autoload.php' );

if (!function_exists('esig_is_plugin_active')) {

    function esig_is_plugin_active($plugin) {
        $network_active = false;
        if (is_multisite()) {
            $plugins = get_site_option('active_sitewide_plugins');
            if (isset($plugins[$plugin])) {
                $network_active = true;
            }
        }
        return in_array($plugin, get_option('active_plugins')) || $network_active;
    }

}

if (esig_is_plugin_active('woocommerce/woocommerce.php') || class_exists('WooCommerce')) {



    /**
     * Check if WooCommerce is active
     * */
    
    require_once( plugin_dir_path(__FILE__) . 'includes/class-esig-woocommerce-sad.php' );
    require_once( plugin_dir_path(__FILE__) . 'includes/class-hold-payment.php' );
    require_once(plugin_dir_path(__FILE__) . 'includes/esigWooFilters.php');
    require_once( plugin_dir_path(__FILE__) . 'admin/woocommerce-esig-admin.php' );
    require_once( plugin_dir_path(__FILE__) . 'admin/woocommerce-esig-shortcode.php' );
    require_once( plugin_dir_path(__FILE__) . 'admin/woo-data.php' );

    add_action('plugins_loaded', array('esigWooData', 'instance'));

    add_action('plugins_loaded', array('ESIG_WOOCOMMERCE_Admin', 'get_instance'));
    add_action('plugins_loaded', array('ESIG_WOOCOMMERCE_Shortcode', 'get_instance'));
    add_action('plugins_loaded', array('esig_hold_payment', 'get_instance'));

    
    require_once( plugin_dir_path( __FILE__ ) . 'admin/rating-widget/esign-rating-widget.php' );
    add_action( 'plugins_loaded', array( 'esignWoocommerceRatingWidget', 'get_instance' ) );


    /**
     * Load plugin textdomain.
     *
     * @since 1.1.3
     */
    function esig_commerce_load_textdomain() {

        load_plugin_textdomain('esig-woocommerce', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    add_action('plugins_loaded', 'esig_commerce_load_textdomain');
} else {

    add_action('plugins_loaded', array('ESIG_WOOCOMMERCE', 'get_instance'));
}

add_action('admin_enqueue_scripts', 'enqueue_woo_admin_about_scripts');
function enqueue_woo_admin_about_scripts() {
    $screen = get_current_screen();
    
    if (str_contains(esig_woocommerce_get("id",$screen), 'esign-woocommerce-about')){

       
        wp_enqueue_style( 'esig-woocommerce-google-fonts', 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@200;300;400;600;700;900&display=swap', false );
        wp_enqueue_script('esign-woocommerce-iframe-script', plugins_url('admin/assets/js/esign-iframe.js', __FILE__), array('jquery', 'jquery-ui-dialog'), '0.0.1', true);
        wp_enqueue_style( 'esig-woocommerce-snip-styles', plugins_url('admin/about/assets/css/esig-snip-styles.css', __FILE__), false, '0.0.1' );
        wp_enqueue_style( 'esig-woocommerce-about-css', plugins_url('admin/about/assets/css/esig-about.css', __FILE__), false, '0.0.1' );
    }
}


