<?php

/**
 * class Cart
 *
 * @link       https://appcheap.io
 * @since      1.0.0
 * @author     ngocdt
 */

namespace AppBuilder;

defined( 'ABSPATH' ) || exit;

class Cart {
	public function __construct() {
		add_filter( 'woocommerce_session_handler', array( $this, 'woocommerce_session_handler' ), 99, 1 );
		add_action( 'woocommerce_load_cart_from_session', array( $this, 'load_cart_from_session' ), 10 );
		add_action( 'woocommerce_thankyou', array( $this, 'handle_checkout_success' ), 10 );
	}

	/**
	 *
	 * Handle session
	 *
	 * @param $handler
	 *
	 * @return string
	 */
	public function woocommerce_session_handler( $handler ) {

		// if ( isset( $_SERVER['CIRILLA_CART_TOKEN'] ) && class_exists( 'WC_Session' ) ) {
		// include_once APP_BUILDER_ABSPATH . 'includes' . DIRECTORY_SEPARATOR . 'SessionHandler.php';
		// $handler = 'AppBuilderSessionHandler';
		// }

		return $handler;
	}

	/**
	 * Restore cart for web
	 */
	public function load_cart_from_session() {

		global $wpdb;

		$table = $wpdb->prefix . APP_BUILDER_CART_TABLE;

		// If not exist cart key
		if ( ! isset( $_REQUEST['cart_key_restore'] ) ) {
			return;
		}

		// Is if rest api
		if ( WC()->is_rest_api_request() ) {
			return;
		}

		if ( ! session_id() ) {
			session_start();
		}

		wc_nocache_headers();

		$cart_key = trim( wp_unslash( $_REQUEST['cart_key_restore'] ) );

		$value = $wpdb->get_var( $wpdb->prepare( "SELECT cart_value FROM $table WHERE cart_key = %s", $cart_key ) );

		if ( ! $value ) {
			return;
		}

		$cart_data = maybe_unserialize( $value );

		// Clear old cart
		WC()->cart->empty_cart();

		// Set new cart data
		WC()->session->set( 'cart', maybe_unserialize( $cart_data['cart'] ) );
		WC()->session->set( 'cart_totals', maybe_unserialize( $cart_data['cart_totals'] ) );
		WC()->session->set( 'applied_coupons', maybe_unserialize( $cart_data['applied_coupons'] ) );
		WC()->session->set( 'coupon_discount_totals', maybe_unserialize( $cart_data['coupon_discount_totals'] ) );
		WC()->session->set( 'coupon_discount_tax_totals', maybe_unserialize( $cart_data['coupon_discount_tax_totals'] ) );
		WC()->session->set( 'removed_cart_contents', maybe_unserialize( $cart_data['removed_cart_contents'] ) );

		$customer = maybe_unserialize( $cart_data['customer'] );

		WC()->customer->set_props(
			array(
				'billing_first_name'  => null,
				'billing_last_name'   => null,
				'billing_company'     => null,
				'billing_address_1'   => null,
				'billing_address_2'   => null,
				'billing_city'        => $customer['city'] ?? null,
				'billing_state'       => $customer['state'] ?? null,
				'billing_postcode'    => $customer['postcode'] ?? null,
				'billing_country'     => $customer['country'] ?? null,
				'billing_email'       => null,
				'billing_phone'       => null,
				'shipping_first_name' => null,
				'shipping_last_name'  => null,
				'shipping_company'    => null,
				'shipping_address_1'  => null,
				'shipping_address_2'  => null,
				'shipping_city'       => $customer['shipping_city'] ?? null,
				'shipping_state'      => $customer['shipping_state'] ?? null,
				'shipping_postcode'   => $customer['shipping_postcode'] ?? null,
				'shipping_country'    => $customer['shipping_country'] ?? null,
			)
		);

		WC()->customer->save();

		// Keep the cart key in session
		$_SESSION['cart_key'] = $cart_key;
	}

	/**
	 *
	 * Handle action after user go to checkout success page
	 *
	 * @param $order_id
	 */
	public function handle_checkout_success( $order_id ) {
		if ( ! session_id() ) {
			session_start();
		}

		if ( isset( $_SESSION['cart_key'] ) ) {
			global $wpdb;

			// Delete cart from database.
			$wpdb->delete( $wpdb->prefix . APP_BUILDER_CART_TABLE, array( 'cart_key' => $_SESSION['cart_key'] ) );

			// unset cart key in session
			unset( $_SESSION['cart_key'] );
		}
	}
}
