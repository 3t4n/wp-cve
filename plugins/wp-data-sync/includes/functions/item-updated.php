<?php
/**
 * Item Updated
 *
 * Delete item ID from DB when item is updated, trashed or untrashed.
 *
 * @since   1.2.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'post_updated', 'WP_DataSync\App\item_updated', 20, 1 );
add_action( 'trashed_post', 'WP_DataSync\App\item_updated', 20, 1 );
add_action( 'untrash_post', 'WP_DataSync\App\item_updated', 20, 1 );
add_action( 'set_object_terms', 'WP_DataSync\App\item_updated', 20, 1 );

/**
 * Delete item ID from DB.
 *
 * @param $item_id
 */

function item_updated( $item_id ) {
	reset_item_request_status( $item_id );
}

/**
 * Fire when post meta is updated.
 *
 * @param int $meta_id
 * @param int $item_id
 */

add_action( 'updated_postmeta', function( $meta_id, $item_id ) {
	reset_item_request_status( $item_id );
}, 10, 2 );

/**
 * Reset item request status.
 *
 * @param $item_id
 */

function reset_item_request_status( $item_id ) {

	global $current_item_id;

	// Do not reset item requests status multiple times.
	if ( $current_item_id !== $item_id ) {

		$current_item_id = $item_id;

		ItemRequest::delete_id( $item_id );

		Log::write( 'reset-item-request-status', "Iten ID: $item_id" );

	}

}
