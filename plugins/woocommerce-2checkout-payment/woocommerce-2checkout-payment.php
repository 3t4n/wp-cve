<?php 
/*
 Plugin Name: WooCommerce 2Checkout Payment Gateway Version Basic
Plugin URI: https://najeebmedia.com/wordpress-plugin/woocommerce-2checkout-payment-gateway-with-inline-support/
Description: WooCommerce 2Checkout Gateway with Inline Support
Version: 6.2
Author: N-Media
WC requires at least: 3.0.0
WC tested up to: 4.4.1
Author URI: http://www.najeebmedia.com/
*/


define('TWOCO_PATH', untrailingslashit(plugin_dir_path( __FILE__ )) );
define('TWOCO_URL', untrailingslashit(plugin_dir_url( __FILE__ )) );


include TWOCO_PATH.'/inc/functions.php';

add_action( 'plugins_loaded', 'twoco_gateway', 0);
function twoco_settings( $links ) {
    $payment_url    = admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_gateway_nm_twocheckout' );
    $payment_title  = __('Payment Settings', 'twoco');
    $update_pro     = __('Upgrade PRO', 'twoco');
    $settings_link  = sprintf(__('<a href="%s">%s</a>','twoco'), $payment_url, $payment_title);
    $video_url      = 'https://najeebmedia.com/wc-2co';
    $video_guide  = sprintf(__('<a target="_blank" href="%s">%s</a>','twoco'), $video_url, $update_pro);
  	array_push( $links, $settings_link, $video_guide );
  	return $links;
}

$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'twoco_settings' );


function twoco_gateway(){

    $gateway_settings = get_option('woocommerce_nmwoo_2co_settings');
    include plugin_dir_path(__FILE__).'inc/twoco.form.php';
	
}


function twoco_add_payment_gateway( $methods ) {
	$methods[] = 'WC_Gateway_NM_TwoCheckout';
	return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'twoco_add_payment_gateway' );