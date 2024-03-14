<?php
/**
 * Hyperpay Gateway
 *
 * @package AppBuilder
 */

namespace AppBuilder\Gateway;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

defined( 'ABSPATH' ) || exit;

/**
 * Class HyperpayGateway
 *
 * @link       https://appcheap.io
 * @since      3.1.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */
class HyperpayGateway {

	/**
	 * Confirm payment
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|array
	 */
	public function confirm_payment( $request ) {

		global $woocommerce;

		$checkout_id = $request->get_param( 'checkout_id' );
		$order_id    = $request->get_param( 'order_id' );
		$cart_key    = $request->get_param( 'cart_key' );

		if ( empty( $checkout_id ) ) {
			return new WP_Error(
				'app_builder_confirm_payment',
				__( 'Checkout ID not provider.', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		if ( empty( $order_id ) || empty( $cart_key ) ) {
			return new WP_Error(
				'app_builder_confirm_payment',
				__( 'Order ID or Cart Key not provider.', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return new WP_Error(
				'app_builder_confirm_payment',
				__( 'Order not found.', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		if ( ! class_exists( '\WC_Hyperpay_Gateway' ) ) {
			return new WP_Error(
				'app_builder_confirm_payment',
				__( 'Hyperpay not found.', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		$gateway = null;

		switch ( $request->get_param( 'gateway' ) ) {
			case 'hyperpay':
				$gateway = new \WC_Hyperpay_Gateway();
				break;
			case 'hyperpay_mada':
				$gateway = new \WC_Hyperpay_Mada_Gateway();
				break;
			case 'hyperpay_stcpay':
				$gateway = new \WC_Hyperpay_STCPay_Gateway();
				break;
			case 'hyperpay_applepay':
				$gateway = new \WC_Hyperpay_ApplePay_Gateway();
				break;
			case 'hyperpay_tabby':
				$gateway = new \WC_Hyperpay_Tabby_Gateway();
				break;
			case 'hyperpay_zoodpay':
				$gateway = new \WC_Hyperpay_Zoodpay_Gateway();
				break;
		}

		if ( $gateway === null ) {
			return new WP_Error(
				'app_builder_confirm_payment',
				__( 'Gateway not found.', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		if ( $gateway->testmode ) {
			$transaction_status_url = 'https://test.oppwa.com/v1/checkouts/{checkout_id}/payment';
		} else {
			$transaction_status_url = 'https://oppwa.com/v1/checkouts/{checkout_id}/payment';
		}

		$url = str_replace( '{checkout_id}', $checkout_id, $transaction_status_url );

		$auth = array(
			'headers' => array( 'Authorization' => 'Bearer ' . $gateway->accesstoken ),
		);

		$response    = wp_remote_get( $url . '?entityId=' . $gateway->entityid, $auth );
		$result_json = wp_remote_retrieve_body( $response );
		$result      = json_decode( $result_json, true );

		if ( isset( $result['result']['code'] ) ) {
			$status = $gateway->check_status( $result );

			if ( 'success' === $status ) {
				$merchant_transaction_id = $result['merchantTransactionId'];
				/** Format {order_id}I{string} Get Order ID */
				$payment_order_id = explode( 'I', $merchant_transaction_id )[0];
				/** Check order */
				if ( "$payment_order_id" !== "$order_id" ) {
					return new WP_Error(
						'app_builder_confirm_payment',
						__( 'Order not validate.', 'app-builder' ),
						array(
							'status' => 403,
						)
					);
				}
			}

			/** Check order price */
			$amount       = $result['amount'];
			$order_amount = $order->get_total();
			if ( "$amount" !== "$order_amount" ) {
				return new WP_Error(
					'app_builder_confirm_payment',
					__( 'Order price not validate.', 'app-builder' ),
					array(
						'status' => 403,
					)
				);
			}

			$unique_id          = $result['id'];
			$order_final_status = 'PA' === $result['payload']['paymentType'] ? 'on-hold' : $gateway->get_option( 'order_status' );

			/** Update order notes */
			$order->add_order_note( 'Updated by webhook ' . __( 'Transaction ID: ', 'hyperpay-payments' ) . esc_html( $unique_id ) );
			if ( 'CP' === $result['payload']['paymentType'] ) {
				$order->add_order_note( 'Captured by webhook ' );
			}
			$order->update_status( $order_final_status );
			$order->save();

			if ( ! empty( $woocommerce->cart ) ) {
				$woocommerce->cart->empty_cart();
			}

			return array(
				'redirect' => 'order',
				'orderId'  => $order_id,
			);
		} else {
			return new WP_Error(
				$result['result']['code'],
				$result['result']['description'],
				array(
					'status' => 403,
				)
			);
		}
	}
}
