<?php
/*
Plugin Name: Order date, Order pickup, Order date time, Pickup Location, delivery date  for WooCommerce
Plugin URI: https://woo-restaurant.com/
Description: Set order delivery date, time and type for WooCommerce
Version: 3.0.49
Author: PI Websolution
Author URI: piwebsolution.com
Text Domain: pisol-dtt
WC tested up to: 8.6.0
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if(is_plugin_active( 'pi-woocommerce-order-date-time-and-type-pro/pi-woocommerce-order-date-time-and-type-pro.php')){
    
    function pisol_dtt_pro_error_notice() {
        ?>
        <div class="error notice">
            <p><?php _e( 'You have the pro version of PI WooCommerce order date time and type', 'pisol-dtt' ); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'pisol_dtt_pro_error_notice' );
    deactivate_plugins(plugin_basename(__FILE__));
    return;
}

/* 
    Making sure WooCommerce is there 
*/
if(!is_plugin_active( 'woocommerce/woocommerce.php')){
    function pisol_dtt_woocommerce_error_notice() {
        ?>
        <div class="error notice">
            <p><?php _e( 'Please Install and Activate WooCommerce plugin, without that this plugin cant work', 'pisol-dtt' ); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'pisol_dtt_woocommerce_error_notice' );
    deactivate_plugins(plugin_basename(__FILE__));
    return;
}

/**
 * Declare compatible with HPOS new order table 
 */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

define('PISOL_DTT_PLUGIN_VERSION', '3.0.49');
define('PISOL_DTT_FREE_RESET_SETTING', false);
define('PISOL_DTT_URL', plugin_dir_url(__FILE__));
define('PISOL_DTT_PATH', plugin_dir_path( __FILE__ ));
define('PISOL_DTT_BASE', plugin_basename(__FILE__));
define('PISOL_DTT_PRICE', '$34 Only');
define('PISOL_DTT_BUY_URL', 'https://www.piwebsolution.com/cart/?add-to-cart=621&variation_id=15710#order_review');

function pisol_dtt_plugin_link( $links ) {
	$links = array_merge( array(
        '<a href="' . esc_url( admin_url( '/admin.php?page=pisol-dtt' ) ) . '">' . __( 'Settings' ) . '</a>',
        '<a style="color:#0a9a3e; font-weight:bold;" target="_blank" href="' . esc_url(PISOL_DTT_BUY_URL) . '">' . __( 'Buy PRO Version', 'pisol-dtt' ) . '</a>'
	), $links );
	return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'pisol_dtt_plugin_link' );

/** 
 * Activation function to redirect to setting page
 * 
 * */
register_activation_hook( __FILE__, 'pi_free_order_date_time_activation' );

function pi_free_order_date_time_activation(){
    add_option('pi_dtt_do_activation_redirect', true);
}

add_action('admin_init', 'pi_free_order_date_time_redirect');
function pi_free_order_date_time_redirect(){
    if (get_option('pi_dtt_do_activation_redirect', false)) {
        delete_option('pi_dtt_do_activation_redirect');
        if(!isset($_GET['activate-multi']))
        {
            wp_redirect("admin.php?page=pisol-dtt");
        }
    }
}

if ( ! function_exists( 'pisol_dtt_plugins_loaded' ) ) {
function pisol_dtt_plugins_loaded() {
    load_plugin_textdomain( 'pisol-dtt', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'pisol_dtt_plugins_loaded', 0 );
}



/**
 * Checking Pro version
 */
function pi_dtt_pro_check(){
	return false;
}


/* This adds menu link */
require_once plugin_dir_path( __FILE__ ).'include/includes.php';



