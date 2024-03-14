<?php


/**
 * class PayTabsGateway
 *
 * @link       https://appcheap.io
 * @since      3.1.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Gateway;

defined( 'ABSPATH' ) || exit;

use AppBuilder\Data\CartData;

class PayTabsGateway {

	const PT_TRAN_TYPE = '_pt_transaction_type';

	public function confirm_payment( $request ) {

		$order_id         = $request->get_param( 'order_id' );
		$order            = wc_get_order( $order_id );
		$requestSignature = $request->get_param( 'signature' );
		$data             = $request->get_params();

		$validate = $this->verify( $requestSignature, $data );

		if ( is_wp_error( $validate ) ) {
			return $validate;
		}

		if ( ! class_exists( '\WC_Gateway_Paytabs_All' ) || ! $order ) {
			return new \WP_Error(
				"app_builder_confirm_payment",
				__( "The plugin Paytabs not install yet.", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		return $this->validate_payment( $request->get_params(), $order );
	}

	/**
	 * Verify request
	 *
	 * @param $requestSignature
	 * @param $data
	 *
	 * @return bool|\WP_Error
	 */
	private function verify( $requestSignature, $data ) {
		if ( ! defined( 'PAYTABS_SERVER_KEY_FOR_MOBILE' ) ) {
			return new \WP_Error(
				"app_builder_confirm_payment",
				__( "The constant PAYTABS_SERVER_KEY_FOR_MOBILE not define yet.", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		unset( $data['signature'] );
		unset( $data['cart_key'] );
		unset( $data['app-builder-decode'] );
		$query = http_build_query( $data );

		$signature = hash_hmac( 'sha256', $query, PAYTABS_SERVER_KEY_FOR_MOBILE );

		if ( ! hash_equals( $signature, $requestSignature ) === true ) {
			return new \WP_Error(
				"app_builder_confirm_payment",
				__( "The signature invalid.", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		return true;
	}

	private function validate_payment( $result, $order ) {

		$success          = (bool) $result['isSuccess'];
		$is_on_hold       = (bool) $result['isOnHold'];
		$is_pending       = (bool) $result['isPending'];
		$response_code    = $result['responseCode'];
		$message          = $result['responseMessage'];
		$transaction_ref  = $result['transactionReference'];
		$transaction_type = strtolower( $result['transactionType'] ?? '' );
		$token            = $result['token'] ?? '';
		$cart_key         = $result['cart_key'];

		if ( $success || $is_on_hold || $is_pending ) {
			return $this->order_success(
				$order,
				$transaction_ref,
				$transaction_type,
				$token,
				$message,
				false,
				false,
				$is_on_hold,
				$is_pending,
				$response_code,
				$cart_key
			);
		} else {
			$this->order_failed( $order, $message );
		}
	}

	/**
	 * @param $order
	 * @param $transaction_id
	 * @param $transaction_type
	 * @param $token_str
	 * @param $message
	 * @param $is_tokenise
	 * @param $is_ipn
	 * @param $is_on_hold
	 * @param $is_pending
	 * @param $response_code
	 * @param $cart_key
	 *
	 * @return array
	 */
	private function order_success(
		$order,
		$transaction_id,
		$transaction_type,
		$token_str,
		$message,
		$is_tokenise,
		$is_ipn,
		$is_on_hold,
		$is_pending,
		$response_code,
		$cart_key
	): array {
		global $woocommerce;
		$paytabs = new \WC_Gateway_Paytabs_All();

		if ( $is_on_hold || $is_pending ) {
			$order->set_transaction_id( $transaction_id );
		} else {
			$order->payment_complete( $transaction_id );
		}
		// $order->reduce_order_stock();

		$this->pt_set_tran_ref( $order, $transaction_type, $transaction_id );

		$cart = new CartData();
		$cart->remove_cart_by_cart_key( $cart_key );

		$order->add_order_note( $message, true );

		if ( $is_on_hold ) {
			$order->update_status( 'wc-on-hold', 'Payment for this order is On-Hold, you can Capture/Decline manualy from your dashboard on PayTabs portal', true );
		} elseif ( $is_pending ) {
			$_msg = 'Payment for this order is Pending';
			if ( $response_code ) {
				$_msg .= " (Reference number: {$response_code}) ";
			}
			if ( ! $paytabs->ipn_enable ) {
				$_msg .= ', You must enable the IPN to allow the Order update requests from PayTabs ';
			}
			$order->update_status( 'wc-on-hold', $_msg, true );
		} else {
			$paytabs->setNewStatus( $order, true, $transaction_type );
		}

		return array(
			'redirect'           => 'order',
			'order_id'           => $order->get_id(),
			'order_received_url' => $paytabs->get_return_url( $order )
		);

	}

	/**
	 * @param $order
	 * @param $message
	 *
	 * @return string[]
	 */
	private function order_failed( $order, $message ): array {
		$paytabs = new \WC_Gateway_Paytabs_All();

		$order->update_status( 'failed', $message );

		$paytabs->setNewStatus( $order, false );

		return array(
			'message'  => $message,
			'redirect' => 'checkout',
		);
	}

	private function pt_set_tran_type( $order, $transaction_type ) {
		update_post_meta( $order->get_id(), $this::PT_TRAN_TYPE, $transaction_type );
	}

	private function pt_set_tran_ref( $order, $transaction_type, $transaction_id ) {
		add_post_meta( $order->get_id(), '_pt_tran_ref_' . $transaction_type, $transaction_id );
		$this->pt_set_tran_type( $order, $transaction_type );
	}
}
