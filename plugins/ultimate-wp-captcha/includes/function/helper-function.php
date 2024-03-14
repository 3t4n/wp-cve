<?php
/**
 * Helper function
 *
 * @version 1.0.0
 * @package uwc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 * @return string|array
 */
function uwc_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'uwc_clean', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}
/**
 * Get data if set, otherwise return a default value or null. Prevents notices when data is not set.
 *
 * @since  1.0.0
 * @param  mixed  $var     Variable.
 * @param  string $default Default value.
 * @return mixed
 */
function uwc_get_var( &$var, $default = null ) {
	return isset( $var ) ? $var : $default;
}
/**
 * Get option value based on option name.
 *
 * @since  1.0.0
 * @param  string $option option name.
 * @return string|array
 */
function uwc_get_option( $option = '' ) {
	$get_option = get_option( 'uwc_setting_data' );
	if ( isset( $get_option[ $option ] ) ) {
		$get_option = $get_option[ $option ];
	}
	return $get_option;
}
/**
 * Get the location where captcha is being displayed.
 *
 * @since  1.0.0
 * @return string
 */
function uwc_get_captcha_display_location() {
	$captcha_display_location = array(
		'wp_login_form'           => __( 'WordPress Login Form', 'ultimate-wp-captcha' ),
		'wp_registration_form'    => __( 'WordPress Registration Form', 'ultimate-wp-captcha' ),
		'wp_reset_passwordd_form' => __( 'WordPress Reset password form', 'ultimate-wp-captcha' ),
		'wp_comments_form'        => __( 'WordPress Comments form', 'ultimate-wp-captcha' ),
	);
	if ( true === uwc_is_woocommerce_activated() ) {
		$captcha_display_location['wc_login_form']           = __( 'WooCommerce Login form', 'ultimate-wp-captcha' );
		$captcha_display_location['wc_registration_form']    = __( 'WooCommerce Registration form', 'ultimate-wp-captcha' );
		$captcha_display_location['wc_reset_passwordd_form'] = __( 'WooCommerce Reset password form', 'ultimate-wp-captcha' );
		$captcha_display_location['wc_checkout']             = __( 'WooCommerce Checkout', 'ultimate-wp-captcha' );
	}
	if ( true === uwc_is_learndash_activated() ) {
		$captcha_display_location['ld_login_form']        = __( 'LearnDash Login form', 'ultimate-wp-captcha' );
		$captcha_display_location['ld_registration_form'] = __( 'LearnDash Registration form', 'ultimate-wp-captcha' );
	}
	/**
	 * Filters captcha display location.
	 *
	 * @since 1.2.0
	 * @param array $captcha_display_location An array of captcha display location.
	 */
	return apply_filters( 'uwc_get_captcha_for', $captcha_display_location );
}
/**
 * Check if WooCommerce is activated.
 *
 * @since  1.0.0
 * @return bool
 */
function uwc_is_woocommerce_activated() {
	if ( class_exists( 'woocommerce' ) ) {
		return true;
	}
	return false;
}
/**
 * Check if LearnDash is activated.
 *
 * @since  1.0.0
 * @return bool
 */
function uwc_is_learndash_activated() {
	if ( class_exists( 'SFWD_LMS' ) ) {
		return true;
	}
	return false;
}
/**
 * It returns true if the given key is exist in enable_captcha_for array or this array is not exist in option yet, false otherwise.
 *
 * @since  1.0.0
 * @param  string $key key name.
 * @return bool
 */
function uwc_is_this_location_checked( $key ) {
	$uwc_setting_data = get_option( 'uwc_setting_data' );
	if ( ! isset( $uwc_setting_data['enable_captcha_for'] ) || in_array( $key, $uwc_setting_data['enable_captcha_for'], true ) ) {
		return true;
	}
	return false;
}
