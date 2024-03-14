<?php
/**
 * The api functions for the plugin
 *
 * @package Sight
 */

/**
 * Alias of sight_portfolio()->has_setting()
 *
 * @param string $name The name.
 */
function sight_has_setting( $name = '' ) {
	return sight_portfolio()->has_setting( $name );
}

/**
 * Alias of sight_portfolio()->get_setting()
 *
 * @param string $name The name.
 */
function sight_raw_setting( $name = '' ) {
	return sight_portfolio()->get_setting( $name );
}

/**
 * Alias of sight_portfolio()->update_setting()
 *
 * @param string $name The name.
 * @param mixed  $value The value.
 */
function sight_update_setting( $name, $value ) {

	return sight_portfolio()->update_setting( $name, $value );
}

/**
 * Alias of sight_portfolio()->get_setting()
 *
 * @param string $name  The name.
 * @param mixed  $value The value.
 */
function sight_get_setting( $name, $value = null ) {

	// Check settings.
	if ( sight_has_setting( $name ) ) {
		$value = sight_raw_setting( $name );
	}

	// Filter.
	$value = apply_filters( "canvas_settings_{$name}", $value );

	return $value;
}

/**
 * This function will add a value into the settings array found in the acf object
 *
 * @param string $name  The name.
 * @param mixed  $value The value.
 */
function sight_append_setting( $name, $value ) {

	// Vars.
	$setting = sight_raw_setting( $name );

	// Bail ealry if not array.
	if ( ! is_array( $setting ) ) {
		$setting = array();
	}

	// Append.
	$setting[] = $value;

	// Update.
	return sight_update_setting( $name, $setting );
}

/**
 * Returns data.
 *
 * @param string $name  The name.
 */
function sight_get_data( $name ) {
	return sight_portfolio()->get_data( $name );
}

/**
 * Sets data.
 *
 * @param string $name  The name.
 * @param mixed  $value The value.
 */
function sight_set_data( $name, $value ) {
	return sight_portfolio()->set_data( $name, $value );
}
