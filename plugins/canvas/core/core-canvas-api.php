<?php
/**
 * The api functions for the plugin
 *
 * @package Canvas
 */

/**
 * This function will return true for a non empty array
 *
 * @param array $array Array.
 */
function canvas_is_array( $array ) {
	return ( is_array( $array ) && ! empty( $array ) );
}

/**
 * This function will return true for an empty var (allows 0 as true)
 *
 * @param mixed $value Value.
 */
function canvas_is_empty( $value ) {
	return ( empty( $value ) && ! is_numeric( $value ) );
}

/**
 * Alias of cnvs()->has_setting()
 *
 * @param string $name The name.
 */
function cnvs_has_setting( $name = '' ) {
	return cnvs()->has_setting( $name );
}

/**
 * Alias of cnvs()->get_setting()
 *
 * @param string $name The name.
 */
function cnvs_raw_setting( $name = '' ) {
	return cnvs()->get_setting( $name );
}

/**
 * Alias of cnvs()->update_setting()
 *
 * @param string $name The name.
 * @param mixed  $value The value.
 */
function cnvs_update_setting( $name, $value ) {

	return cnvs()->update_setting( $name, $value );
}

/**
 * Alias of cnvs()->get_setting()
 *
 * @param string $name  The name.
 * @param mixed  $value The value.
 */
function cnvs_get_setting( $name, $value = null ) {

	// Check settings.
	if ( cnvs_has_setting( $name ) ) {
		$value = cnvs_raw_setting( $name );
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
function cnvs_append_setting( $name, $value ) {

	// Vars.
	$setting = cnvs_raw_setting( $name );

	// Bail ealry if not array.
	if ( ! is_array( $setting ) ) {
		$setting = array();
	}

	// Append.
	$setting[] = $value;

	// Update.
	return cnvs_update_setting( $name, $setting );
}

/**
 * Returns data.
 *
 * @param string $name  The name.
 */
function cnvs_get_data( $name ) {
	return cnvs()->get_data( $name );
}

/**
 * Sets data.
 *
 * @param string $name  The name.
 * @param mixed  $value The value.
 */
function cnvs_set_data( $name, $value ) {
	return cnvs()->set_data( $name, $value );
}

