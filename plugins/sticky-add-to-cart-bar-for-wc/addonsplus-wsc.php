<?php
/**
 * @package  WooCart
 */
/*
Plugin Name: Sticky Add To Cart Bar For WooCommerce
Plugin URI: https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/
Description: Plugin that add sticky Add To Cart Bar on product page. It supports variable product with ajax add to cart feature. Grab visitors attention and increase the conversion using Sticky Add To Cart Bar For WooCommerce.
Version: 1.4.6
Author: addonsplus
Requires at least: 4.8
Tested up to: 5.9
WC requires at least: 3.2
WC tested up to: 5.9.3
Author URI: https://addonsplus.com/
Text Domain: addonsplus-wsc
*/

// If this file is called Directly, abort!!!
defined( 'ABSPATH' ) or die( 'Hey, You can not access...' );

// Require once the Composer Autoload
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/**
 * The code that runs during plugin activation
 */
function activate_woocart_plugin() {
  // check dependency of other plugins
	if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
     include_once( ABSPATH . '/wp-admin/includes/plugin.php' );
 }
 if ( current_user_can( 'activate_plugins' ) && ! class_exists( 'WooCommerce' ) ) {
    // Custom Error Message
    add_action( 'admin_notices', 'wsc_woo_admin_notice__error' );
}

WscInc\Base\Activate::activate();
}

// Hook to activate the plugin
register_activation_hook( __FILE__, 'activate_woocart_plugin' );

/**
 * The code that runs during plugin deactivation
 */
function deactivate_woocart_plugin() {
	WscInc\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_woocart_plugin' );

/**
 * Initialize all the core classes of the plugin
 */
if ( class_exists( 'WscInc\\Init' ) ) {
	WscInc\Init::registerServices();
}

// Check status of Any depended plugin (Ex. WooCommerce) when dashboard is loaded and it will automatically deactivate the plugin if that plugin is not available

add_action( 'admin_init' , 'check_plugin' );

function check_plugin(){
    // Check woocommerce is active or not
	if(! class_exists( 'WooCommerce' )){
    add_action( 'admin_notices', 'wsc_woo_admin_notice__error' );
	}
}

function wsc_woo_admin_notice__error() {
  echo '<div class="notice notice-error"><p>Sticky Add To Cart Bar For WooCommerce is enabled but not effective. It requires WooCommerce in order to work.</p></div>';
}

function ask_review_wsc_plugin(){
  if(get_option('dismissed-wsc-notice-date')){
    $datetime1 = new DateTime();
    $datetime2 = get_option('dismissed-wsc-notice-date');
    $interval = date_diff( $datetime2, $datetime1 );
  }
  if ( ! get_option('dismissed-wsc-notice', FALSE ) || $interval->format('%a') > 14) { 
    echo '<div class="notice notice-success notice-wsc-class is-dismissible">
      <div style="margin-top: 15px; margin-bottom: 15px;">Sticky Add To Cart Bar For WooCommerce need your support to keep updating and improving the plugin. <a target="_blank" href="https://wordpress.org/support/plugin/sticky-add-to-cart-bar-for-wc/reviews/?filter=5#new-post"><b>Please, help us by leaving review <span class="dashicons  dashicons-star-filled" style="color:#dba617"></span><span class="dashicons  dashicons-star-filled" style="color:#dba617"></span><span class="dashicons  dashicons-star-filled" style="color:#dba617"></span><span class="dashicons  dashicons-star-filled" style="color:#dba617"></span><span class="dashicons  dashicons-star-filled" style="color:#dba617"></span></a></b> Thanks!</div>
    </div>';
  }
}
add_action('admin_notices', 'ask_review_wsc_plugin');

function ajax_notice_handler() {
    update_option( 'dismissed-wsc-notice', TRUE );
    update_option( 'dismissed-wsc-notice-date', new DateTime());
}

add_action( 'wp_ajax_dismissed_wsc_notice_handler','ajax_notice_handler' );