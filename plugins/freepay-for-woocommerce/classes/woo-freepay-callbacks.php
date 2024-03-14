<?php

/**
 * Class WC_FreePay_Callbacks
 */
class WC_FreePay_Callbacks {

	/**
	 * Regular payment logic for authorized transactions
	 *
	 * @param WC_Order $order
	 * @param stdClass $transaction
	 */
	public static function payment_authorized( $order, $transaction ) {
		$hasTransactionId = false;
		
		if(WC_FreePay_Order_Utils::get_transaction_id($order) == $transaction['authorizationIdentifier']) {
			$hasTransactionId = true;
		}

		if(!$hasTransactionId) {
			$order->read_meta_data(true);
			$step_idx = WC_FreePay_Order_Utils::get_authorization_step($order);

			if(!empty($step_idx)) {
				if($step_idx == 1) {
					$hasTransactionId = true;
				}
			}
		}

		// Register the payment on the order
		if(!$hasTransactionId) {
			$order->payment_complete( $transaction['authorizationIdentifier'] );
		}

		// Write a note to the order history
		$order->add_order_note( sprintf( __( 'Payment authorized (Callback). Transaction ID: %s', 'freepay-for-woocommerce' ), $transaction['authorizationIdentifier'] ) );

		// Fallback to save transaction IDs since this has seemed to sometimes fail when using WC_Order::payment_complete
		self::save_transaction_id_fallback( $order, $transaction );
	}

	/**
	 * @param int $subscription_order_id
	 * @param stdClass $transaction
	 * @param bool $single_subcsription_transaction
	 * @param bool $is_change_subscription
	 */
	public static function subscription_authorized( $subscription_order_id, $transaction, $single_subcsription_transaction, $is_change_subscription, $is_renew ) {
		$is_wps_sfw = false;

		$tmp_order = wc_get_order($subscription_order_id);
		$wps_sfw_subscription_id = $tmp_order->get_meta( 'wps_subscription_id', true );
		if(!empty($wps_sfw_subscription_id)) {
			$is_wps_sfw = true;
			$subscription_order = wc_get_order( $wps_sfw_subscription_id );
		}
		else {
			$subscription_order = wc_get_order( $subscription_order_id );
		}

		$subscription_order->add_order_note( sprintf( __( 'Subscription authorized. Transaction ID: %s', 'freepay-for-woocommerce' ), $transaction['savedCardIdentifier'] ) );
		$subscription_order->update_meta_data( '_freepay_transaction_id', $transaction['savedCardIdentifier'] );
		$subscription_order->save_meta_data();
		
		if(!$single_subcsription_transaction && !$is_change_subscription) {
			// Check if there is an initial payment on the subscription.
			// We are saving the total before completing the original payment.
			// This gives us the correct payment for the auto initial payment on subscriptions.

			if($is_wps_sfw) {
				$parent_order_id = $subscription_order_id;
			}
			else {
				$wcsOrder = wcs_get_subscription( $subscription_order_id );

				if($is_renew) {
					$parent_order_id = reset($wcsOrder->get_related_orders('ids'));
				}
				else {
					$parent_order_id = end($wcsOrder->get_related_orders('ids', 'parent'));
				}				
			}

			$parent_order = wc_get_order( $parent_order_id );
			$subscription_initial_payment = $parent_order->get_total();
			
			// Only make an instant payment if there is an initial payment
			if ( $subscription_initial_payment > 0 ) {
				WC_FP_MAIN()->process_recurring_payment( new WC_FreePay_API_Subscription(), $transaction['savedCardIdentifier'], $subscription_initial_payment, $parent_order, false );
			}
			// If there is no initial payment, we will mark the order as complete.
			// This is usually happening if a subscription has a free trial.
			else {
				$parent_order->payment_complete();
			}
		}
	}

	/**
	 * Common logic for authorized payments/subscriptions
	 *
	 * @param WC_Order $order
	 */
	public static function authorized( $order ) {
		// Remove payment link
		WC_FreePay_Payment_Utils::delete_payment_link($order);
	}

	/**
	 * @param WC_Order $order
	 * @param stdClass $transaction
	 */
	public static function save_transaction_id_fallback( $order, $transaction ) {
		try {
			if ( ! empty( $transaction['authorizationIdentifier'] ) ) {
				$order->set_transaction_id( $transaction['authorizationIdentifier'] );
				$order->update_meta_data( '_freepay_transaction_id', $transaction['authorizationIdentifier'] );
				$order->save_meta_data();
				$order->save();
			}
		} catch ( WC_Data_Exception $e ) {
			wc_get_logger()->error( $e->getMessage() );
		}
	}
}