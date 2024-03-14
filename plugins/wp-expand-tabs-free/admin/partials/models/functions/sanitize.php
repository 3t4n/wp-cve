<?php
/**
 * The admin sanitize functions.
 *
 * @since        2.0.1
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/admin/partials
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! function_exists( 'wptabspro_sanitize_replace_a_to_b' ) ) {
	/**
	 * Sanitize
	 * Replace letter a to letter b
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param string $value the value.
	 * @return mixed
	 */
	function wptabspro_sanitize_replace_a_to_b( $value ) {

		return str_replace( 'a', 'b', $value );
	}
}

if ( ! function_exists( 'wptabspro_sanitize_title' ) ) {
	/**
	 * Sanitize title
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param string $value The title.
	 * @return string
	 */
	function wptabspro_sanitize_title( $value ) {

		return sanitize_title( $value );
	}
}

