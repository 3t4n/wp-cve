<?php
/**
 * Support for the WooCommerce Bookings plugin
 * Plugin: https://woocommerce.com/products/woocommerce-bookings/
 *
 * @package PeachPay
 */

defined( 'PEACHPAY_ABSPATH' ) || exit;

/**
 * Initializes Woocommerce Bookings compatibility.
 */
function peachpay_woocommerce_bookings_init() {
	if ( peachpay_get_settings_option( 'peachpay_currency_options', 'enabled' ) ) {
		add_filter( 'woocommerce_bookings_calculated_booking_cost_success_output', 'peachpay_wcbookings_adjust_calculated_cost_output', 100, 3 );
		add_filter( 'woocommerce_get_price_html', 'peachpay_wcbookings_get_price_html', 100, 2 );
	}
}
add_action( 'peachpay_init_compatibility', 'peachpay_woocommerce_bookings_init' );

/**
 * Modifies how WooCommerce Bookings outputs the calculated cost for a booking.
 * PeachPay needs to convert the price that WooCommerce Bookings calculates.
 * Reference class that runs this filter: class-wc-bookings-ajax.php
 *
 * @param string     $output The current html output.
 * @param string     $display_price The wc_price formatted price for display.
 * @param WC_Product $product The product.
 *
 * @return string
 */
function peachpay_wcbookings_adjust_calculated_cost_output( $output, $display_price, $product ) {
	require_once PEACHPAY_ABSPATH . 'core/modules/currency-switcher/currency-convert.php';
	return __( 'Booking cost', 'peachpay-for-woocommerce' ) . ': <strong>' . wc_price( peachpay_update_raw_price( $display_price, 'product' ) ) . $price_suffix . '</strong>';
}

/**
 * Background: WooCommerce Bookings has some logic to show a sale price (crossed
 * out text followed by not crossed out text) however a sale doens't appear to
 * exist for booking products and one of the prices they compare is converted by
 * PeachPay while the other is not (thus always resulting in crossed out text
 * when the currency is not set to the base currency).
 *
 * This function is a copy of the function WC_Product_Booking::get_price_html()
 * in the file class-wc-product-booking.php without the possibility of crossed
 * out prices.
 *
 * @param string     $price_html The current html output.
 * @param WC_Product $product The product.
 *
 * @return string
 */
function peachpay_wcbookings_get_price_html( $price_html, $product ) {
	if ( is_a( $product, 'WC_Product_Booking' ) ) {
		$base_price = WC_Bookings_Cost_Calculation::calculated_base_cost( $product );

		if ( 'incl' === get_option( 'woocommerce_tax_display_shop' ) ) {
			if ( function_exists( 'wc_get_price_excluding_tax' ) ) {
				$display_price = wc_get_price_including_tax(
					$product,
					array(
						'qty'   => 1,
						'price' => $base_price,
					)
				);
			} else {
				$display_price = $product->get_price_including_tax( 1, $base_price );
			}
		} elseif ( function_exists( 'wc_get_price_excluding_tax' ) ) {
				$display_price = wc_get_price_excluding_tax(
					$product,
					array(
						'qty'   => 1,
						'price' => $base_price,
					)
				);
		} else {
			$display_price = $product->get_price_excluding_tax( 1, $base_price );
		}

		$display_price = peachpay_update_raw_price( $display_price, 'product' );

		if ( $display_price ) {
			if ( $product->has_additional_costs() ) {
				/* translators: 1: display price */
				$price_html = sprintf( __( 'From: %s', 'peachpay-for-woocommerce' ), wc_price( $display_price ) ) . $product->get_price_suffix();
			} else {
				$price_html = wc_price( $display_price ) . $product->get_price_suffix();
			}
		} elseif ( ! $product->has_additional_costs() ) {
			$price_html = __( 'Free', 'peachpay-for-woocommerce' );
		} else {
			$price_html = '';
		}

		return $price_html;
	}

	return $price_html;
}
