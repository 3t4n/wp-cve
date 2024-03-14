<?php
/**
 * Custom Post Template.
 *
 * PHP version 7
 *
 * @package  mws_open_webstory
 */

/**
 * Ajax Deleting post.
 *
 * @since 1.0.3
 */
add_action( 'wp_ajax_mws_dlt_webstory', 'mws_dlt_webstory' );
add_action( 'wp_ajax_nopriv_mws_dlt_webstory', 'mws_dlt_webstory' );
/** Context menu delete story */
function mws_dlt_webstory() {
	if ( ! check_ajax_referer( 'mws_context_menu', 'mws_context_menu' ) ) {
		die();
	} else {
		if ( isset( $_POST['product_id'] ) && ! empty( $_POST['product_id'] ) ) {
			$post_id = sanitize_text_field( wp_unslash( $_POST['product_id'] ) );
			wp_delete_post( $post_id, true );
			echo esc_html( $post_id );
		}
	}
		die();
}


