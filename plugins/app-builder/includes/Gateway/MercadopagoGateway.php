<?php


/**
 * class MercadopagoGateway
 *
 * @link       https://appcheap.io
 * @since      3.1.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Gateway;

defined( 'ABSPATH' ) || exit;

use AppBuilder\Data\CartData;

class MercadopagoGateway {

	public function confirm_payment( $request ) {

		if ( ! class_exists( '\MP' ) ) {
			return new \WP_Error(
				"app_builder_confirm_payment",
				__( "The plugin Mercadopago not install yet.", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		$access_token = get_option( '_mp_access_token_prod', '' );
		if ( 'yes' === get_option( 'checkbox_checkout_test_mode', '' ) || empty( get_option( 'checkbox_checkout_test_mode', '' ) ) ) {
			$access_token = get_option( '_mp_access_token_test', '' );
		}

		$mp        = new \MP( $access_token );
		$paymentId = $request->get_param( 'paymentId' );

		$payment_info = $mp->get(
			'/v1/payments/' . $paymentId,
			array( 'Authorization' => 'Bearer ' . $access_token ),
			false
		);

		if ( ! is_wp_error( $payment_info ) && ( 200 === $payment_info['status'] || 201 === $payment_info['status'] ) ) {
			if ( $payment_info['response'] ) {

				$data = $payment_info['response'];

				$order_key = $data['external_reference'];

				if ( empty( $order_key ) ) {
					return new \WP_Error(
						"app_builder_confirm_payment",
						__( 'External Reference not found' ),
						array(
							'status' => 422,
						)
					);
				}

				$id    = (int) $order_key;
				$order = wc_get_order( $id );

				if ( ! $order ) {
					return new \WP_Error(
						"app_builder_confirm_payment",
						__( 'Order is invalid' ),
						array(
							'status' => 422,
						)
					);

				}

				if ( $order->get_id() !== $id ) {
					return new \WP_Error(
						"app_builder_confirm_payment",
						__( 'Order error' ),
						array(
							'status' => 422,
						)
					);

				}

				$invoice_prefix = get_option( '_mp_store_identificator', 'WC-' );

				$data['external_reference'] = $invoice_prefix . $id;

				do_action( 'valid_mercadopago_ipn_request', $data );

				$cart = new CartData();
				$cart->remove_cart_by_cart_key( $request->get_param( 'cart_key' ) );

				return [
					'redirect' => 'order',
					'orderId'  => $id,
				];
			}
		} else {
			$message = 'error when processing received data: ' . wp_json_encode( $payment_info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

			return new \WP_Error(
				"app_builder_confirm_payment",
				$message,
				array(
					'status' => 403,
				)
			);
		}
	}
}
