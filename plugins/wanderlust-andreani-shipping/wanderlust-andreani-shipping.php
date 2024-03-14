<?php
/*
	Plugin Name: Wanderlust Andreani para WooCommerce
	Plugin URI: https://wanderlust-webdesign.com/
	Description: Obtain shipping rates dynamically with Andreani API for your orders.
	Version: 2.0.204
	Author: Wanderlust Web Design
	Author URI: https://wanderlust-webdesign.com
	WC tested up to: 7.6.1
	Copyright: 2007-2023 wanderlust-webdesign.com.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * Plugin global API URL
*/
global $wp_session;

$wp_session['url_andreani'] = 'https://andreani.wanderlust-webdesign.com/';

require_once( 'includes/functions.php' );

/**
 * Plugin page links
*/
function wc_andreani_plugin_links( $links ) {

	$plugin_links = array(
		'<a href="http://wanderlust-webdesign.com/">' . __( 'Soporte', 'woocommerce-shipping-andreani' ) . '</a>',
	);

	return array_merge( $plugin_links, $links );
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wc_andreani_plugin_links' );

/**
 * WooCommerce is active
*/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	/**
	 * woocommerce_init_shipping_table_rate function.
	 *
	 * @access public
	 * @return void
	 */
	function wc_andreani_init() {
		include_once( 'includes/class-wc-shipping-andreani.php' );
	}
  add_action( 'woocommerce_shipping_init', 'wc_andreani_init' ); 

	/**
	 * wc_andreani_add_method function.
	 *
	 * @access public
	 * @param mixed $methods
	 * @return void
	 */
	function wc_andreani_add_method( $methods ) {
		$methods[ 'andreani_wanderlust' ] = 'WC_Shipping_Andreani';
		return $methods;
	}

	add_filter( 'woocommerce_shipping_methods', 'wc_andreani_add_method' );

	/**
	 * wc_andreani_scripts function.
	 */
	function wc_andreani_scripts() {
		wp_enqueue_script( 'jquery-ui-sortable' );
	}

	add_action( 'admin_enqueue_scripts', 'wc_andreani_scripts' );

	$andreani_settings = get_option( 'woocommerce_andreani_settings', array() );
	
}