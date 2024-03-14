<?php
/**
 * shortcode [featured_products_slider]
 *
 * @package Product Slider and Carousel with Category for WooCommerce
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Action for shortcode
 *
 * @since 1.0
 */
function wcpscwc_featured_products_slider( $atts, $content ) {

	// Taking some variables
	$atts			= is_array( $atts ) ? $atts : array();
	$atts['type']	= 'featured';
	$result			= wcpscwc_product_slider( $atts, $content );

	if( is_user_logged_in() && current_user_can('manage_options') ) {
		$notice = '<div class="wcpscwc-deprecated">'.sprintf( __('This shortcode is deprecated since version 2.5 and will be removed in future. Kindly use <b>wcpscwc_pdt_slider</b> for slider shortcode instead of this or check plugin <a href="%s" target="_blank">documentation</a> for more help.', 'woo-product-slider-and-carousel-with-category'), 'https://docs.wponlinesupport.com/woo-product-slider-and-carousel-with-category/' ).'</div>';
		return $notice . $result; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
	}

	return $result;
}

// Add Shortcode featured product slider
add_shortcode( 'featured_products_slider', 'wcpscwc_featured_products_slider' );