<?php
/**
 * @package     Splitit_WooCommerce_Plugin
 *
 * File - class-splitit-flexfields-payment-plugin-checkout.php
 * Methods for work with checkout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // @Exit if accessed directly
}

/**
 * Class SplitIt_FlexFields_Payment_Plugin_Checkout
 */
class SplitIt_FlexFields_Payment_Plugin_Checkout {


	/**
	 * Create checkout
	 *
	 * @param object $order_info Information about order.
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function create_checkout( $order_info ) {
		$user_data = json_decode( $order_info->user_data, true );
		$cart_info = json_decode( $order_info->wc_cart, true );

		$order_data = array(
			'status'      => apply_filters( 'woocommerce_default_order_status', 'processing' ),
			'customer_id' => $order_info->user_id,
		);

		$order = wc_create_order( $order_data );

		$order = $this->add_product_to_order( $cart_info, $order );
		$order = $this->add_address_to_order( $user_data, $order );

		if ( $order_info->shipping_method_id ) {
			$order->add_shipping( $this->get_shipping_to_order( $order_info ) );
		}

		if ( $order_info->coupon_code && $order_info->coupon_amount ) {
			$order = $this->add_discount_to_order( $cart_info, $order );
		}

		$order->set_payment_method( $user_data['payment_method'] );

		if ( is_wp_error( $order->get_id() ) ) {
			$message = $order->get_id()->get_error_message();
			$data    = array(
				'user_id' => get_current_user_id(),
				'method'  => 'create_checkout() Checkout',
			);
			SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, $message, 'error' );
			throw new Exception( $message );
		}
		$order->calculate_totals();

		// Clean-up cart if order created.
		$wc_session_handler = new WC_Session_Handler();
		$wc_session_handler->delete_session( $order_info->session_id );

		return $order->get_id();
	}

	/**
	 * Get shipping
	 *
	 * @param object $order_info Information about order.
	 *
	 * @return WC_Shipping_Rate
	 */
	public function get_shipping_to_order( $order_info ) {
		$shipping_cost = wc_format_decimal( $order_info->shipping_method_cost );
		return new WC_Shipping_Rate( '', $order_info->shipping_method_title, $shipping_cost, array(), $order_info->shipping_method_id );
	}

	/**
	 * Add discount to order
	 *
	 * @param array  $user_data User data.
	 * @param object $order Order.
	 *
	 * @return mixed
	 */
	public function add_discount_to_order( $user_data, $order ) {
		foreach ( $user_data['coupon_discount_totals'] as $code => $amount ) {
			$order->add_coupon( $code, wc_format_decimal( $amount ) );
		}

		return $order;
	}

	/**
	 * Add address to order
	 *
	 * @param array  $user_data User data.
	 * @param object $order Order.
	 *
	 * @return mixed
	 */
	public function add_address_to_order( $user_data, $order ) {
		$billing_address = $this->get_billing_address( $user_data );

		if ( isset( $user_data['ship_to_different_address'] ) && 1 === (int) $user_data['ship_to_different_address'] ) {
			$shipping_address = $this->get_shipping_address( $user_data );
		} else {
			$shipping_address = $billing_address;
		}

		$order->set_address( $billing_address, 'billing' );
		$order->set_address( $shipping_address, 'shipping' );

		return $order;
	}

	/**
	 * Add product to order
	 *
	 * @param array  $cart_info Cart information.
	 * @param object $order Order.
	 *
	 * @return mixed
	 */
	public function add_product_to_order( $cart_info, $order ) {
		$wc_product_factory = new WC_Product_Factory();

		foreach ( $cart_info['cart_contents'] as $cart_product ) {
			$quantity        = (int) $cart_product['quantity'];
			$args            = array();
			$args ['totals'] = array(
				'subtotal'     => $cart_product['line_subtotal'],
				'subtotal_tax' => $cart_product['line_subtotal_tax'],
				'total'        => $cart_product['line_total'],
				'tax'          => $cart_product['line_tax'],
				'tax_data'     => $cart_product['line_tax_data'],
			);

			if ( ! empty( $cart_product['variation'] ) ) {
				$args['variation'] = $cart_product['variation'];
				$order->add_product( $wc_product_factory->get_product( $cart_product['variation_id'] ), $quantity, $args );
			} else {
				$order->add_product( $wc_product_factory->get_product( $cart_product['product_id'] ), $quantity, $args );
			}
		}

		return $order;
	}

	/**
	 * Get billing address
	 *
	 * @param array $order_info Order information.
	 *
	 * @return array|null[]
	 */
	public function get_billing_address( $order_info ) {
		return array(
			'first_name' => isset( $order_info['billing_first_name'] ) ? $order_info['billing_first_name'] : null,
			'last_name'  => isset( $order_info['billing_last_name'] ) ? $order_info['billing_last_name'] : null,
			'company'    => isset( $order_info['billing_company'] ) ? $order_info['billing_company'] : null,
			'email'      => isset( $order_info['billing_email'] ) ? $order_info['billing_email'] : null,
			'phone'      => isset( $order_info['billing_phone'] ) ? $order_info['billing_phone'] : null,
			'address_1'  => isset( $order_info['billing_address_1'] ) ? $order_info['billing_address_1'] : null,
			'address_2'  => isset( $order_info['billing_address_2'] ) ? $order_info['billing_address_2'] : null,
			'city'       => isset( $order_info['billing_city'] ) ? $order_info['billing_city'] : null,
			'state'      => isset( $order_info['billing_state'] ) ? $order_info['billing_state'] : null,
			'country'    => isset( $order_info['billing_country'] ) ? $order_info['billing_country'] : null,
			'postcode'   => isset( $order_info['billing_postcode'] ) ? $order_info['billing_postcode'] : null,
		);
	}

	/**
	 * Get shipping address
	 *
	 * @param array $order_info Order information.
	 *
	 * @return array|null[]
	 */
	public function get_shipping_address( $order_info ) {
		return array(
			'first_name' => isset( $order_info['shipping_first_name'] ) ? $order_info['shipping_first_name'] : null,
			'last_name'  => isset( $order_info['shipping_last_name'] ) ? $order_info['shipping_last_name'] : null,
			'company'    => isset( $order_info['shipping_company'] ) ? $order_info['shipping_company'] : null,
			'email'      => isset( $order_info['shipping_email'] ) ? $order_info['shipping_email'] : null,
			'phone'      => isset( $order_info['shipping_phone'] ) ? $order_info['shipping_phone'] : null,
			'address_1'  => isset( $order_info['shipping_address_1'] ) ? $order_info['shipping_address_1'] : null,
			'address_2'  => isset( $order_info['shipping_address_2'] ) ? $order_info['shipping_address_2'] : null,
			'city'       => isset( $order_info['shipping_city'] ) ? $order_info['shipping_city'] : null,
			'state'      => isset( $order_info['shipping_state'] ) ? $order_info['shipping_state'] : null,
			'country'    => isset( $order_info['shipping_country'] ) ? $order_info['shipping_country'] : null,
			'postcode'   => isset( $order_info['shipping_postcode'] ) ? $order_info['shipping_postcode'] : null,
		);
	}
}
