<?php


/**
 * class VnpayGateway
 *
 * @link       https://appcheap.io
 * @since      3.1.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Gateway;

defined( 'ABSPATH' ) || exit;

use AppBuilder\Data\CartData;

class VnpayGateway {

	public function confirm_payment( $request ) {

		$order_id = $request->get_param( 'order_id' );
		$cart_key = $request->get_param( 'cart_key' );

		if ( empty( $order_id ) || empty( $cart_key ) ) {
			return new \WP_Error(
				"app_builder_confirm_payment",
				__( "Order ID or Cart Key not provider.", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return new \WP_Error(
				"app_builder_confirm_payment",
				__( "Order not found.", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		if ( $order->get_status() == 'processing' ) {
			$cart = new CartData();
			$cart->remove_cart_by_cart_key( $cart_key );

			return [
				'redirect'           => 'order',
				'order_id'           => $order_id,
				'order_key'          => $order->get_order_key(),
				'order_received_url' => $order->get_checkout_order_received_url(),
			];
		}

		return [
			'redirect' => 'checkout',
		];
	}

}
