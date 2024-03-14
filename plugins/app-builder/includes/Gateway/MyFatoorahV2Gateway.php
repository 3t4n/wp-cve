<?php


/**
 * class MyFatoorahV2Gateway
 *
 * @link       https://appcheap.io
 * @since      3.1.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Gateway;

defined( 'ABSPATH' ) || exit;

use AppBuilder\Data\CartData;

class MyFatoorahV2Gateway {

	public function confirm_payment( $request ) {

		if ( ! class_exists( '\MyfatoorahWoocommerce' ) ) {
			return new \WP_Error(
				"app_builder_confirm_payment",
				__( "The plugin Myfatoorah not install yet.", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		$orderId = $request->get_param( 'oid' );

		if ( ! $orderId ) {
			return new \WP_Error(
				"app_builder_confirm_payment",
				__( "The Order is not found. Please, contact the store admin.", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		$order         = new \WC_Order( $orderId );
		$paymentMethod = $order->get_payment_method();
		if ( $paymentMethod != 'myfatoorah_v2' && $paymentMethod != 'myfatoorah_embedded' ) {
			return new \WP_Error(
				"app_builder_confirm_payment",
				__( "Wrong payment method.", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		//get MyFatoorah object
		$calss   = 'WC_Gateway_' . ucfirst( $paymentMethod );
		$gateway = new $calss;

		//pending, processing, on-hold, completed, cancelled, refunded, failed, or customed
		$status = $order->get_status();

		//go back if NOT pending, failed, on-hold
		if ( $status != 'pending' && $status != 'failed' && $status != 'on-hold' ) {
			return new \WP_Error(
				"app_builder_confirm_payment",
				__( "The payment status not pending, failed, on-hold.", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		//get Payment Id
		$KeyType   = 'PaymentId';
		$paymentId = $request->get_param( 'paymentId' );
		$key       = $paymentId ? sanitize_text_field( $paymentId ) : null;
		if ( ! $key ) {
			$KeyType = 'InvoiceId';
			$key     = get_post_meta( $orderId, 'InvoiceId', true );
			if ( ! $key ) {
				return new \WP_Error(
					"app_builder_confirm_payment",
					__( "The Order is not found. Please, contact the store admin.", "app-builder" ),
					array(
						'status' => 403,
					)
				);
			}
		}

		//When "thankyou" order-received page is reached …
		try {
			$error = $gateway->checkStatus( $key, $KeyType, $order );
		} catch ( \Exception $ex ) {
			$error = $ex->getMessage();
		}

		if ( $error ) {
			return new \WP_Error(
				"app_builder_confirm_payment",
				$error,
				array(
					'status' => 403,
				)
			);
		}

		$cart = new CartData();
		$cart->remove_cart_by_cart_key( $request->get_param( 'cart_key' ) );

		return [
			'redirect'           => 'order',
			'orderId'            => $orderId,
			'order_received_url' => $this->redirectToSuccessURL( $gateway, $orderId, $order ),
		];
	}

	public function redirectToSuccessURL( $gateway, $orderId, $order ) {
		if ( $gateway->success_url ) {
			return $gateway->success_url . '/' . $orderId . '/?key=' . $order->get_order_key();
		}

		return $order->get_checkout_order_received_url();
	}
}
