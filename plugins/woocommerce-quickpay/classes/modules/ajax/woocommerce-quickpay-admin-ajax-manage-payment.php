<?php

class WC_QuickPay_Admin_Ajax_Manage_Payment extends WC_QuickPay_Admin_Ajax_Action {

	public function action(): string {
		return 'manage-payment';
	}

	public function execute(): void {
		if ( isset( $_REQUEST['quickpay_action'], $_REQUEST['post'] ) ) {
			$param_action = $_REQUEST['quickpay_action'];
			$param_post   = $_REQUEST['post'];

			if ( ! woocommerce_quickpay_can_user_manage_payments( $param_action ) ) {
				wp_send_json_error( 'Your user is not capable of %s payments.', $param_action );
			}

			if ( ! $order = woocommerce_quickpay_get_order( (int) $param_post ) ) {
				wp_send_json_error( 'We could not find your order with ID %d', (int) $param_post );
			}

			try {
				if ( ! $transaction_id = WC_QuickPay_Order_Utils::get_transaction_id( $order ) ) {
					wp_send_json_error( 'We could not find a transaction ID.' );
				}

				$payment = WC_QuickPay_Subscription::is_subscription( $order ) ? new WC_QuickPay_API_Subscription() : new WC_QuickPay_API_Payment();
				$payment->get( $transaction_id );

				// Based on the current transaction state, we check if
				// the requested action is allowed
				if ( $payment->is_action_allowed( $param_action ) ) {
					// Check if the action method is available in the payment class
					if ( method_exists( $payment, $param_action ) ) {
						// Fetch amount if sent.
						$amount = isset( $_REQUEST['quickpay_amount'] ) ? WC_QuickPay_Helper::price_custom_to_multiplied( $_REQUEST['quickpay_amount'], $payment->get_currency() ) : $payment->get_remaining_balance();

						// Call the action method and parse the transaction id and order object
						$payment->$param_action( $transaction_id, $order, WC_QuickPay_Helper::price_multiplied_to_float( $amount, $payment->get_currency() ) );
					} else {
						throw new QuickPay_API_Exception( sprintf( "Unsupported action: %s.", $param_action ) );
					}
				} // The action was not allowed. Throw an exception
				else {
					throw new QuickPay_API_Exception( sprintf( "Action: \"%s\", is not allowed for order #%d, with type state \"%s\"", $param_action, WC_QuickPay_Order_Utils::get_clean_order_number( $order ), $payment->get_current_type() ) );
				}
			} catch ( QuickPay_Exception $e ) {
				$e->write_to_logs();
				wp_send_json_error( $e->getMessage() );
			}
		}
	}
}
