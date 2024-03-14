<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function elex_gpf_insert_feed( $feed_id, $meta_key, $meta_content ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'gpf_feeds';
	$meta_content = json_encode( $meta_content );
	$wpdb->query( ( $wpdb->prepare( 'INSERT INTO %1s (feed_id,feed_meta_key,feed_meta_content) VALUES ( %d, %s, %s ) ', $table_name, $feed_id, $meta_key, $meta_content ) ? stripslashes( $wpdb->prepare( 'INSERT INTO %1s (feed_id,feed_meta_key,feed_meta_content) VALUES ( %d, %s, %s ) ', $table_name, $feed_id, $meta_key, $meta_content ) ) : $wpdb->prepare( '%s', '' ) ), ARRAY_A );
	return $meta_content;
}

function elex_gpf_update_feed( $feed_id, $meta_key, $meta_content ) {
	global $wpdb;
	$table = $wpdb->prefix . 'gpf_feeds';
	$meta_content = json_encode( $meta_content );// json_encode($meta_content);
	$wpdb->query( ( $wpdb->prepare( 'UPDATE %1s SET feed_meta_content=%s WHERE (feed_id=%d AND feed_meta_key=%s ) ', $table, $meta_content, $feed_id, $meta_key ) ? stripslashes( $wpdb->prepare( 'UPDATE %1s SET feed_meta_content=%s WHERE (feed_id=%d AND feed_meta_key=%s ) ', $table, $meta_content, $feed_id, $meta_key ) ) : $wpdb->prepare( '%s', '' ) ), ARRAY_A );
}

function elex_gpf_get_feed_data( $id, $meta_key ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'gpf_feeds';
	$feed_query           = "SELECT feed_meta_content FROM $table_name WHERE (feed_id=$id AND feed_meta_key='$meta_key')";
	$meta_content = $wpdb->get_results( ( $wpdb->prepare( '%1s', $feed_query ) ? stripslashes( $wpdb->prepare( '%1s', $feed_query ) ) : $wpdb->prepare( '%s', '' ) ), ARRAY_A );
	return $meta_content;
}

function elex_gpf_get_tax_rate_for_country( $country, $price ) {
	$countries = include ELEX_PRODUCT_FEED_PLUGIN_PATH . 'includes/elex-country-codes.php';
	$country_code = $countries[ $country ];
	$all_tax_rates = array();
	$tax_classes = WC_Tax::get_tax_classes(); // Retrieve all tax classes.
	if ( ! in_array( '', $tax_classes ) ) { // Make sure "Standard rate" (empty class name) is present.
		array_unshift( $tax_classes, '' );
	}
	foreach ( $tax_classes as $tax_class ) { // For each tax class, get all rates.
		$taxes = WC_Tax::get_rates_for_tax_class( $tax_class );
		$all_tax_rates = array_merge( $all_tax_rates, $taxes );
	}
	if ( ! empty( $all_tax_rates ) ) {
		$rate = 0;
		foreach ( $all_tax_rates as $key => $tax_details ) {
			if ( $tax_details->tax_rate > $rate && ( ! $tax_details->tax_rate_country || ( $tax_details->tax_rate_country == $country_code ) ) ) {
				$rate = $tax_details->tax_rate;
			}
		}
		$price_incl_tax = $price + ( ( $price * $rate ) / 100 );
		return $price_incl_tax;
	}
	return $price;
}
