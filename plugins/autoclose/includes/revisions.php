<?php
/**
 * Functions to handle post revisions.
 *
 * @since 2.0.0
 *
 * @package AutoClose
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Function to read options from the database.
 *
 * @since 2.0.0
 *
 * @return int|bool Number of rows affected/selected for all other queries. Boolean false on error.
 */
function acc_delete_revisions() {
	global $wpdb;

	$result = $wpdb->query( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		"
		DELETE FROM {$wpdb->posts}
		WHERE post_type = 'revision'
		"
	);

	return $result;
}


/**
 * Sets the number of revisions to keep for a specific post type.
 *
 * @since 2.1.0
 *
 * @param int     $num  Number of revisions to store.
 * @param WP_Post $post Post object.
 */
function acc_revisions_to_keep( $num, $post ) {
	$post_type           = $post->post_type;
	$revision_post_types = array_keys( acc_get_revision_post_types() );

	$revisions_to_keep = acc_get_option( 'revision_' . $post_type );

	// If revisions to keep is -2, then we ignore.
	if ( -2 === (int) $revisions_to_keep ) {
		return $num;
	}

	$is_target_type = in_array( $post_type, $revision_post_types, true );

	return $is_target_type ? $revisions_to_keep : $num;
}
add_filter( 'wp_revisions_to_keep', 'acc_revisions_to_keep', 999999, 2 );


/**
 * Retrieve the post types that have revisions.
 *
 * @since 2.1.0
 *
 * @return array Array of post types that support revisisions in the format name => label/name
 */
function acc_get_revision_post_types() {

	$revision_post_types = array();

	$post_types = get_post_types( array(), 'objects' );

	foreach ( $post_types as $post_type ) {
		if ( post_type_supports( $post_type->name, 'revisions' ) ) {
			if ( property_exists( $post_type, 'labels' ) && property_exists( $post_type->labels, 'name' ) ) {
				$name = $post_type->labels->name;
			} else {
				$name = $post_type->name;
			}
			$revision_post_types[ $post_type->name ] = $name;
		}
	}

	return $revision_post_types;
}
