<?php
/**
 * Setup hooks.
 *
 * @package WP Merchant Promotions Feed Manager/Functions
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds the Merchant Promotions Feed to the feed types.
 *
 * @param   array $types    Array with the feed types.
 *
 * @return  array   Array with all the feed types, including the Merchant Promotions Feed.
 */
function wpprfm_add_promotions_feed_type( $types ) {
	$types['3'] = 'Google Merchant Promotions Feed';
	return $types;
}

add_filter( 'wppfm_feed_types', 'wpprfm_add_promotions_feed_type' );
