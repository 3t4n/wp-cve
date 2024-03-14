<?php
/**
 * Setup hooks.
 *
 * @package WP Product Review Feed Manager/Functions
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds the Product Review Feed to the feed types.
 *
 * @param   array $types    Array with the feed types.
 *
 * @return  array   Array with all the feed types, including the Product Review Feed.
 */
function wpprfm_add_review_feed_type( $types ) {
	$types['2'] = 'Google Product Review Feed';
	return $types;
}

add_filter( 'wppfm_feed_types', 'wpprfm_add_review_feed_type' );
