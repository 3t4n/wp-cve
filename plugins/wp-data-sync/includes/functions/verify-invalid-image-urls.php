<?php
/**
 * Verify Invalid Image URLs
 *
 * If an image fails the first validation process. We use a more expensive
 * method to validate the image URL.
 *
 * This is not recommended due to performance issues.
 *
 * @since   2.1.24
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter invalid image URL response.
 *
 * @param bool $info
 * @param string $image_url
 * @param DataSync $data_sync
 *
 * @return bool
 */

add_filter( 'wp_data_sync_is_valid_image_url', function( $info, $image_url, $data_sync ) {

	if ( ! $info ) {

		if ( ! Settings::is_checked( 'wp_data_sync_verify_invalid_image_urls' ) ) {
			return $info;
		}

		$response = wp_remote_head( $image_url, [
			'sslverify' => $data_sync->ssl_verify()
		] );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
			return true;
		}

	}

	return $info;

}, 10, 3 );
