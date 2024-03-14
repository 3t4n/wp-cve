<?php
/**
 * SECURITY : Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed!' );
}

/**
 * Insert delete button if user can delete revisions
 */
add_action( 'admin_footer', 'wpsrd_single_revision_delete_button' );
function wpsrd_single_revision_delete_button() {
	global $post, $pagenow;
	if ( 'post.php' == $pagenow ) {
		$post_type_list = wpsrd_post_types_default();

		if ( ! isset( $post->ID ) ) {
			return;
		}

		$nonce = wp_create_nonce( 'delete-revisions_' . $post->ID );

		if ( current_user_can( apply_filters( 'wpsrd_capability', 'delete_post' ), $post->ID ) && in_array( get_post_type( $post->ID ), $post_type_list ) ) {
			echo '<div id="wpsrd-btn-container" style="display:none"><a href="#delete-revision" data-nonce="' . $nonce . '" class="action wpsrd-btn once">' . __( 'Delete' ) . '</a></div>';
		}
	}
}

/**
 * Delete single revision from revisions meta box
 */
add_action( 'wp_ajax_wpsrd_single_revision_delete', 'wpsrd_single_revision_delete' );
function wpsrd_single_revision_delete() {

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

		//Get var from GET
		$post_id = $_GET['wpsrd-post_ID'];
		$revID   = $_GET['revID'];
		$nonce 	 = $_GET['wpsrd-nonce'];

		//Nonce check
		if ( ! wp_verify_nonce( $nonce, 'delete-revisions_' . $post_id ) ) {
			$output = array(
				'success' => 'error',
				'data'    => __( 'You can\'t do this...', 'simple-revisions-delete' ),
			);
		}

		$post_type_list = wpsrd_post_types_default();

		if ( ! current_user_can( apply_filters( 'wpsrd_capability', 'delete_post' ), $post_id ) && ! in_array( get_post_type( $post_id ), $post_type_list ) ) {
			wp_send_json_error( __( 'You can\'t do this...', 'simple-revisions-delete' ) );
		}

		if ( ! empty( $revID ) && $post_id == wp_is_post_revision( $revID ) ) {

			$rev_delete = wp_delete_post_revision( $revID );

			if ( is_wp_error( $rev_delete ) ) {
				//Extra error notice if WP error return something
				$output = array(
					'success' => 'error',
					'data'    => $rev_delete->get_error_message(),
				);
			} else {
				$output = array(
					'success' => 'success',
					'data'    => __( 'Deleted' ),
				);
			}

			( $output['success'] == 'success' ? wp_send_json_success( $output['data'] ) : wp_send_json_error( $output['data'] ) );

		} else {
			wp_send_json_error( __( 'Something went wrong', 'simple-revisions-delete' ) );
		}
	}

	//If accessed directly
	wp_die( __( 'You can\'t do this...', 'simple-revisions-delete' ) );

}
