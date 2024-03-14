<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Woo_Payment_Add_Discounts {

	public function __construct() {
		// Load public-facing JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'woocommerce_cart_calculate_fees', array( $this, 'wpd_add_discount' ), 10 );
		add_filter( 'woocommerce_gateway_title', array( $this, 'wpd_payment_method_title' ), 10, 2 );
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'wpd_update_order_data' ), 10 );
	}

	/**
	 * Script file included
	 */
	public function enqueue_scripts() {
		if ( is_checkout() ) {
			wp_enqueue_script( 'woo-payment-discounts', plugins_url( 'assets/js/wpd_custom.js', plugin_dir_path( __FILE__ ) ), array( 'wc-checkout' ), false );
		}
	}

	/**
	 * Calcule the discount amount.
	 */
	protected function calculate_discount( $type, $value, $subtotal ) {
		if ( $type == 'percentage' ) {
			$value = ( $subtotal / 100 ) * ( $value );
		}

		return $value;
	}

	/**
	 * Generate the discount name.
	 */
	protected function discount_name( $value, $gateway ) {
		if ( strstr( $value, '%' ) ) {
			return sprintf( __( 'Discount for %s (%s off)', 'woo-payment-discounts' ), esc_attr( $gateway->title ), $value );
		}

		return sprintf( __( 'Discount for %s', 'woo-payment-discounts' ), esc_attr( $gateway->title ) );
	}

	/**
	 * Display the discount in payment method title.
	 */
	public function wpd_payment_method_title( $title, $id ) {
		if ( ! is_checkout() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			return $title;
		}

		$settings = get_option( 'woo_payment_discounts_setting' );
		$settings = maybe_unserialize( $settings );
		if ( isset( $settings[ $id ]['amount'] ) && 0 < $settings[ $id ]['amount'] ) {
			$discount = $settings[ $id ]['amount'];
			if ( $settings[ $id ]['type'] == 'percentage' ) {
				$value = $discount . '%';
			} else {
				$value = wc_price( $discount );
			}
			$title .= ' <small>(' . sprintf( __( '%s off', 'woo-payment-discounts' ), $value ) . ')</small>';
		}

		return $title;
	}

	/**
	 * Add discount.
	 */
	public function wpd_add_discount( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) || is_cart() ) {
			return;
		}

		// Gets the settings.
		$gateways = get_option( 'woo_payment_discounts_setting' );
		$gateways = maybe_unserialize( $gateways );
		if ( isset( $gateways[ WC()->session->chosen_payment_method ] ) ) {
			$value = $gateways[ WC()->session->chosen_payment_method ]['amount'];
			$type  = $gateways[ WC()->session->chosen_payment_method ]['type'];
			if ( apply_filters( 'woo_payment_discounts_apply_discount', 0 < $value, $cart ) ) {
				$payment_gateways = WC()->payment_gateways->payment_gateways();
				$gateway          = $payment_gateways[ WC()->session->chosen_payment_method ];
				$discount_name    = $this->discount_name( $value, $gateway );
				$cart_discount    = $this->calculate_discount( $type, $value, $cart->cart_contents_total ) * - 1;
				$cart->add_fee( $discount_name, $cart_discount, true );
			}
		}
	}

	/**
	 * Remove the discount in the payment method title.
	 */
	public function wpd_update_order_data( $order_id ) {
		$payment_method_title     = get_post_meta( $order_id, '_payment_method_title', true );
		$new_payment_method_title = preg_replace( '/<small>.*<\/small>/', '', $payment_method_title );
		// Save the new payment method title.
		$new_payment_method_title = sanitize_text_field( $new_payment_method_title );
		update_post_meta( $order_id, '_payment_method_title', $new_payment_method_title );
	}

}

new Woo_Payment_Add_Discounts();
