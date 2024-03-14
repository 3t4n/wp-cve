<?php
/**
 * Plugin option functions.
 *
 * @package add-on-cf7-for-notion
 */

namespace WPC_WPCF7_NTN\Options;

defined( 'ABSPATH' ) || exit;

/**
 * Get the full option key (prefixed).
 *
 * @param string $key Option key.
 * @return string
 */
function get_option_key( $key ) {
	return sprintf( '%1$s%2$s', WPCONNECT_WPCF7_NTN_OPTIONS_PREFIX, $key );
}

/**
 * Get a specific option value.
 *
 * @param string $key Option key.
 * @param mixed  $default Option default value.
 * @return mixed
 */
function get_plugin_option( $key, $default = null ) {
	return get_option( get_option_key( $key ), $default );
}

/**
 * Update a specific option.
 *
 * @param string  $key Option key.
 * @param mixed   $value Option value.
 * @param boolean $autoload Autoload.
 * @return void
 */
function update_plugin_option( $key, $value, $autoload = false ) {
	update_option( get_option_key( $key ), $value, $autoload );
}

/**
 * Delete a specific option.
 *
 * @param string $key Option key.
 * @return void
 */
function delete_plugin_option( $key ) {
	delete_option( get_option_key( $key ) );
}

/**
 * Do we already have a value for a specific option?
 *
 * @param string $key Option key.
 * @return boolean
 */
function has_plugin_option( $key ) {
	return ! empty( get_plugin_option( $key ) );
}
