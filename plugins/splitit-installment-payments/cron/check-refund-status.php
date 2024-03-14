<?php
/**
 * @package     Splitit_WooCommerce_Plugin
 *
 * File - create-log-table.php
 * Function for create checkout data table
 */

require_once dirname( __DIR__ ) . '/classes/class-splitit-flexfields-payment-plugin-log.php';

// daily cron job
if ( ! wp_next_scheduled( 'check_splitit_refund_status_daily' ) ) {
	wp_schedule_event( time(), 'daily', 'check_splitit_refund_status_daily' );
}
add_action( 'check_splitit_refund_status_daily', 'check_splitit_refund_status_by_ipn_daily' );

function check_splitit_refund_status_by_ipn_daily() {
	SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'daily cron job check_splitit_refund_status process' );

	$log_data = array(
		'user_id' => null,
		'method'  => __( 'splitit_refund_cron_job() Splitit', 'splitit_ff_payment' ),
	);

	try {

		$orders_waiting_refund = SplitIt_FlexFields_Payment_Plugin_Log::select_from_refund_log_orders_without_refund_result();

		global $settings_for_check_refund;

		foreach ( $orders_waiting_refund as $item ) {

			// info from DB.
			$entity_id             = $item->id;
			$ipn                   = $item->ipn;
			$order_id              = $item->order_id;
			$splitit_refund_id     = $item->refund_id;
			$refund_amount         = $item->refund_amount;
			$requested_action_type = $item->action_type;

			// info from Splitit.
			$api      = new SplitIt_FlexFields_Payment_Plugin_API( $settings_for_check_refund );
			$ipn_info = $api->get_ipn_info( $ipn );

			$ipn_info_status           = $ipn_info->getStatus();
			$ipn_info_refunds          = $ipn_info->getRefunds();
			$ipn_info_ref_order_number = $ipn_info->getRefOrderNumber();

			$order = wc_get_order( $order_id );

			if ( ! $order ) {
				throw new Exception( __( 'Refund cron job error. There is no order with ID = ' . $order_id . ' in platform. IPN = ' . $ipn . '', 'splitit_ff_payment' ) );
			}

			$current_timestamp    = time();
			$created_at_timestamp = strtotime( $item->updated_at );

			$time_difference = $current_timestamp - $created_at_timestamp;

			$fourteen_days_in_seconds = 14 * 24 * 60 * 60;

			// checking whether it has been more than 14 days since the recording.
			if ( $time_difference > $fourteen_days_in_seconds ) {
				SplitIt_FlexFields_Payment_Plugin_Log::update_refund_log( $entity_id, array( 'action_type' => 'done' ) );
			}

			if ( $ipn_info_ref_order_number == $order_id ) {

				if ( ! empty( $ipn_info_refunds ) ) {

					foreach ( $ipn_info_refunds as $splitit_refund ) {
						$refund_id = $splitit_refund->getRefundId();

						if ( $refund_id == $splitit_refund_id ) {
							$refund_status = $splitit_refund->getStatus();

							$splitit_refund_amount = $splitit_refund->getTotalAmount();

							if ( 'Succeeded' == $refund_status ) {
								// this is for the case if the status is Succeeded, but for some reason the webhook did not arrive and
								// the order status on the platform has not yet been changed to Refunded or Cancelled.

								if ( $splitit_refund_amount == $refund_amount ) {

									if ( 'refund' == $requested_action_type ) {
										$refunds = $order->get_refunds();

										if ( ( empty( $refunds ) || $order->get_remaining_refund_amount() > 0 ) && in_array( $order->get_status(), array( 'processing', 'completed' ) ) ) {

											$reason = 'splitit_programmatically';

											if ( $order->get_remaining_refund_amount() >= $refund_amount ) {
												$refund = wc_create_refund(
													array(
														'amount'   => $refund_amount,
														'reason'   => $reason,
														'order_id' => $order_id,
														'refund_payment' => true,
													)
												);

												SplitIt_FlexFields_Payment_Plugin_Log::update_refund_log( $entity_id, array( 'action_type' => 'done' ) );

												if ( is_wp_error( $refund ) ) {
													if ( $refund->get_error_message() == 'Invalid refund amount.' ) {
														$order->add_order_note( 'Refund failed by Splitit: Refund requested amount = ' . $refund_amount . ' exceeds remaining order balance of ' . $order->get_remaining_refund_amount() );
														SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Refund requested amount = ' . $refund_amount . ' exceeds remaining order balance of ' . $order->get_remaining_refund_amount() . '; Order ID: ' . $order_id . ', ipn = ' . $ipn );
													} else {
														$order->add_order_note( 'Refund failed by Splitit. Amount: ' . $refund_amount . ';Error: ' . $refund->get_error_message() );
														SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Refund error: ' . $refund->get_error_message() . '; Amount: ' . $refund_amount . '; Order ID: ' . $order_id . ', ipn = ' . $ipn );
													}
												} else {
													SplitIt_FlexFields_Payment_Plugin_Log::update_refund_log( $entity_id, array( 'action_type' => 'done' ) );
													$order->add_order_note( 'A refund for the amount = ' . $refund_amount . ' has succeeded on the Splitit side.' );
													SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Refund success. Amount: ' . $refund_amount . ', Order ID: ' . $order_id . ' Refund ID: ' . $splitit_refund_id . ', ipn = ' . $ipn );
												}
											} else {
												SplitIt_FlexFields_Payment_Plugin_Log::update_refund_log( $entity_id, array( 'action_type' => 'done' ) );
												$order->add_order_note( 'Splitit made a refund for a different amount = ' . $splitit_refund_amount . '; Check this order in the Merchant Portal or contact Splitit support.' );
												SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Refund requested amount = ' . $splitit_refund_amount . ' exceeds remaining order balance of ' . $order->get_remaining_refund_amount() . 'Order ID: ' . $order_id . ', ipn = ' . $ipn );
											}
										} else {
											SplitIt_FlexFields_Payment_Plugin_Log::update_refund_log( $entity_id, array( 'action_type' => 'done' ) );
											SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Refund has already been completed for this order. Order ID: ' . $order_id . ', ipn = ' . $ipn );
										}
									} elseif ( 'cancel' == $requested_action_type ) {

										SplitIt_FlexFields_Payment_Plugin_Log::update_refund_log( $entity_id, array( 'action_type' => 'done' ) );
										$order->add_order_note( 'Cancel for the amount = ' . $splitit_refund_amount . ' is succeeded on the Splitit side' );
										SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Cancel success. Amount = ' . $splitit_refund_amount . ', Order ID: ' . $order_id . ' Refund ID: ' . $splitit_refund_id . ', ipn = ' . $ipn );

										$order->update_status( 'cancelled' );
									}
								} else {

									SplitIt_FlexFields_Payment_Plugin_Log::update_refund_log( $entity_id, array( 'action_type' => 'done' ) );
									$order->add_order_note( 'Refund order programmatically is failed, incorrect refund amount' );
									throw new Exception( __( 'Refund cron job error. Splitit made a refund for a different amount. Platform amount = ' . $refund_amount . ', and Splitit amount = ' . $splitit_refund_amount . '; Check this order in the Merchant Portal. IPN = ' . $ipn . '', 'splitit_ff_payment' ) );

								}
							} elseif ( 'Failed' == $refund_status ) {

								SplitIt_FlexFields_Payment_Plugin_Log::update_refund_log( $entity_id, array( 'action_type' => 'done' ) );
								$order->add_order_note( 'Refund failed by Splitit. Refund status is Failed. For more details please contact the Splitit Support Team' );
								SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Refund failed by Splitit. Refund status from Splitit is Failed. Order ID = ' . $order_id . ', ipn = ' . $ipn );

							} elseif ( 'Pending' == $refund_status ) {

								// checking whether it has been more than 14 days since the recording
								if ( $time_difference > $fourteen_days_in_seconds ) {
									SplitIt_FlexFields_Payment_Plugin_Log::update_refund_log( $entity_id, array( 'action_type' => 'done' ) );
									$order->add_order_note( 'Refund failed by Splitit. Refund status is Pending. For more details please contact the Splitit Support Team' );
									SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Refund failed by Splitit. The status has not been changed from Pending for more than 14 days. Order ID = ' . $order_id . ', ipn = ' . $ipn );
								}
							}
						}
					}
				}
			}
		}

		SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Number of checked refunds: ' . count( $orders_waiting_refund ) );

	} catch ( Exception $e ) {
		SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $log_data, $e->getMessage(), 'error' );
		if ( 'my-wordpress-blog.local' != DOMAIN && 'localhost' != DOMAIN && '127.0.0.1' != DOMAIN ) {
			send_slack_refund_notification( 'Check refunds cron job error: \n ' . $e->getMessage() . ' \n Domain: <' . URL . '|' . DOMAIN . '> \n Platform: Woocommerce' );
		}
	}

}
