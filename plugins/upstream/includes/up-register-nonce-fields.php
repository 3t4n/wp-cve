<?php
/**
 * Handle nonce fields registration on a single hooks.
 * Nonce fields on meta boxes are not included, as they are registered on their meta boxes.
 *
 * @package UpStream
 */

/**
 * Add nonce field at the top of the post-editor form.
 *
 * @param WP_Post $post Post object.
 */
function upstream_admin_nonce_field_on_edit_form_top( $post ) {
	$nonce_fields = array(
		'project'         => array(
			'nonce_field_name' => 'upstream_admin_project_form_nonce',
			'nonce_key'        => 'upstream_admin_project_form',
		),
		'client'          => array(
			'nonce_field_name' => 'upstream_admin_client_form_nonce',
			'nonce_key'        => 'upstream_admin_client_form',
		),
		'upst_milestone'  => array(
			'nonce_field_name' => 'upstream_admin_upst_milestone_form_nonce',
			'nonce_key'        => 'upstream_admin_upst_milestone_form',
		),
		'up_custom_field' => array(
			'nonce_field_name' => 'upstream_admin_up_custom_field_form_nonce',
			'nonce_key'        => 'upstream_admin_up_custom_field_form',
		),
	);

	// Just render on the registered post types.
	if ( ! in_array( $post->post_type, array_keys( $nonce_fields ), true ) ) {
		return;
	}

	// Prevent the nonce field to be rendered multiple time.
	if ( defined( 'UPSTREAM_MAIN_NONCE_FIELD_RENDERED' ) ) {
		return;
	}

	// Render nonce field.
	wp_nonce_field(
		$nonce_fields[ $post->post_type ]['nonce_key'],
		$nonce_fields[ $post->post_type ]['nonce_field_name']
	);

	define( 'UPSTREAM_MAIN_NONCE_FIELD_RENDERED', 1 );
}
add_action( 'edit_form_top', 'upstream_admin_nonce_field_on_edit_form_top' );
