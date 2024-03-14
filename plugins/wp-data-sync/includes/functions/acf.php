<?php
/**
 * ACF
 *
 * Process ACF fields.
 *
 * @since   2.4.0
 *
 * @package WP_Data_Sync_Api
 */

namespace WP_DataSync\App;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Is ACF Field Post Meta
 *
 * @param bool   $is_field
 * @param string $meta_key
 * @param int    $post_id
 */

add_filter( 'wp_data_sync_is_acf_field_post_meta', function( $is_field, $meta_key, $post_id ) {

	if ( ! class_exists( 'ACF' ) ) {
		return false;
	}

	if ( ! function_exists( 'get_field' ) ) {
		return false;
	}

	if ( ! function_exists( 'update_field' ) ) {
		return false;
	}

	return get_field( $meta_key, $post_id );

}, 10, 3 );

/**
 * Process ACF Field Post Meta
 *
 * @param string $meta_key
 * @param mixed $meta_value
 * @param int   $post_id
 */

add_action( 'wp_data_sync_process_acf_field_post_meta', function( $meta_key, $meta_value, $post_id ) {
	update_field( $meta_key, $meta_value, $post_id );
}, 10, 3 );