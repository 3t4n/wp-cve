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
	pll_register_string( 'description_0_' . $method->id, $method->get_option( 'description' ), 'WooCommerce' );
}

foreach ( WC_Shipping_Zones::get_zones() as $zone ) {
	foreach ( $zone['shipping_methods'] as $method ) {
		pll_register_string( 'description_' . $zone['zone_id'] . '_' . $method->id, $method->get_option( 'description' ), 'WooCommerce' );
	}
}

/**
 * Translate descriptions
 */
add_filter( 'smdfw_description_output', 'smdfw_translate_description' );
function smdfw_translate_description( $description ) {
	return pll__( $description );
}
