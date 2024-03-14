<?php
/**
 * Class IyzicoGateway
 *
 * @link       https://appcheap.io
 * @since      3.2.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 * @package    AppBuilder\Gateway
 * @subpackage AppBuilder\Gateway\IyzicoGateway
 * @license    https://appcheap.io
 */

namespace AppBuilder\Gateway;

defined( 'ABSPATH' ) || exit;

use AppBuilder\Data\CartData;

/**
 * Class IyzicoGateway
 */
class IyzicoGateway {

	/**
	 * IyzicoGateway constructor.
	 */
	public function __construct() {
		add_action( 'woocommerce_rest_checkout_process_payment_with_context', array( $this, 'process_payment' ), 11, 2 );
	}

	/**
	 * Confirm payment
	 *
	 * @param $request
	 *
	 * @return array|\WP_Error
	 */
	public function confirm_payment( $request ) {

		$order_id            = $request->get_param( 'order_id' );
		$cart_key            = $request->get_param( 'cart_key' );
		$order_key           = $request->get_param( 'order_key' );
		$razorpay_payment_id = $request->get_param( 'razorpay_payment_id' );
		$razorpay_order_id   = $request->get_param( 'razorpay_order_id' );
		$razorpay_signature  = $request->get_param( 'razorpay_signature' );

		if ( empty( $order_id ) || empty( $cart_key ) ) {
			return new \WP_Error(
				'app_builder_confirm_payment',
				__( 'Order ID or Cart Key not provider.', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return new \WP_Error(
				'app_builder_confirm_payment',
				__( 'Order not found.', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		$cart = new CartData();

		if ( $order->get_status() == 'processing' ) {

			$cart->remove_cart_by_cart_key( $cart_key );

			return array(
				'redirect'           => 'order',
				'order_id'           => $order_id,
				'order_key'          => $order->get_order_key(),
				'order_received_url' => $order->get_checkout_order_received_url(),
			);
		}

		$data = $this->check_razorpay_response( $order_key, $razorpay_payment_id, $razorpay_order_id, $razorpay_signature, 1 );

		if ( $data['redirect'] == 'order' ) {
			$cart->remove_cart_by_cart_key( $cart_key );
		}

		return $data;
	}

	/**
	 * Process payment
	 *
	 * @param  \PaymentContext $context The payment context.
	 * @param \PaymentResult  $result  The payment result.
	 *
	 * @return void
	 */
	public function process_payment( $context, &$result ) {
		if ( 'iyzico' === $context->payment_method ) {
			$gateway = $context->get_payment_method_instance();
			ob_start();
			$gateway->iyzico_payment_form( $context->order->get_id() );
			$payment_form = ob_get_contents();
			ob_end_clean();

			$result->set_payment_details(
				array(
					'payment_form' => stripslashes( $payment_form ),
				)
			);

			$result->set_status( 'pending' );
		}
	}
}
