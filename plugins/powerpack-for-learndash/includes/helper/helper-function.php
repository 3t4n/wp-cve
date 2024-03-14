<?php
/**
 * Helpers
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Checks if setting is active
 *
 * @param string $class_name Name of the class to check.
 *
 * @return bool
 */
function learndash_powerpack_is_setting_active( $class_name = '' ) {
	if ( empty( $class_name ) ) {
		return false;
	}

	$get_option = get_option( 'learndash_powerpack_active_classes' );

	if ( isset( $get_option[ $class_name ] ) && 'active' === $get_option[ $class_name ] ) {
		return true;
	}

	return false;
}

/**
 * Checks if a file exists using the classname.
 *
 * @param string $class_name the classname to look for.
 *
 * @return bool
 */
function learndash_powerpack_file_exists( $class_name = '' ) {
	if ( empty( $class_name ) ) {
		return false;
	}
	$class_name = learndash_powerpack_format_class_file_include( $class_name );
	global $wp_filesystem;
	require_once ABSPATH . '/wp-admin/includes/file.php';
	WP_Filesystem();
	$file_path = LD_POWERPACK_PLUGIN_PATH . '/includes/ld_classes/' . $class_name . '.php';

	if ( ! $wp_filesystem->exists( $file_path ) ) {
		return false;
	}

	return true;
}

/**
 * Formats the classname to strlower and replaces _ to -.
 *
 * @param string $class_name the classname to replace.
 *
 * @return bool|string if the classname is empty or formatted classname.
 */
function learndash_powerpack_format_class_file_include( $class_name ) {
	if ( empty( $class_name ) ) {
		return false;
	}
	$class_name = strtolower( str_replace( '_', '-', $class_name ) );

	return $class_name;
}

/**
 * Checks if current class is active.
 *
 * @param String $class_name the classname to format.
 *
 * @return string
 */
function learndash_powerpack_is_current_class_active( $class_name = '' ) {
	$find_class_status = '';
	$get_option        = get_option( 'learndash_powerpack_active_classes' );
	if ( isset( $get_option[ $class_name ] ) ) {
		$find_class_status = $get_option[ $class_name ];
	}

	return $find_class_status;
}

/**
 * Sanitize formdata.
 *
 * @param mixed $var User input to sanitize.
 *
 * @return mixed
 */
function learndash_powerpack_sanitize_formdata( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'learndash_powerpack_sanitize_formdata', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}
