<?php
/**
 * Ajax Account class
 *
 * @package ZiinaPayment\Ajax
 */

namespace ZiinaPayment\Ajax;

use Exception;

defined( 'ABSPATH' ) || exit();

/**
 * Class Ajax Account
 *
 * @package ZiinaPayment\Ajax
 * @since   1.0.0
 */
class Payment extends Base {
	/**
	 * Prefix for actions
	 *
	 * @var string
	 */
	const PREFIX = 'ziina_payment';

	/**
	 * Actions for wc api (registration with prefix)
	 *
	 * @var array
	 */
	const ACTIONS = array(
		'success_url',
		'cancel_url',
	);

	/**
	 * Action success_url
	 */
	public function success_url() {
		$order_id = isset( $_GET['order_id'] ) ? wc_clean( $_GET['order_id'] ) : '';
		$order    = wc_get_order( $order_id );

		if ( empty( $order ) ) {
			wp_die( __( 'Wrong order id', 'ziina' ) );
		}

		try {
			$payment_intent = ziina_payment()->api()->get_payment_intent( $order_id );
		} catch ( Exception $e ) {
			wp_die( __( 'Api request error. Try again or contact us', 'ziina' ) );
		}

		if ( empty( $payment_intent ) ) {
			wp_die( __( 'Wrong payment id', 'ziina' ) );
		}

		if ( 'completed' === $payment_intent['status'] ) {
			if ( $order->payment_complete() ) {
				wp_redirect( $order->get_checkout_order_received_url() );
				die();
			} else {
				wp_die( __( 'Payment not completed. Reload page or contact us', 'ziina' ) );
			}
		}

		wc_add_notice(
			__( 'Payment error. Try again or contact us', 'ziina' ),
			'error'
		);

		wp_redirect( wc_get_checkout_url() );
		die();
	}

	/**
	 * Action failure_url
	 */
	public function cancel_url() {
		wc_add_notice(
			__( 'Payment error. Try again or contact us', 'ziina' ),
			'error'
		);

		wp_redirect( wc_get_checkout_url() );
		die();
	}
}
