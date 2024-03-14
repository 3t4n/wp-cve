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
add_action( 'admin_footer', 'wpsrd_gutenberg_purge_revisions_button', 3 );
function wpsrd_gutenberg_purge_revisions_button() {

	global $post;
	$post_type_list = wpsrd_post_types_default();

	if ( ! isset( $post->ID ) || ! in_array( get_post_type( $post->ID ), $post_type_list ) ) {
		return;
	}

	$revisions = wp_get_post_revisions( $post->ID );

	//Check if user can delete revisions
	if ( ! current_user_can( apply_filters( 'wpsrd_capability', 'delete_post' ), $post->ID ) ) {
		return;
	}

	$nonce = wp_create_nonce( 'delete-revisions_' . $post->ID );

	$content  = '<div id="wpsrd-gutenberg" style="display:none">';
	$content .= '<span id="wpsrd-clear-revisions">';
	$content .= '<a href="#clear-revisions" class="wpsrd-link once" data-nonce="' . $nonce . '" data-action="' . esc_attr__( 'Purging', 'simple-revisions-delete' ) . '" data-error="' . esc_attr__( 'Something went wrong', 'simple-revisions-delete' ) . '">';
	$content .= __( 'Purge', 'simple-revisions-delete' );
	$content .= '</a>';
	$content .= '<span class="wpsrd-loading"></span>';
	$content .= '</span>';

	$content .= '<div class="misc-pub-section wpsrd-no-js">';
	$content .= '<a class="" href="' . admin_url( 'admin-post.php?action=wpsrd_purge_revisions&wpsrd-post_ID=' . $post->ID . '&wpsrd-nonce=' . $nonce ) . '">' . esc_attr__( 'Purge revisions', 'simple-revisions-delete' ) . '</a>';
	$content .= '</div>';
	$content .= '</div>';

	//Insert the purge link in the footer. It wil be copied by the js when needed
	echo $content;
}
