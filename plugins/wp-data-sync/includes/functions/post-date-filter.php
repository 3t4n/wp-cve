<?php
/**
 * Post Date Filter
 *
 * Filter the post date with current date if empty.
 *
 * @since   1.9.10
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Post date filter.
 *
 * @param string   $post_date
 * @param int      $post_id
 * @param DataSync $data_sync
 *
 * @return string
 */

add_filter( 'wp_data_sync_post_date', function( $post_date, $post_id, $data_sync ) {

	// Set the post date if empty and not a new post.
	if ( empty( $post_date ) && $data_sync->get_is_new() ) {
		return current_time( 'mysql' );
	}

	return $post_date;

}, 10, 3 );
