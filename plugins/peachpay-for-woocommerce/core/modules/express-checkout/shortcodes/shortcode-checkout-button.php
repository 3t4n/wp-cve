<?php
/**
 * PeachPay Express Checkout button shortcode
 *
 * @package PeachPay
 */

defined( 'ABSPATH' ) || exit;

/**
 * PeachPay Express Checkout button shortcode
 *
 * @param array $atts The shortcode configuration attributes.
 */
function pp_checkout_button_shortcode( $atts ) {
	if ( ! pp_should_display_public() ) {
		return;
	}

	if ( isset( $atts['border_radius'] ) && null !== $atts['border_radius'] ) {
		$atts['border_radius'] = intval( $atts['border_radius'] );
	}

	if ( isset( $atts['width'] ) && null !== $atts['width'] ) {
		$atts['width'] = intval( $atts['width'] );
	}

	if ( isset( $atts['product_id'] ) && null !== $atts['product_id'] ) {
		$atts['product_id'] = intval( $atts['product_id'] );
	}

	if ( isset( $atts['display_available_payment_icons'] ) && null !== $atts['display_available_payment_icons'] ) {
		$atts['display_available_payment_icons'] = filter_var( $atts['display_available_payment_icons'], FILTER_VALIDATE_BOOLEAN );
	}

	if ( isset( $atts['available_payment_icons_maximum'] ) && null !== $atts['available_payment_icons_maximum'] ) {
		$atts['available_payment_icons_maximum'] = intval( $atts['available_payment_icons_maximum'] );
	}

	$atts = shortcode_atts(
		array(
			'text'                            => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'peachpay_button_text', __( 'Express checkout', 'peachpay-for-woocommerce' ) ),
			'icon_class'                      => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_icon', '' ),
			'effect_class'                    => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_effect', 'none' ),
			'text_color'                      => peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_text_color', PEACHPAY_DEFAULT_TEXT_COLOR ),
			'background_color'                => peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_color', PEACHPAY_DEFAULT_BACKGROUND_COLOR ),
			'border_radius'                   => intval( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_border_radius', 5 ) ),
			'width'                           => 220,
			'display_available_payment_icons' => filter_var( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_display_payment_method_icons', false ), FILTER_VALIDATE_BOOLEAN ),
			'available_payment_icons_maximum' => 4,

			'product_id'                      => null,
		),
		$atts
	);

	$atts['custom_classes']    = '';
	$atts['custom_styles']     = '';
	$atts['custom_attributes'] = array(
		'href' => pp_checkout_permalink(),
	);

	if ( isset( $atts['product_id'] ) && null !== $atts['product_id'] ) {
		$product = wc_get_product( $atts['product_id'] );
		if ( is_null( $product ) || ! $product ) {
			if ( pp_should_display_admin() ) {
				return 'Error: Product id ' . $atts['product_id'] . ' not found. Shortcode usage: [peachpay product_id=123] where 123 is the id of a valid product.';
			} else {
				return;
			}
		}

		$url   = $product->add_to_cart_url();
		$query = wp_parse_url( $url, PHP_URL_QUERY );

		$atts['custom_attributes']['href']                    = pp_checkout_permalink() . '?' . $query;
		$atts['custom_attributes']['data-activation-trigger'] = 'shortcode';
	}

	$atts['custom_styles'] = 'text-align:center;';

	return pp_checkout_button_template( $atts );
}
