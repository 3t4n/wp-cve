<?php
/**
 * Plugin Name:     Ship to a different address checked unchecked
 * Plugin URI:      https://profiles.wordpress.org/nareshparmar827/
 * Description:     WooCommerce – Ship to a different address checked or unchecked
 * Author:          Naresh Parmar
 * Author URI:      https://profiles.wordpress.org/nareshparmar827/
 * Donate link:     https://paypal.me/NARESHBHAIPARMAR?locale.x=en_GB
 * Text Domain:     ship-to-a-different-address-checked-unchecked
 * Domain Path:     /languages
 * Version:         1.0
 * @package         WP_NGD_Ship_Different_Address_Checked_Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'includes/class-' . basename( __FILE__ );

/**
 * Plugin textdomain.
 */
function wp_ngd_ship_to_a_different_address_textdomain() {
	load_plugin_textdomain( 'ship-to-a-different-address-checked-unchecked', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'wp_ngd_ship_to_a_different_address_textdomain' );

/**
 * Plugin activation.
 */
function wp_ngd_ship_to_a_different_address_activation() {
	// If check pro plugin activated or not.
	if( ! class_exists( 'WooCommerce' ) ) {
		// Activate WooCommerce plguin.
		deactivate_plugins( plugin_basename( __FILE__ ) );
		// Display error message.
		wp_die( __( 'Please activate WooCommerce', 'ship-to-a-different-address-checked-unchecked' ), 'Plugin dependency check',
			array(
				'back_link' => true,
			)
		);
	}else{

		// Activation code here.
		$option_name = 'wp_ngd_ship_option_checked' ;
		$new_value = '1';
		 
		if ( get_option( $option_name ) !== false ) {
		 
		    // The option already exists, so update it.
		    update_option( $option_name, $new_value );
		 
		} else {
		 
		    // The option hasn't been created yet, so add it with $autoload set to 'no'.
		    $deprecated = null;
		    $autoload = 'no';
		    add_option( $option_name, $new_value, $deprecated, $autoload );
		}
	}

}
register_activation_hook( __FILE__, 'wp_ngd_ship_to_a_different_address_activation' );

/**
 * Plugin deactivation.
 */
function wp_ngd_ship_to_a_different_address_deactivation() {
	// Deactivation code here.
	$option_name = 'wp_ngd_ship_option_checked' ;
	 
	if ( get_option( $option_name ) !== false ) {
		delete_option( $option_name );
	}
}
register_deactivation_hook( __FILE__, 'wp_ngd_ship_to_a_different_address_deactivation' );

/**
 * Initialization class.
 */
function wp_ngd_ship_to_a_different_address_init() {
	global $wp_ngd_ship_to_a_different_address;
	$wp_ngd_ship_to_a_different_address = new WP_NGD_Ship_Different_Address_Checked_Class();
}
add_action( 'plugins_loaded', 'wp_ngd_ship_to_a_different_address_init' );
