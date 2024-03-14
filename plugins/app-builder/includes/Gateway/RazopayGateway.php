<?php


/**
 * class RazopayGateway
 *
 * @link       https://appcheap.io
 * @since      3.2.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Gateway;

defined( 'ABSPATH' ) || exit;

use AppBuilder\Data\CartData;

class RazopayGateway {

	public function __construct() {
		add_action( 'woocommerce_rest_checkout_process_payment_with_context', [ $this, 'create_order_id' ], 9999, 2 );
	}

	public function confirm_payment( $request ) {

		$order_id            = $request->get_param( 'order_id' );
		$cart_key            = $request->get_param( 'cart_key' );
		$order_key           = $request->get_param( 'order_key' );
		$razorpay_payment_id = $request->get_param( 'razorpay_payment_id' );
		$razorpay_order_id   = $request->get_param( 'razorpay_order_id' );
		$razorpay_signature  = $request->get_param( 'razorpay_signature' );

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

		$cart = new CartData();

		if ( $order->get_status() == 'processing' ) {

			$cart->remove_cart_by_cart_key( $cart_key );

			return [
				'redirect'           => 'order',
				'order_id'           => $order_id,
				'order_key'          => $order->get_order_key(),
				'order_received_url' => $order->get_checkout_order_received_url(),
			];
		}

		$data = $this->check_razorpay_response( $order_key, $razorpay_payment_id, $razorpay_order_id, $razorpay_signature, 1 );

		if ( $data['redirect'] == 'order' ) {
			$cart->remove_cart_by_cart_key( $cart_key );
		}

		return $data;
	}

	/**
	 * Handles any potential stripe intents on the order that need handled.
	 *
	 * This is configured to execute after legacy payment processing has
	 * happened on the woocommerce_rest_checkout_process_payment_with_context
	 * action hook.
	 *
	 * @param \PaymentContext $context Holds context for the payment.
	 * @param \PaymentResult $result Result object for the payment.
	 */
	public function create_order_id( $context, &$result ) {
		if ( 'razorpay' === $context->payment_method && class_exists( 'WC_Payment_Gateway' ) ) {
			$razorpay        = new \WC_Razorpay();
			$razorpayOrderId = $razorpay->createOrGetRazorpayOrderId( $context->order, $context->order->get_id(), 'none' );

			$payment_details                    = $result->payment_details;
			$payment_details['razorpayOrderId'] = $razorpayOrderId;

			$result->set_payment_details( $payment_details );
		}
	}

	/**
	 * @param $order_key
	 * @param $razor_payment_id
	 * @param $razor_order_id
	 * @param $razor_signature
	 * @param $form_submit
	 *
	 * @return array|string[]
	 */
	public function check_razorpay_response( $order_key, $razor_payment_id, $razor_order_id, $razor_signature, $form_submit ) {
		global $woocommerce;
		global $wpdb;

		$razorpay = new \WC_Razorpay();

		$order = false;

		$post_type = 'shop_order';

		$post_password = $order_key;

		$meta_key = '_order_key';

		$postMetaData = $wpdb->get_row( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta AS P WHERE meta_key = %s AND meta_value = %s", $meta_key, $post_password ) );

		$postData = $wpdb->get_row( $wpdb->prepare( "SELECT post_status FROM $wpdb->posts AS P WHERE post_type=%s and ID=%s", $post_type, $postMetaData->post_id ) );

		$arrayPost = json_decode( json_encode( $postMetaData ), true );

		if ( ! empty( $arrayPost ) and
		     $arrayPost != null ) {
			$orderId = $postMetaData->post_id;

			if ( $postData->post_status === 'draft' ) {
				updateOrderStatus( $orderId, 'wc-pending' );
			}

			$order = wc_get_order( $orderId );
		}

		// If the order has already been paid for
		// redirect user to success page
		if ( $order->needs_payment() === false ) {
			$this->redirectUser( $order );
		}

		$razorpayPaymentId = null;

		if ( $orderId and ! empty( $razor_payment_id ) ) {
			$error   = "";
			$success = false;

			try {
				$this->verifySignature( $razor_payment_id, $razor_order_id, $razor_signature );
				$success           = true;
				$razorpayPaymentId = sanitize_text_field( $razor_payment_id );

				// clean cart
			} catch ( \Errors\SignatureVerificationError $e ) {
				$error = 'WOOCOMMERCE_ERROR: Payment to Razorpay Failed. ' . $e->getMessage();
			}
		}

		$razorpay->updateOrder( $order, $success, $error, $razorpayPaymentId, null, true );

		return $this->redirectUser( $order );
	}

	protected function redirectUser( $order ): array {
		return [
			'redirect'           => 'order',
			'order_id'           => $order->get_id(),
			'order_key'          => $order->get_order_key(),
			'order_received_url' => $order->get_checkout_order_received_url(),
		];
	}

	/**
	 * @param $razor_payment_id
	 * @param $razor_order_id
	 * @param $razor_sig
	 */
	protected function verifySignature( $razor_payment_id, $razor_order_id, $razor_signature ) {
		$razorpay = new \WC_Razorpay();
		$api      = $razorpay->getRazorpayApiInstance();

		$attributes                                 = array(
			$razorpay::RAZORPAY_PAYMENT_ID => $razor_payment_id,
			$razorpay::RAZORPAY_SIGNATURE  => $razor_signature,
		);
		$attributes[ $razorpay::RAZORPAY_ORDER_ID ] = $razor_order_id;
		$api->utility->verifyPaymentSignature( $attributes );
	}
}
