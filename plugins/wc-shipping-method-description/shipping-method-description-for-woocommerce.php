<?php
/**
 * Plugin Name: Shipping Method Description for WooCommerce
 * Plugin URI: https://github.com/thomascharbit/smdfw
 * Description: Add a description to all WooCommerce shipping methods on cart and checkout pages.
 * Author: Thomas Charbit
 * Author URI: https://thomascharbit.fr
 * Version: 1.2.6
 * License: GPLv3 or later License
 * Requires at least: 4.4
 * Tested up to: 6.0.1
 * WC requires at least: 2.6
 * WC tested up to: 6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'SMDFW_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Check if WooCommerce is active
 **/
function smdfw_is_woocommerce_activated() {
	return class_exists( 'woocommerce' );
}

/**
 * Init plugin
 **/
add_action( 'init', 'smdfw_init', 100 );
function smdfw_init() {
	if ( ! smdfw_is_woocommerce_activated() ) {
		return;
	}
	// Add shipping methods filters
	$shipping_methods = WC()->shipping->get_shipping_methods();
	foreach ( $shipping_methods as $id => $shipping_method ) {
		add_filter( "woocommerce_shipping_instance_form_fields_$id", 'smdfw_add_form_fields' );
	}
	// Add Polylang integration if needed
	if ( function_exists( 'pll_current_language' ) ) {
		require_once SMDFW_DIR . 'includes/smdfw-polylang.php';
	}
	// Add WPML integration if needed
	if ( function_exists( 'icl_object_id' ) && ! function_exists( 'pll_current_language' ) ) {
		require_once SMDFW_DIR . 'includes/smdfw-wpml.php';
	}
}

/**
 * Display admin notice if WooCommerce is not active
 */
add_action( 'admin_notices', 'smdfw_requirement_notice' );
function smdfw_requirement_notice() {
	if ( ! smdfw_is_woocommerce_activated() ) {
		/* translators: %1$s: open link, %2$s: close link */
		$error   = sprintf( __( 'WooCommerce Shipping Method Description requires %1$sWooCommerce%2$s to be installed and active.', 'smdfw' ), '<a href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>' );
		$message = '<div class="error"><p>' . $error . '</p></div>';
		echo $message;
	}
}

/**
 * Add description field to shipping method form
 */
function smdfw_add_form_fields( $fields ) {
	// Create description field
	$new_fields = array(
		'description' => array(
			'title'   => __( 'Description', 'smdfw' ),
			'type'    => 'textarea',
			'default' => null,
		),
	);
	// Insert it after title field
	$keys  = array_keys( $fields );
	$index = array_search( 'title', $keys, true );
	$pos   = false === $index ? count( $fields ) : $index + 1;
	return array_merge( array_slice( $fields, 0, $pos ), $new_fields, array_slice( $fields, $pos ) );
}

/**
 * Load description as metadata
 */
add_filter( 'woocommerce_shipping_method_add_rate_args', 'smdfw_add_rate_description_arg', 10, 2 );
function smdfw_add_rate_description_arg( $args, $method ) {
	$args['meta_data']['description'] = htmlentities( $method->get_option( 'description' ) );
	return $args;
}

/**
 * Display description field after method label
 */
add_action( 'woocommerce_after_shipping_rate', 'smdfw_output_shipping_rate_description', 10 );
function smdfw_output_shipping_rate_description( $method ) {
	$meta_data = $method->get_meta_data();
	if ( array_key_exists( 'description', $meta_data ) ) {
		$description = apply_filters( 'smdfw_description_output', html_entity_decode( $meta_data['description'] ), $method );
		$html        = '<div class="shipping_method_description"><small class="smdfw">' . wp_kses( $description, wp_kses_allowed_html( 'post' ) ) . '</small></div>';
		echo apply_filters( 'smdfw_description_output_html', $html, $description, $method );
	}
}
