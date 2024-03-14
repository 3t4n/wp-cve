<?php
/**
 * SECURITY : Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed!' );
}

/**
 * Remove revisions button
 */
add_action( 'post_submitbox_misc_actions', 'wpsrd_purge_revisions_button', 3 );
function wpsrd_purge_revisions_button() {
	global $post;
	$post_type_list = wpsrd_post_types_default();

	if ( ! in_array( get_post_type( $post->ID ), $post_type_list ) ) {
		return;
	}

	$revisions = wp_get_post_revisions( $post->ID );

	if ( ! empty( $revisions ) ) {
		//Check if user can delete revisions
		if ( ! current_user_can( apply_filters( 'wpsrd_capability', 'delete_post' ), $post->ID ) ) {
			return;
		}

		$nonce = wp_create_nonce( 'delete-revisions_' . $post->ID );

		$content  = '<span id="wpsrd-clear-revisions">&nbsp;&nbsp;';
		$content .= '<a href="#clear-revisions" class="wpsrd-link once" data-nonce="' . $nonce . '" data-action="' . esc_attr__( 'Purging', 'simple-revisions-delete' ) . '" data-error="' . esc_attr__( 'Something went wrong', 'simple-revisions-delete' ) . '">';
		$content .= __( 'Purge', 'simple-revisions-delete' );
		$content .= '</a>';
		$content .= '<span class="wpsrd-loading"></span>';
		$content .= '</span>';

		$content .= '<div class="misc-pub-section wpsrd-no-js">';
		$content .= '<a class="" href="' . admin_url( 'admin-post.php?action=wpsrd_purge_revisions&wpsrd-post_ID=' . $post->ID . '&wpsrd-nonce=' . $nonce ) . '">' . esc_attr__( 'Purge revisions', 'simple-revisions-delete' ) . '</a>';
		$content .= '</div>';

		echo $content;
	}
}

/**
 * Remove revisions functions
 */
add_action( 'wp_ajax_wpsrd_purge_revisions', 'wpsrd_purge_revisions' );
add_action( 'admin_post_wpsrd_purge_revisions', 'wpsrd_purge_revisions' );
function wpsrd_purge_revisions() {
	//Get var from GET
	$post_id         = $_GET['wpsrd-post_ID'];
	$nonce           = $_GET['wpsrd-nonce'];
	$revisions_count = 0;

	//Nonce check
	if ( ! wp_verify_nonce( $nonce, 'delete-revisions_' . $post_id ) ) {
		$output = array(
			'success' => 'error',
			'data'    => __( 'You can\'t do this...', 'simple-revisions-delete' ),
		);
	} else {
		$revisions = wp_get_post_revisions( $post_id );
	}

	//Check revisions & delete them
	if ( isset( $revisions ) && ! empty( $revisions ) ) {
		$output = array(
			'success' => 'success',
			'data'    => __( 'Purged', 'simple-revisions-delete' ),
		);

		foreach ( $revisions as $revision ) {
			$rev_delete = wp_delete_post_revision( $revision );

			if ( is_wp_error( $rev_delete ) ) {
				$output = array(
					'success' => 'error',
					'data'    => $rev_delete->get_error_message(),
				);
			} else {
				$revisions_count++;
			}
		}
	} else {
		$output = array(
			'success' => 'error',
			'data'    => __( 'There is no revisions for this post', 'simple-revisions-delete' ),
		);
	}

	//Output for AJAX call or no JS fallback
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

		( $output['success'] == 'success' ? wp_send_json_success( $output['data'] ) : wp_send_json_error( $output['data'] ) );

	} else {

		//Prepare the notice
		add_settings_error(
			'wpsrd-admin-notice',
			'wpsrd_notice',
			$output['data'],
			( $output['success'] == 'success' ? 'updated' : 'error' )
		);

		//Store the notice for the redirection
		set_transient( 'wpsrd_settings_errors', get_settings_errors(), 30 );

		//Build the redirection
		$redirect = add_query_arg( 'rev_purged', $revisions_count, wp_get_referer() );

		wp_redirect( $redirect );
		exit;

	}
}
