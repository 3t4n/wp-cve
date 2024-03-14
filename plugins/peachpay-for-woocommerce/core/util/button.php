<?php
/**
 * PeachPay Button API
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * Reset button preferences to the defaults
 *
 * @param string $args Button setting section.
 */
function peachpay_reset_button( $args ) {
	$button_options   = get_option( 'peachpay_express_checkout_button' );
	$branding_options = get_option( 'peachpay_express_checkout_branding' );
	if ( 'button_appearance' === $args ) {
		$branding_options['button_color']                      = PEACHPAY_DEFAULT_BACKGROUND_COLOR;
		$branding_options['button_text_color']                 = PEACHPAY_DEFAULT_TEXT_COLOR;
		$button_options['button_icon']                         = 'none';
		$button_options['button_border_radius']                = 5;
		$button_options['peachpay_button_text']                = '';
		$button_options['button_effect']                       = 'fade';
		$button_options['button_display_payment_method_icons'] = true;
	} elseif ( 'button_pages' === $args ) {
		$button_options['display_on_product_page']        = true;
		$button_options['product_button_alignment']       = 'left';
		$button_options['product_button_position']        = 'beforebegin';
		$button_options['product_button_mobile_position'] = 'default';
		$button_options['button_width_product_page']      = 220;

		$button_options['cart_button_alignment']  = 'full';
		$button_options['button_width_cart_page'] = 220;

		$button_options['checkout_button_alignment']  = 'center';
		$button_options['button_width_checkout_page'] = 320;
		$button_options['checkout_header_text']       = '';
		$button_options['checkout_subtext_text']      = '';

		$button_options['cart_page_enabled']     = true;
		$button_options['checkout_page_enabled'] = true;
		$button_options['mini_cart_enabled']     = true;
	} elseif ( 'floating_button' === $args ) {
		$button_options['floating_button_icon']       = 'shopping_cart';
		$button_options['floating_button_size']       = 70;
		$button_options['floating_button_icon_size']  = 35;
		$button_options['floating_button_alignment']  = 'right';
		$button_options['floating_button_bottom_gap'] = 27;
		$button_options['floating_button_side_gap']   = 45;
		// phpcs:ignore
		// This one is not prefixed with floating button because peachpay_button_hide_html() assumes the option name starts with disabled_
		$button_options['floating_button_enabled'] = true;
	} elseif ( 'button_shadow' === $args ) {
		$button_options['button_shadow_enabled'] = false;
	}

	update_option( 'peachpay_express_checkout_button', $button_options );
}
