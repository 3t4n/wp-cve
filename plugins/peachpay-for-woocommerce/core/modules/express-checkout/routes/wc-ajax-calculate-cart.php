<?php
/**
 * WC ajax request for calculating the checkout totals.
 *
 * @package PeachPay
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns all details needed to represent the current woocommerce cart in the PeachPay express checkout.
 */
function pp_checkout_wc_ajax_calculate_checkout() {
	define( 'PEACHPAY_CHECKOUT', 1 );
	define( 'WOOCOMMERCE_CHECKOUT', true ); // Define wc checkout to educate fedex/wc that express-checkout is similar to wc checkout.

	try {

		//PHPCS:disable WordPress.Security.NonceVerification.Missing

		$billing_email = isset( $_POST['billing_email'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_email'] ) ) : null;
		if ( null !== $billing_email ) {
			WC()->customer->set_billing_email( $billing_email );
		}

		$billing_first_name = isset( $_POST['billing_first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_first_name'] ) ) : null;
		if ( null !== $billing_first_name ) {
			WC()->customer->set_billing_first_name( $billing_first_name );
			WC()->customer->set_shipping_first_name( $billing_first_name );
		}

		$billing_last_name = isset( $_POST['billing_last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_last_name'] ) ) : null;
		if ( null !== $billing_last_name ) {
			WC()->customer->set_billing_last_name( $billing_last_name );
			WC()->customer->set_shipping_last_name( $billing_last_name );
		}

		$billing_phone = isset( $_POST['billing_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) : null;
		if ( null !== $billing_phone ) {
			WC()->customer->set_billing_phone( $billing_phone );
			WC()->customer->set_shipping_phone( $billing_phone );
		}

		$billing_company = isset( $_POST['billing_company'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_company'] ) ) : null;
		if ( null !== $billing_company ) {
			WC()->customer->set_billing_company( $billing_company );
			WC()->customer->set_shipping_company( $billing_company );
		}

		$billing_address_1 = isset( $_POST['billing_address_1'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_address_1'] ) ) : null;
		if ( null !== $billing_address_1 ) {
			WC()->customer->set_billing_address_1( $billing_address_1 );
			WC()->customer->set_shipping_address_1( $billing_address_1 );
		}

		$billing_address_2 = isset( $_POST['billing_address_2'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_address_2'] ) ) : null;
		if ( null !== $billing_address_2 ) {
			WC()->customer->set_billing_address_2( $billing_address_2 );
			WC()->customer->set_shipping_address_2( $billing_address_2 );
		}

		$billing_city = isset( $_POST['billing_city'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_city'] ) ) : null;
		if ( null !== $billing_city ) {
			WC()->customer->set_billing_city( $billing_city );
			WC()->customer->set_shipping_city( $billing_city );
		}

		$billing_state = isset( $_POST['billing_state'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_state'] ) ) : null;
		if ( null !== $billing_state ) {
			WC()->customer->set_billing_state( $billing_state );
			WC()->customer->set_shipping_state( $billing_state );
		}

		$billing_country = isset( $_POST['billing_country'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_country'] ) ) : null;
		if ( null !== $billing_country ) {
			WC()->customer->set_billing_country( $billing_country );
			WC()->customer->set_shipping_country( $billing_country );
		}

		$billing_postcode = isset( $_POST['billing_postcode'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_postcode'] ) ) : null;
		if ( null !== $billing_postcode ) {
			WC()->customer->set_billing_postcode( $billing_postcode );
			WC()->customer->set_shipping_postcode( $billing_postcode );
		}

		$ship_to_different_address = isset( $_POST['ship_to_different_address'] ) ? sanitize_text_field( wp_unslash( $_POST['ship_to_different_address'] ) ) : null;
		if ( '1' === $ship_to_different_address ) {

			// Since by default the billing fields are copied to the shipping fields, we need to clear
			// them out if the user has selected to ship to a different address. Failing to do this could
			// result in mixture of two addresses which could be wrong.
			WC()->customer->set_shipping_first_name( '' );
			WC()->customer->set_shipping_last_name( '' );
			WC()->customer->set_shipping_phone( '' );
			WC()->customer->set_shipping_company( '' );
			WC()->customer->set_shipping_address_1( '' );
			WC()->customer->set_shipping_address_2( '' );
			WC()->customer->set_shipping_city( '' );
			WC()->customer->set_shipping_state( '' );
			WC()->customer->set_shipping_country( '' );
			WC()->customer->set_shipping_postcode( '' );

			$shipping_first_name = isset( $_POST['shipping_first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_first_name'] ) ) : null;
			if ( null !== $shipping_first_name ) {
				WC()->customer->set_shipping_first_name( $shipping_first_name );
			}

			$shipping_last_name = isset( $_POST['shipping_last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_last_name'] ) ) : null;
			if ( null !== $shipping_last_name ) {
				WC()->customer->set_shipping_last_name( $shipping_last_name );
			}

			$shipping_phone = isset( $_POST['shipping_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_phone'] ) ) : null;
			if ( null !== $shipping_phone ) {
				WC()->customer->set_shipping_phone( $shipping_phone );
			}

			$shipping_company = isset( $_POST['shipping_company'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_company'] ) ) : null;
			if ( null !== $shipping_company ) {
				WC()->customer->set_shipping_company( $shipping_company );
			}

			$shipping_address_1 = isset( $_POST['shipping_address_1'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_address_1'] ) ) : null;
			if ( null !== $shipping_address_1 ) {
				WC()->customer->set_shipping_address_1( $shipping_address_1 );
			}

			$shipping_address_2 = isset( $_POST['shipping_address_2'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_address_2'] ) ) : null;
			if ( null !== $shipping_address_2 ) {
				WC()->customer->set_shipping_address_2( $shipping_address_2 );
			}

			$shipping_city = isset( $_POST['shipping_city'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_city'] ) ) : null;
			if ( null !== $shipping_city ) {
				WC()->customer->set_shipping_city( $shipping_city );
			}

			$shipping_state = isset( $_POST['shipping_state'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_state'] ) ) : null;
			if ( null !== $shipping_state ) {
				WC()->customer->set_shipping_state( $shipping_state );
			}

			$shipping_country = isset( $_POST['shipping_country'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_country'] ) ) : null;
			if ( null !== $shipping_country ) {
				WC()->customer->set_shipping_country( $shipping_country );
			}

			$shipping_postcode = isset( $_POST['shipping_postcode'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_postcode'] ) ) : null;
			if ( null !== $shipping_postcode ) {
				WC()->customer->set_shipping_postcode( $shipping_postcode );
			}
		}

		$shipping_methods = isset( $_POST['shipping_method'] ) ? wc_clean( wp_unslash( $_POST['shipping_method'] ) ) : array(); //PHPCS:ignore
		if ( $shipping_methods && count( $shipping_methods ) > 0 ) {
			$existing_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
			if ( ! is_array( $existing_shipping_methods ) ) {
				$existing_shipping_methods = array();
			}

			foreach ( $shipping_methods as $package_key => $selected_method ) {
				$existing_shipping_methods[ $package_key ] = $selected_method;
			}

			WC()->session->set( 'chosen_shipping_methods', $existing_shipping_methods );
		}

		$payment_method = isset( $_POST['payment_method'] ) ? sanitize_text_field( wp_unslash( $_POST['payment_method'] ) ) : null;
		if ( null !== $payment_method ) {
			WC()->session->set( 'chosen_payment_method', $payment_method );
		}

		if ( isset( $_GET['only-calculate'] ) ) {//PHPCS:ignore

			WC()->cart->calculate_totals();

			wp_send_json( array( 'success' => true ) );
			return;
		}

		//PHPCS:enable WordPress.Security.NonceVerification.Missing

		wp_send_json( peachpay_cart_calculation() );

	} catch ( Exception $error ) {

		wp_send_json(
			array(
				'success' => false,
				'message' => $error->getMessage(),
				'notices' => wc_get_notices(),
			)
		);
	}

	wp_die();
}
