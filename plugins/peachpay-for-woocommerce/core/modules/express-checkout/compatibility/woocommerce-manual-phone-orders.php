<?php
/**
 * PeachPay express checkout compatibility for "Manual Order & Phone Order".
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * Hides the PeachPay for WooCommerce Express checkout button on the manual phone orders form.
 *
 * @param bool $hide_button Whether to hide the button.
 * @return bool
 */
function pp_checkout_hide_phone_orders_button( $hide_button ) {
	global $post;

	if ( class_exists( 'IGN_Manual_Phone_Orders' ) ) {
		if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'woocommerce_manual_phone_order' ) ) {
			return true;
		}
	}

	return $hide_button;
}

add_filter( 'pp_checkout_checkout_page_button_hide', 'pp_checkout_hide_phone_orders_button' );
add_filter( 'pp_checkout_floating_button_hide', 'pp_checkout_hide_phone_orders_button' );
