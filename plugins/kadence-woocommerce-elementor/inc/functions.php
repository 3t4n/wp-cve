<?php
/**
 * Functions for woo elementor.
 *
 * @package Kadence Woocommerce Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Removes the description tab so there is no issues with endless looping
 *
 * @param array $tabs the products active tabs.
 */
function kt_woo_ele_remove_description_tab( $tabs ) {
	unset( $tabs['description'] ); // Remove the description tab.
	return $tabs;
}

/**
 * Hides the quantity box by setting the max to 1.
 *
 * @param array $args the products quantity args.
 */
function kadence_woo_ele_hide_quantity( $args ) {
	$args['max_value'] = 1;
	$args['min_value'] = 1;
	return $args;
}
