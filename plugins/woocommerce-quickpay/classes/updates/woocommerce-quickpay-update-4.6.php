<?php
/**
 * Update WC_QuickPay to 4.6
 *
 * @author    PerfectSolution
 * @version  1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Ignore user aborts and allow the script to run forever if supported
ignore_user_abort( true );
set_time_limit( 0 );


global $wpdb;

if (WC_QuickPay_Helper::is_HPOS_enabled()) {
	$subscriptions = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wc_orders p WHERE p.type = 'shop_subscription' AND p.status NOT IN ('draft', 'trash') AND NOT EXISTS(SELECT 1 FROM {$wpdb->prefix}wc_orders_meta pm WHERE p.id=pm.order_id AND pm.meta_key IN ('_transaction_id', 'TRANSACTION_ID'))", OBJECT );
} else {
	$subscriptions = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} p WHERE p.post_type = 'shop_subscription' AND p.post_status NOT IN ('draft', 'trash') AND NOT EXISTS(SELECT 1 FROM {$wpdb->postmeta} pm WHERE p.ID=pm.post_id AND pm.meta_key IN ('_transaction_id', 'TRANSACTION_ID'))", OBJECT );
}
if ( ! empty( $subscriptions ) ) {
	foreach ( $subscriptions as $subscription_post ) {
		$subscription = woocommerce_quickpay_get_subscription( $subscription_post );
		// Create order object
		$order          = woocommerce_quickpay_get_order( $subscription_post->post_parent );
		$transaction_id = WC_QuickPay_Order_Utils::get_transaction_id( $order );

		$order_id        = $order->get_id();
		$subscription_id = $subscription->get_id();

		if ( ! empty( $transaction_id ) && WC_QuickPay_Order_Payments_Utils::is_order_using_quickpay( $order ) ) {
			$logger      = new WC_QuickPay_Log();
			$transaction = new WC_QuickPay_API_Subscription();

			try {
				// Check if the transaction ID is actually a transaction of type subscription. If not, an exception will be thrown.
				$response = $transaction->get( $transaction_id );

				// Set the transaction ID on the parent order
				$subscription->set_transaction_id( $transaction_id );


				if ( $order = wc_get_order( $order_id ) ) {
					$order->delete_meta_data( '_transaction_id' );
					$order->delete_meta_data( 'TRANSACTION_ID' );
					$order->save_meta_data();
				}

				$logger->add( sprintf( 'Migrated transaction (%d) from parent order ID: %s to subscription order ID: %s', $transaction_id, $subscription_id, $order_id ) );
			} catch ( WC_Data_Exception|QuickPay_API_Exception $e ) {
				$logger->add( sprintf( 'Failed migration of transaction (%d) from parent order ID: %s to subscription order ID: %s. Error: %s', $transaction_id, $subscription_id, $order_id, $e->getMessage() ) );
			}
		}
	}
}

