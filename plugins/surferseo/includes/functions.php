<?php
/**
 * Stores general purpose functions to use in multiple places.
 *
 * @package SurferSEO
 */

/**
 * Returns post GSC traffic by post ID.
 *
 * @param int $post_id Post ID.
 * @return array|null
 */
function surfer_get_last_post_traffic_by_id( $post_id ) {
	global $wpdb;
	return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}surfer_gsc_traffic WHERE post_id = %d ORDER BY data_gathering_date DESC LIMIT 1", $post_id ), ARRAY_A );
}

/**
 * Returns supported post types.
 *
 * @return array
 */
function surfer_return_supported_post_types() {

	$default_post_types = array(
		'post',
		'page',
	);

	return apply_filters( 'surfer_supported_post_types', $default_post_types );
}

/**
 * Verifies if user can perform ajax action
 *
 * @param string $nonce_name Nonce name.
 * @param string $action Action name.
 * @return bool
 */
function surfer_validate_ajax_request( $nonce_name = '_surfer_nonce', $action = 'surfer-ajax-nonce' ) {

	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	if ( ! check_ajax_referer( $action, $nonce_name, false ) ) {
		return false;
	}

	return true;
}

/**
 * Verifies if user can perform ajax action
 *
 * @param string $nonce_value Nonce.
 * @param string $action Action name.
 * @return bool
 */
function surfer_validate_custom_request( $nonce_value, $action = 'surfer-ajax-nonce' ) {

	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	if ( ! isset( $nonce_value ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $nonce_value ) ), $action ) ) {
		return false;
	}

	return true;
}


/**
 * Adds numerical suffix to number.
 *
 * @param int $number Number to add suffix to.
 * @return string
 */
function surfer_add_numerical_suffix( $number ) {
	$ends = array( 'th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th' );
	if ( ( ( $number % 100 ) >= 11 ) && ( ( $number % 100 ) <= 13 ) ) {
		return $number . 'th';
	} else {
		return $number . $ends[ $number % 10 ];
	}
}

/**
 * Checks if plugin is active even if default function is not loaded.
 *
 * @param string $plugin - plugin name to check.
 * @return bool
 */
function surfer_check_if_plugins_is_active( $plugin ) {

	if ( ! function_exists( 'is_plugin_active' ) ) {
		return in_array( $plugin, (array) get_option( 'active_plugins', array() ), true );
	} else {
		return is_plugin_active( $plugin );
	}
}
