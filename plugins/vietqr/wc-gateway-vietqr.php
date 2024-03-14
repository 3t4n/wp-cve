<?php

/**
 * Plugin Name: VietQR
 * Plugin URI: https://casso.vn/plugin-vietqr-woocommerce/
 * Description:  Quick bank transfer by generating QR codes that are accepted by 37 Vietnam banking App: Vietcombank, Vietinbank, BIDV, ACB, VPBank, MBank, TPBank, Digimi, MSB ... Developed for WooCommerce.
 * Author: Casso Team
 * Author URI: https://casso.vn
 * Text Domain: vietqr
 * Domain Path: /languages
 * Version: 3.5.2
 * Tested up to: 6.0
 * License: GNU General Public License v3.0
 */


defined('ABSPATH') or exit;
define( 'WC_GATEWAY_VIETQR_VERSION', '3.5.2' ); 
define( 'WC_GATEWAY_VIETQR_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'WC_GATEWAY_VIETQR_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

// Make sure WooCommerce is active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    return;
}

//load plugin code
add_action('plugins_loaded', 'vietqr_gateway_init', 11);

function vietqr_gateway_init()
{
    require_once(plugin_basename('classes/class-wc-gateway-vietqr.php'));
}

//register vietqr gateway 
add_filter('woocommerce_payment_gateways', 'vietqr_add_gateways');
add_action('plugins_loaded', 'vietqr_load_plugin_textdomain');
// add_filter( 'auto_update_plugin', '__return_true' );
function vietqr_add_gateways($gateways)
{
    $gateways[] = 'WC_Gateway_VietQR';
    return $gateways;
}

add_action( 'woocommerce_blocks_loaded', 'woocommerce_vietqr_woocommerce_blocks_support' );

function woocommerce_vietqr_woocommerce_blocks_support() {
	if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
		require_once dirname( __FILE__ ) . '/classes/class-wc-gateway-vietqr-blocks-support.php';
		add_action(
			'woocommerce_blocks_payment_method_type_registration',
			function( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
				$payment_method_registry->register( new WC_VietQR_Blocks_Support );
			}
		);
	}
}

add_action( 'init', 'vietqr_add_settting');

function vietqr_add_settting(){
    if ( class_exists( 'WooCommerce' ) ) {
        // Add "Settings" link when the plugin is active
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ),'viet_qr_add_settings_link');
    }
}
function viet_qr_add_settings_link( $links ) {
    $settings = array( '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=vietqr' ) . '">' . __( 'Thiết lập', 'woocommerce' ) . '</a>' );
    $links    = array_reverse( array_merge( $links, $settings ) );

    return $links;
}
function vietqr_load_plugin_textdomain()
{
    load_plugin_textdomain('vietqr', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 

}