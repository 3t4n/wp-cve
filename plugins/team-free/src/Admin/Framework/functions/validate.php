<?php
/**
 *  Framework validate file.
 *
 * @package    team-free
 * @subpackage team-free/framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! function_exists( 'spf_validate_email' ) ) {
	/**
	 * Email validate
	 *
	 * @param  string $value email.
	 * @return string
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spf_validate_email( $value ) {

		if ( ! filter_var( $value, FILTER_VALIDATE_EMAIL ) ) {
			return esc_html__( 'Please enter a valid email address.', 'team-free' );
		}

	}
}

if ( ! function_exists( 'spf_validate_numeric' ) ) {
	/**
	 * Numeric validate
	 *
	 * @param  int $value int.
	 * @return int
	 */
	function spf_validate_numeric( $value ) {

		if ( ! is_numeric( $value ) ) {
			return esc_html__( 'Please enter a valid number.', 'team-free' );
		}

	}
}

if ( ! function_exists( 'spf_validate_required' ) ) {
	/**
	 * Required validate
	 *
	 * @param  string $value string.
	 * @return string
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spf_validate_required( $value ) {

		if ( empty( $value ) ) {
			return esc_html__( 'This field is required.', 'team-free' );
		}

	}
}

if ( ! function_exists( 'spf_validate_url' ) ) {
	/**
	 * URL validate
	 *
	 * @param  string $value value.
	 * @return string
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spf_validate_url( $value ) {

		if ( ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
			return esc_html__( 'Please enter a valid URL.', 'team-free' );
		}

	}
}

