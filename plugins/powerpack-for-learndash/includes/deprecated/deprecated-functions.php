<?php
/**
 * Deprecated functions
 * The functions will be removed in a later version.
 *
 * @package LearnDash PowerPack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

if ( ! function_exists( 'setting_is_active' ) ) {
	/**
	 * Checks if setting is active
	 *
	 * @param string $class_name Name of the class to check.
	 *
	 * @return bool
	 */
	function setting_is_active( $class_name = '' ) {
		if ( function_exists( '_deprecated_function' ) ) {
			_deprecated_function( __FUNCTION__, '1.3.0', 'setting_is_active()' );
		}

		return learndash_powerpack_is_setting_active( $class_name );
	}
}

if ( ! function_exists( 'check_if_file_exist_using_class_name' ) ) {
	/**
	 * Checks if a file exists using the classname.
	 *
	 * @param string $class_name the classname to look for.
	 *
	 * @return bool
	 */
	function check_if_file_exist_using_class_name( $class_name = '' ) {
		if ( function_exists( '_deprecated_function' ) ) {
			_deprecated_function( __FUNCTION__, '1.3.0', 'check_if_file_exist_using_class_name()' );
		}

		return learndash_powerpack_file_exists( $class_name );
	}
}

if ( ! function_exists( 'if_current_class_is_active' ) ) {
	/**
	 * Checks if current class is active.
	 *
	 * @param String $class_name the classname to format.
	 *
	 * @return false if the classname is empty or formatted classname.
	 */
	function if_current_class_is_active( $class_name = '' ) {
		if ( function_exists( '_deprecated_function' ) ) {
			_deprecated_function( __FUNCTION__, '1.3.0', 'if_current_class_is_active()' );
		}

		return learndash_powerpack_is_current_class_active( $class_name );
	}
}

if ( ! function_exists( 'ld_post_clean' ) ) {
	/**
	 * Sanitize formdata.
	 *
	 * @param mixed $var the post to clean.
	 *
	 * @return mixed
	 */
	function ld_post_clean( $var ) {
		if ( function_exists( '_deprecated_function' ) ) {
			_deprecated_function( __FUNCTION__, '1.3.0', 'ld_post_clean()' );
		}

		return learndash_powerpack_sanitize_formdata( $var );
	}
}

