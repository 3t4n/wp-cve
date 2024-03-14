<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Register description strings
 */
// Shipping methods in shipping zones.
$zone = new WC_Shipping_Zone( 0 ); // Rest of the the world.
foreach ( $zone->get_shipping_methods() as $method ) {
	do_action( 'wpml_register_single_string', 'admin_texts_woocommerce_shipping', 'shipping_method_' . $method->instance_id . '_description', $method->get_option( 'description' ) );
}

foreach ( WC_Shipping_Zones::get_zones() as $zone ) {
	foreach ( $zone['shipping_methods'] as $method ) {
		do_action( 'wpml_register_single_string', 'admin_texts_woocommerce_shipping', 'shipping_method_' . $method->instance_id . '_description', $method->get_option( 'description' ) );
	}
}

/**
 * Translate descriptions
 */
add_filter( 'smdfw_description_output', 'smdfw_translate_description', 10, 2 );
function smdfw_translate_description( $description, $method ) {
	return apply_filters( 'wpml_translate_single_string', $description, 'admin_texts_woocommerce_shipping', 'shipping_method_' . $method->instance_id . '_description' );
}
