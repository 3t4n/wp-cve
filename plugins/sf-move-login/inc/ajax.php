<?php
defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

add_action( 'wp_ajax_sfml_sanitize_slug', 'sfml_sanitize_slug_ajax_post_cb' );
/**
 * Sanitize all slugs via ajax.
 *
 * @since 2.5.3
 */
function sfml_sanitize_slug_ajax_post_cb() {
	// Make all security tests.
	if ( false === check_ajax_referer( 'sfml_sanitize_slug', false, false ) ) {
		wp_send_json_error( 'nonce' );
	}

	$capacity = is_multisite() ? 'manage_network_options' : 'manage_options';

	if ( ! current_user_can( $capacity ) ) {
		wp_send_json_error( 'capacity' );
	}

	if ( empty( $_POST['slugs'] ) || ! is_array( $_POST['slugs'] ) ) {
		wp_send_json_error( 'entry' );
	}

	$slugs = SFML_Options::get_instance()->sanitize_slugs( $_POST['slugs'] );

	wp_send_json_success( $slugs );
}
