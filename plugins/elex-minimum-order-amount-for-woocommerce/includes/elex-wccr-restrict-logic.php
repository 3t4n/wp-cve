<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Elex_Restrict_Logic {
	public function __construct() {
		add_action( 'woocommerce_check_cart_items', array( $this, 'elex_wccr_check_cart_quantities' ) );
	}


	public function my_custom_checkout_field_process() {
		remove_action('woocommerce_proceed_to_checkout',
		'woocommerce_button_proceed_to_checkout', 20 );
		echo '<a href="#" class="checkout-button button alt wc-forward">
		Proceed to checkout </a>';
	}

	public function elex_wccr_check_cart_quantities() {
		global $woocommerce;
		$restrictions = get_option( 'elex_wccr_checkout_restriction_settings' );
		if ( is_user_logged_in() ) {
			$user_role = wp_get_current_user()->roles[0];
		} else {
			$user_role = 'unregistered_user';
		}
		$restrict_checkout = false;
		$restrict_msg = '';
		$conversion_rate = apply_filters( 'elex_wccr_conversion_rate', 1 );
		$min_price = ( isset( $restrictions[ $user_role ]['min_price'] ) && ! empty( $restrictions[ $user_role ]['min_price'] ) ) ? $restrictions[ $user_role ]['min_price'] * $conversion_rate : '';
		$max_price = ( isset( $restrictions[ $user_role ]['max_price'] ) && ! empty( $restrictions[ $user_role ]['max_price'] ) ) ? $restrictions[ $user_role ]['max_price'] * $conversion_rate : '';
		if ( is_array( $restrictions ) && in_array( $user_role, array_keys( $restrictions ) ) && isset( $restrictions[ $user_role ]['enable_restriction'] ) ) {
			if ( $min_price && ( $min_price > $woocommerce->cart->subtotal ) ) {
				$restrict_checkout = true;
				$restrict_msg = $restrictions[ $user_role ]['error_message'];
			}

			if ( ! $restrict_checkout && $max_price && ( $max_price < $woocommerce->cart->subtotal ) ) {
				$restrict_checkout = true;
				$restrict_msg = $restrictions[ $user_role ]['error_message'];
			}
		}
		if ( $restrict_checkout ) {
			add_action('woocommerce_proceed_to_checkout', array( $this,'my_custom_checkout_field_process'));
			wc_add_notice( html_entity_decode( $restrict_msg ), 'error' );
			
		}
	}
}
new Elex_Restrict_Logic();
