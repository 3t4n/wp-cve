<?php

    /**
    * Description : WooCommerce Extension that can Define different shipping costs for products, based on customers location.
    * Package : Innozilla Per Product Shipping WooCommerce
    * Version : 1.0.0
    * Author : Innozilla
    */
    

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * innozilla_per_product_shipping_get_matching_rule function.
 *
 * @param mixed $product_id
 * @param mixed $package
 * @return false|null
 */
function innozilla_per_product_shipping_get_matching_rule( $product_id, $package, $standalone = true ) {
	global $wpdb;

	$product_id = apply_filters( 'innozilla_per_product_shipping_get_matching_rule_product_id', $product_id );

	if ( get_post_meta( $product_id, '_per_product_shipping', true ) !== 'yes' )
		return false;

	if ( ! $standalone && get_post_meta( $product_id, '_per_product_shipping_add_to_all', true ) !== 'yes' )
		return null; // No rates, don't fallback to parent product if variable

	$country 	= $package['destination']['country'];
	$state 		= $package['destination']['state'];
	$postcode 	= $package['destination']['postcode'];

	// Define valid postcodes
	$valid_postcodes 	= array( '', $postcode );

	// Work out possible valid wildcard postcodes
	$postcode_length	= strlen( $postcode );
	$wildcard_postcode	= $postcode;

	for ( $i = 0; $i < $postcode_length; $i ++ ) {
		$wildcard_postcode = substr( $wildcard_postcode, 0, -1 );
		$valid_postcodes[] = $wildcard_postcode . '*';
	}

	// Rules array
	$rules = array();

	// Get rules matching product, country and state
    $matching_rule = $wpdb->get_row(
    	$wpdb->prepare(
    		"
    		SELECT * FROM {$wpdb->prefix}innozilla_per_product_shipping_rules_woo
    		WHERE product_id = %d
    		AND iz_rule_country IN ( '', %s )
    		AND iz_rule_state IN ( '', %s )
    		AND iz_rule_postcode IN ( '" . implode( "','", $valid_postcodes ) . "' )
    		ORDER BY iz_rule_order
    		LIMIT 1
    		" , $product_id, strtoupper( $country ), strtoupper( $state )
    	)
    );

    return $matching_rule;
}