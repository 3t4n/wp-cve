<?php
/**
* Plugin Name: Waitlist woocommerce( Back in stock notifier )
* Plugin URI: http://xootix.com/waitlist-for-woocommerce
* Version: 2.5.3
* Text Domain: waitlist-woocommerce
* Domain Path: /languages
* Author URI: http://xootix.com
* Description: Send notification email to users when product arrives in stock.
* Tags: out of stock, back in stock, out of stock notifier
*/

//Exit if accessed directly
if(!defined('ABSPATH')){
	return;
}


if ( ! defined( 'XOO_WL_PLUGIN_FILE' ) ) {
	define( 'XOO_WL_PLUGIN_FILE', __FILE__ );
}

/**
 * Initialize
 *
 * @since    1.0.0
 */
function xoo_wl_free_init(){

	if( !class_exists( 'woocommerce' ) ) return;
	
	do_action('xoo_wl_before_plugin_activation');

	if ( ! class_exists( 'Xoo_Wl' ) ) {
		require 'includes/class-xoo-wl.php';
	}

	xoo_wl();
	
}
add_action( 'plugins_loaded','xoo_wl_free_init', 10 );


function xoo_wl_deactivate_pro(){
	deactivate_plugins( 'waitlist-woocommerce-premium/xoo-wl-main.php' );
}
register_activation_hook( __FILE__, 'xoo_wl_deactivate_pro' );


if( !function_exists( 'xoo_wl' ) ){
	function xoo_wl(){
		return Xoo_Wl::get_instance();
	}
}