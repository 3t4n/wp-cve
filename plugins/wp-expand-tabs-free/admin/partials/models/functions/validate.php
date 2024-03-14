<?php
/**
 * The admin validate functions.
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

if ( ! function_exists( 'wptabspro_validate_email' ) ) {
	/**
	 * Email validate
	 *
	 * @param string $value The email.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wptabspro_validate_email( $value ) {

		if ( ! filter_var( $value, FILTER_VALIDATE_EMAIL ) ) {
			return esc_html__( 'Please write a valid email address!', 'wp-expand-tabs-free' );
		}

	}
}

if ( ! function_exists( 'wptabspro_validate_numeric' ) ) {
	/**
	 *
	 * Numeric validate
	 *
	 * @param string $value The number.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wptabspro_validate_numeric( $value ) {

		if ( ! is_numeric( $value ) ) {
			return esc_html__( 'Please write a numeric data!', 'wp-expand-tabs-free' );
		}

	}
}

if ( ! function_exists( 'wptabspro_validate_required' ) ) {
	/**
	 *
	 * Required validate
	 *
	 * @param string $value The required data.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wptabspro_validate_required( $value ) {

		if ( empty( $value ) ) {
			return esc_html__( 'Error! This field is required!', 'wp-expand-tabs-free' );
		}

	}
}

if ( ! function_exists( 'wptabspro_validate_url' ) ) {
	/**
	 *
	 * URL validate
	 *
	 * @param string $value The URL.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wptabspro_validate_url( $value ) {

		if ( ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
			return esc_html__( 'Please write a valid url!', 'wp-expand-tabs-free' );
		}

	}
}

if ( ! function_exists( 'wptabspro_customize_validate_email' ) ) {
	/**
	 *
	 * Email validate for Customizer
	 *
	 * @param object $validity Email validity.
	 * @param string $value The Email.
	 * @param object $wp_customize Customize option.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wptabspro_customize_validate_email( $validity, $value, $wp_customize ) {

		if ( ! sanitize_email( $value ) ) {
			$validity->add( 'required', esc_html__( 'Please write a valid email address!', 'wp-expand-tabs-free' ) );
		}

		return $validity;

	}
}

if ( ! function_exists( 'wptabspro_customize_validate_numeric' ) ) {
	/**
	 *
	 * Numeric validate for Customizer
	 *
	 * @param object $validity Numeric validity.
	 * @param string $value The Numeric.
	 * @param object $wp_customize Customize option.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wptabspro_customize_validate_numeric( $validity, $value, $wp_customize ) {

		if ( ! is_numeric( $value ) ) {
			$validity->add( 'required', esc_html__( 'Please write a numeric data!', 'wp-expand-tabs-free' ) );
		}

		return $validity;

	}
}

if ( ! function_exists( 'wptabspro_customize_validate_required' ) ) {
	/**
	 *
	 * Required validate for Customizer
	 *
	 * @param object $validity Required validity.
	 * @param string $value The Required.
	 * @param object $wp_customize Customize option.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wptabspro_customize_validate_required( $validity, $value, $wp_customize ) {

		if ( empty( $value ) ) {
			$validity->add( 'required', esc_html__( 'Error! This field is required!', 'wp-expand-tabs-free' ) );
		}

		return $validity;

	}
}

if ( ! function_exists( 'wptabspro_customize_validate_url' ) ) {
	/**
	 *
	 * URL validate for Customizer
	 *
	 * @param object $validity URL validity.
	 * @param string $value The URL.
	 * @param object $wp_customize Customize option.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wptabspro_customize_validate_url( $validity, $value, $wp_customize ) {

		if ( ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
			$validity->add( 'required', esc_html__( 'Please write a valid url!', 'wp-expand-tabs-free' ) );
		}

		return $validity;

	}
}
