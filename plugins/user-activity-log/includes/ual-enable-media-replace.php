<?php
/**
 * Enable Media Replace Support.
 *
 * @package User Activity Log
 */

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'load-media_page_enable-media-replace/enable-media-replace', 'ual_on_load_plugin_admin_page', 10, 1 );
/**
 * Load Plugin on Admin Page.
 */
function ual_on_load_plugin_admin_page() {
	if ( empty( $_POST ) ) {
		return;
	}

	if ( isset( $_GET['action'] ) && 'media_replace_upload' == $_GET['action'] ) {
		if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'media_replace_upload' ) ) {
			$attachment_id = empty( $_POST['ID'] ) ? null : (int) $_POST['ID'];
			$replace_type  = empty( $_POST['replace_type'] ) ? null : sanitize_text_field( wp_unslash( $_POST['replace_type'] ) );
			$new_file      = empty( $_FILES['userfile'] ) ? null : array_map( 'sanitize_text_field', wp_unslash( $_FILES['userfile'] ) );

			$prev_attachment_post = get_post( $attachment_id );

			if ( empty( $attachment_id ) || empty( $new_file ) || empty( $prev_attachment_post ) ) {
				return;
			}
			$obj_type   = 'Enable Media Replace';
			$action     = 'Replaced attachment ';
			$post_id    = $attachment_id;
			$post_title = 'Replaced attachment ' . get_the_title( $prev_attachment_post ) . ' with new attachment ' . $new_file['name'];
			ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
		}
	}
}
