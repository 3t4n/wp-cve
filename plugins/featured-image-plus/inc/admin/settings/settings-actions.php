<?php
/**
 * [Short description]
 *
 * @package    DEVRY\FIP
 * @copyright  Copyright (c) 2024, Developry Ltd.
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since      1.3
 */

namespace DEVRY\FIP;

! defined( ABSPATH ) || exit; // Exit if accessed directly.


/**
 * [AJAX] Reset all the options on the their default values.
 */
function fip_reset_options_default() {
	$fip_admin = new FIP_Admin();

	$fip_admin->get_invalid_nonce_token();
	$fip_admin->get_invalid_user_cap();

	delete_option( 'fip_types_supported' );
	delete_option( 'fip_theme_support' );

	$fip_admin->print_json_message(
		1,
		__( 'All options were reset to their default values.', 'featured-image-plus' )
	);
}

add_action( 'wp_ajax_fip_reset_options_default', __NAMESPACE__ . '\fip_reset_options_default' );

/**
 * [ADMIN] Add single featured image.
 */
function fip_add_single_featured_image() {
	$fip_admin = new FIP_Admin();

	$fip_admin->get_invalid_nonce_token();
	$fip_admin->get_invalid_user_cap();

	$id       = absint( sanitize_text_field( $_REQUEST['id'] ) );
	$thumb_id = absint( sanitize_text_field( $_REQUEST['thumb_id'] ) );

	if ( array_key_exists( 'id', $_REQUEST ) && '' !== $id ) {
		if ( array_key_exists( 'thumb_id', $_REQUEST ) && '' !== $thumb_id ) {
			update_post_meta( $id, '_thumbnail_id', $thumb_id );

			$fip_admin->print_json_message(
				1,
				__( 'New featured image was added successfully.' ),
			);
		}
	}

	$fip_admin->print_json_message(
		0,
		__( 'Unexpected error!' ),
	);
}

add_action( 'wp_ajax_fip_add_single_featured_image', __NAMESPACE__ . '\fip_add_single_featured_image' );

/**
 * [ADMIN] Remove single featured image.
 */
function fip_remove_single_featured_image() {
	$fip_admin = new FIP_Admin();

	$fip_admin->get_invalid_nonce_token();
	$fip_admin->get_invalid_user_cap();

	$id = absint( sanitize_text_field( $_REQUEST['id'] ) );

	if ( array_key_exists( 'id', $_REQUEST ) && '' !== $id ) {
		delete_post_meta( $id, '_thumbnail_id' );

		$fip_admin->print_json_message(
			1,
			__( 'Removing featured image...' ),
		);
	}

	$fip_admin->print_json_message(
		0,
		__( 'Unexpected error!' ),
	);
}

add_action( 'wp_ajax_fip_remove_single_featured_image', __NAMESPACE__ . '\fip_remove_single_featured_image' );


/**
 * [ADMIN] Remove ALL featured images.
 */
function fip_remove_all_featured_images() {
	$fip_admin = new FIP_Admin();

	$fip_admin->get_invalid_nonce_token();
	$fip_admin->get_invalid_user_cap();

	$post_ids = array_map( 'sanitize_text_field', $_REQUEST['post_ids'] );

	if ( array_key_exists( 'post_ids', $_POST ) && '' !== $post_ids ) {
		foreach ( $post_ids as $post_id ) {
			$post_id = absint( $post_id );

			if ( get_post_meta( $post_id, '_thumbnail_id', true ) ) {
				delete_post_meta( $post_id, '_thumbnail_id' );
			}
		}

		$fip_admin->print_json_message(
			1,
			__( 'Removing ALL featured images...' ),
		);
	}

	$fip_admin->print_json_message(
		0,
		__( 'Unexpected error!' ),
	);
}

add_action( 'wp_ajax_fip_remove_all_featured_images', __NAMESPACE__ . '\fip_remove_all_featured_images' );
