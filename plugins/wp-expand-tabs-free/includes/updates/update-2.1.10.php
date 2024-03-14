<?php
/**
 * Update version.
 *
 * @package    WP_Tabs
 */

update_option( 'wp_tabs_version', WP_TABS_VERSION );
update_option( 'wp_tabs_db_version', WP_TABS_VERSION );

$args = new \WP_Query(
	array(
		'post_type'      => array( 'page' ),
		'post_status'    => 'publish',
		'posts_per_page' => '300',
	)
);

$post_ids = wp_list_pluck( $args->posts, 'ID' );
if ( count( $post_ids ) > 0 ) {
	add_filter( 'wp_revisions_to_keep', '__return_false' );
	foreach ( $post_ids as $post_key => $pid ) {
		$post_data    = get_post( $pid );
		$post_content = isset( $post_data->post_content ) ? $post_data->post_content : '';
		if ( ! empty( $post_content ) && ( strpos( $post_content, 'wp:sp-wp-tabs-free' ) !== false ) ) {
			$post_content   = preg_replace( '/wp:sp-wp-tabs-free/i', 'wp:sp-wp-tabs-pro', $post_content );
			$gutenberg_post = array(
				'ID'           => $pid,
				'post_content' => $post_content,
			);
			// Update the post into the database.
			$pid = wp_update_post( $gutenberg_post );
		}
	}
}
