<?php


/**
 * class PayStackGateway
 *
 * @link       https://appcheap.io
 * @since      3.1.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Gateway;

defined( 'ABSPATH' ) || exit;

use AppBuilder\Data\CartData;

class PayStackGateway {

	public function confirm_payment( $rq ) {

		if ( ! class_exists( '\WC_Gateway_Paystack' ) ) {
			return new \WP_Error(
				"app_builder_confirm_payment",
				__( "The plugin PayStack plugin not install yet.", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		$paystack = new \WC_Gateway_Paystack();

		if ( $rq->get_param( 'paystack_txnref' ) ) {
			$paystack_txn_ref = sanitize_text_field( $rq->get_param( 'paystack_txnref' ) );
		} elseif ( $rq->get_param( 'reference' ) ) {
			$paystack_txn_ref = sanitize_text_field( $rq->get_param( 'reference' ) );
		} else {
			$paystack_txn_ref = false;
		}

		@ob_clean();

		if ( $paystack_txn_ref ) {

			$paystack_url = 'https://api.paystack.co/transaction/verify/' . $paystack_txn_ref;

			$headers = array(
				'Authorization' => 'Bearer ' . $paystack->secret_key,
			);

			$args = array(
				'headers' => $headers,
				'timeout' => 60,
			);

			$request = wp_remote_get( $paystack_url, $args );

			$error = array();

			if ( ! is_wp_error( $request ) && 200 === wp_remote_retrieve_response_code( $request ) ) {

				$paystack_response = json_decode( wp_remote_retrieve_body( $request ) );

				if ( 'success' == $paystack_response->data->status ) {

					$order_details = explode( '_', $paystack_response->data->reference );
					$order_id      = (int) $order_details[0];
					$order         = wc_get_order( $order_id );

					if ( in_array( $order->get_status(), array( 'processing', 'completed', 'on-hold' ) ) ) {
						return [
							'order_received_url' => $paystack->get_return_url( $order ),
							'redirect'           => 'order'
						];

					}

					$order_total      = $order->get_total();
					$order_currency   = method_exists( $order, 'get_currency' ) ? $order->get_currency() : $order->get_order_currency();
					$currency_symbol  = get_woocommerce_currency_symbol( $order_currency );
					$amount_paid      = $paystack_response->data->amount / 100;
					$paystack_ref     = $paystack_response->data->reference;
					$payment_currency = strtoupper( $paystack_response->data->currency );
					$gateway_symbol   = get_woocommerce_currency_symbol( $payment_currency );

					// check if the amount paid is equal to the order amount.
					if ( $amount_paid < $order_total ) {

						$order->update_status( 'on-hold', '' );

						add_post_meta( $order_id, '_transaction_id', $paystack_ref, true );

						$notice      = sprintf( __( 'Thank you for shopping with us.%1$sYour payment transaction was successful, but the amount paid is not the same as the total order amount.%2$sYour order is currently on hold.%3$sKindly contact us for more information regarding your order and payment status.', 'woo-paystack' ), '<br />', '<br />', '<br />' );
						$notice_type = 'notice';

						// Add Customer Order Note
						$order->add_order_note( $notice, 1 );

						// Add Admin Order Note
						$admin_order_note = sprintf( __( '<strong>Look into this order</strong>%1$sThis order is currently on hold.%2$sReason: Amount paid is less than the total order amount.%3$sAmount Paid was <strong>%4$s (%5$s)</strong> while the total order amount is <strong>%6$s (%7$s)</strong>%8$s<strong>Paystack Transaction Reference:</strong> %9$s', 'woo-paystack' ), '<br />', '<br />', '<br />', $currency_symbol, $amount_paid, $currency_symbol, $order_total, '<br />', $paystack_ref );
						$order->add_order_note( $admin_order_note );

						function_exists( 'wc_reduce_stock_levels' ) ? wc_reduce_stock_levels( $order_id ) : $order->reduce_order_stock();

						$error['type']    = $notice_type;
						$error['message'] = $notice;

					} else {

						if ( $payment_currency !== $order_currency ) {

							$order->update_status( 'on-hold', '' );

							update_post_meta( $order_id, '_transaction_id', $paystack_ref );

							$notice      = sprintf( __( 'Thank you for shopping with us.%1$sYour payment was successful, but the payment currency is different from the order currency.%2$sYour order is currently on-hold.%3$sKindly contact us for more information regarding your order and payment status.', 'woo-paystack' ), '<br />', '<br />', '<br />' );
							$notice_type = 'notice';

							// Add Customer Order Note
							$order->add_order_note( $notice, 1 );

							// Add Admin Order Note
							$admin_order_note = sprintf( __( '<strong>Look into this order</strong>%1$sThis order is currently on hold.%2$sReason: Order currency is different from the payment currency.%3$sOrder Currency is <strong>%4$s (%5$s)</strong> while the payment currency is <strong>%6$s (%7$s)</strong>%8$s<strong>Paystack Transaction Reference:</strong> %9$s', 'woo-paystack' ), '<br />', '<br />', '<br />', $order_currency, $currency_symbol, $payment_currency, $gateway_symbol, '<br />', $paystack_ref );
							$order->add_order_note( $admin_order_note );

							function_exists( 'wc_reduce_stock_levels' ) ? wc_reduce_stock_levels( $order_id ) : $order->reduce_order_stock();

							$error['type']    = $notice_type;
							$error['message'] = $notice;

						} else {

							$order->payment_complete( $paystack_ref );
							$order->add_order_note( sprintf( __( 'Payment via Paystack successful (Transaction Reference: %s)', 'woo-paystack' ), $paystack_ref ) );

							if ( $this->is_autocomplete_order_enabled( $order ) ) {
								$order->update_status( 'completed' );
							}
						}
					}

					$paystack->save_card_details( $paystack_response, $order->get_user_id(), $order_id );

					$cart = new CartData();
					$cart->remove_cart_by_cart_key( $rq->get_param( 'cart_key' ) );

					return [
						'redirect'           => 'order',
						'order_received_url' => $paystack->get_return_url( $order ),
						'type'               => $error['type'],
						'message'            => $error['message']
					];

				} else {

					$order_details = explode( '_', $rq->get_param( 'paystack_txnref' ) );

					$order_id = (int) $order_details[0];

					$order = wc_get_order( $order_id );

					$order->update_status( 'failed', __( 'Payment was declined by Paystack.', 'woo-paystack' ) );

				}
			}
		}

		return [
			'redirect' => 'cart'
		];
	}

	protected function is_autocomplete_order_enabled( $order ) {
		$autocomplete_order = false;

		$payment_method = $order->get_payment_method();

		$paystack_settings = get_option( 'woocommerce_' . $payment_method . '_settings' );

		if ( isset( $paystack_settings['autocomplete_order'] ) && 'yes' === $paystack_settings['autocomplete_order'] ) {
			$autocomplete_order = true;
		}

		return $autocomplete_order;
	}
}
