<?php
/**
 * Restricted Meta Keys
 *
 * An array of restricted meta keys.
 * Keys are restricted since their meta value may break other functionality.
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\Woo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'wp_data_sync_restricted_meta_keys', function( $restricted_meta_keys ) {

	$wc_restricted_meta_keys = [
		'_product_image_gallery',
		'_product_attributes',
		'_upsell_ids',
		'_crosssell_ids'
	];

	return array_merge( $restricted_meta_keys, $wc_restricted_meta_keys );

});