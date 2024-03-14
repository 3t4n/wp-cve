<?php
/**
 * Church Tithe WP
 *
 * @package    Church Tithe WP
 * @subpackage Classes/Church Tithe WP
 * @copyright  Copyright (c) 2018, Church Tithe WP
 * @license    https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since      1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Get Arrangement History, with queryable variables to modify results.
 *
 * @since 1.0.0
 * @param string|array $query_args {
 *                                 Optional. Array or query string of item query parameters. Default empty.
 *
 * @type   int          $number         Maximum number of items to retrieve. Default 20.
 * @type   int          $offset         Number of items to offset the query. Default 0.
 * @type   string|array $orderby        Transactions status or array of statuses. To use 'meta_value'
 *                                        or 'meta_value_num', `$meta_key` must also be provided.
 *                                        To sort by a specific `$meta_query` clause, use that
 *                                        clause's array key. Accepts 'id', 'user_id', 'name',
 *                                        'email', 'payment_ids', 'purchase_value', 'purchase_count',
 *                                        'notes', 'date_created', 'meta_value', 'meta_value_num',
 *                                        the value of `$meta_key`, and the array keys of `$meta_query`.
 *                                        Also accepts false, an empty array, or 'none' to disable the
 *                                        `ORDER BY` clause. Default 'id'.
 * @type   string       $order          How to order retrieved items. Accepts 'ASC', 'DESC'.
 *                                        Default 'DESC'.
 * @type   string|array $columns_values_included        String or array of item IDs to include. Default empty.
 * @type   string|array $columns_values_excluded        String or array of item IDs to exclude. Default empty.
 *                                        empty.
 * @type   string       $search         Search term(s) to retrieve matching items for. Searches
 *                                        through item names. Default empty.
 * @type   string|array $search_columns Columns to search using the value of `$search`. Default 'name'.
 * @type   array        $date_query     Date query clauses to limit retrieved items by.
 *                                        See `WP_Date_Query`. Default empty.
 * @type   bool         $count          Whether to return a count (true) instead of an array of
 *                                        item objects. Default false.
 * @type   bool         $no_found_rows  Whether to disable the `SQL_CALC_FOUND_ROWS` query.
 *                                        Default true.
 * @return array
 */
function church_tithe_wp_get_arrangement_history_admin( $query_args = array() ) {
	$arrangements_db = new Church_Tithe_WP_Arrangements_DB();
	$arrangements    = $arrangements_db->get_arrangements( $query_args );

	// Create an array of rows that we'll use to output the rows in React.
	$rows = array();

	// Loop through each arrangement.
	foreach ( $arrangements as $arrangement ) {

		// If this arrangement is a one-time, skip it here.
		if ( ! $arrangement->interval_count ) {
			continue;
		}

		// Get the User's Info.
		$user = get_userdata( $arrangement->user_id );

		if ( empty( $user ) ) {
			continue;
		}

		// Format the row data.
		$row = array();

		// If the webhook has failed for this, output a notice at the top.
		if ( empty( $arrangement->gateway_subscription_id ) ) {

			$notice_message = __( 'The Stripe webhook failed to reach your website for this plan!', 'church-tithe-wp' );

			$row['webhook_notice'] = array(
				'show_in_list_view'                => false,
				'show_in_single_data_view'         => true,
				'title'                            => __( 'Notice', 'church-tithe-wp' ),
				'description'                      => __( 'The Stripe webhook failed to reach your website.', 'church-tithe-wp' ),
				'mpwpadmin_visual_state_onclick'   => array(
					'welcome' => array(),
				),
				'mpwpadmin_lightbox_state_onclick' => array(
					'stripe_live_webhook_signature_health_check' => array(),
				),
				'value'                            => $notice_message,
			);

		}

		$row['user_list_view'] = array(
			'show_in_list_view'        => true,
			'show_in_single_data_view' => false,
			'title'                    => __( 'User', 'church-tithe-wp' ),
			'description'              => __( 'This is the email of the user who tithed you.', 'church-tithe-wp' ),
			'value'                    => $user->user_email,
		);

		$row['user_single_view'] = array(
			'show_in_list_view'        => false,
			'show_in_single_data_view' => true,
			'title'                    => __( 'User', 'church-tithe-wp' ),
			'description'              => __( 'This is the email of the user who tithed you.', 'church-tithe-wp' ),
			'link_url'                 => admin_url( 'user-edit.php?user_id=' . $user->ID ),
			'value'                    => $user->user_email,
		);

		$row['id'] = array(
			'show_in_list_view'        => true,
			'show_in_single_data_view' => true,
			'title'                    => __( 'Plan ID', 'church-tithe-wp' ),
			'description'              => __( 'This is the ID of the plan in your WordPress database.', 'church-tithe-wp' ),
			'value'                    => $arrangement->id,
		);

		$row['status'] = array(
			'show_in_list_view'        => true,
			'show_in_single_data_view' => true,
			'title'                    => __( 'Status', 'church-tithe-wp' ),
			'description'              => __( 'This indicates the status of the plan.', 'church-tithe-wp' ),
			'value'                    => ucfirst( $arrangement->recurring_status ),
		);

		if ( ! empty( $arrangement->status_reason ) ) {
			$row['status_reason'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Cancellation reason', 'church-tithe-wp' ),
				'description'              => __( 'This indicates the status of the plan.', 'church-tithe-wp' ),
				'value'                    => $arrangement->status_reason,
			);
		}

		$row['live_mode'] = array(
			'show_in_list_view'        => false,
			'show_in_single_data_view' => true,
			'title'                    => __( 'Mode', 'church-tithe-wp' ),
			'description'              => __( 'This indicates whether the plan is a live mode plan, or a test mode plan.', 'church-tithe-wp' ),
			'value'                    => $arrangement->is_live_mode ? __( 'Live Mode', 'church-tithe-wp' ) : __( 'Test Mode', 'church-tithe-wp' ),
		);

		$row['date_created'] = array(
			'show_in_list_view'        => true,
			'show_in_single_data_view' => false,
			'title'                    => __( 'Created Date', 'church-tithe-wp' ),
			'description'              => __( 'This is the date the Plan was created.', 'church-tithe-wp' ),
			'value_type'               => 'date',
			'value_format_function'    => 'church_tithe_wp_list_view_format_date',
			'value'                    => $arrangement->date_created,
		);

		$row['date_created'] = array(
			'show_in_list_view'        => false,
			'show_in_single_data_view' => true,
			'title'                    => __( 'Created Date', 'church-tithe-wp' ),
			'description'              => __( 'This is the date the Plan was created.', 'church-tithe-wp' ),
			'value_type'               => 'date',
			'value_format_function'    => 'church_tithe_wp_list_view_format_date_and_time',
			'value'                    => $arrangement->date_created,
		);

		$row['initial_transaction_id'] = array(
			'show_in_list_view'              => false,
			'show_in_single_data_view'       => true,
			'title'                          => __( 'Initial Transaction ID', 'church-tithe-wp' ),
			'description'                    => __( 'This is ID of the original transaction in thus plan.', 'church-tithe-wp' ),
			'mpwpadmin_visual_state_onclick' => array(
				'transactions' => array(
					'single_data_view' => array(
						$arrangement->initial_transaction_id => array(),
					),
				),
			),
			'value'                          => $arrangement->initial_transaction_id,
		);

		$row['amount_per_interval'] = array(
			'show_in_list_view'        => true,
			'show_in_single_data_view' => true,
			'title'                    => __( 'Amount', 'church-tithe-wp' ),
			'description'              => __( 'This amount and how often it will happen.', 'church-tithe-wp' ),
			'value_type'               => 'money',
			'value_format_function'    => 'church_tithe_wp_list_view_format_money',
			'currency'                 => $arrangement->currency,
			'is_zero_decimal_currency' => church_tithe_wp_is_a_zero_decimal_currency( $arrangement->currency ),
			'cents'                    => $arrangement->renewal_amount,
			'locale'                   => get_user_locale(),
			// translators: This is the word between the amount and the interval. IE: $5 per week.
			'string_after'             => ' ' . __( 'per', 'church-tithe-wp' ) . ' ' . $arrangement->interval_string . ' (' . strtoupper( $arrangement->currency ) . ')',
		);

		if ( $arrangement->is_live_mode ) {
			$stripe_url = 'https://dashboard.stripe.com/subscriptions/' . $arrangement->gateway_subscription_id;
		} else {
			$stripe_url = 'https://dashboard.stripe.com/test/subscriptions/' . $arrangement->gateway_subscription_id;
		}

		if ( empty( $arrangement->gateway_subscription_id ) ) {
			// translators: This placeholder conditionally adds additional context if the WordPress isntallation is on a localhost.
			$gateway_id = sprintf( __( 'Stripe webhook failed! %s', 'church-tithe-wp' ), ( church_tithe_wp_is_site_localhost() ? __( '(Local websites cannot receive webhooks)', 'church-tithe-wp' ) : '' ) );

			$row['gateway_subscription_id'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Stripe ID', 'church-tithe-wp' ),
				'description'              => __( 'This is the ID of the transaction at Stripe.', 'church-tithe-wp' ),
				'value'                    => $gateway_id,
			);

		} else {
			$gateway_id = $arrangement->gateway_subscription_id;

			$row['gateway_subscription_id'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Stripe ID', 'church-tithe-wp' ),
				'description'              => __( 'This is the ID of the transaction at Stripe.', 'church-tithe-wp' ),
				'link_url'                 => $stripe_url,
				'link_target'              => '_blank',
				'value'                    => $gateway_id,
			);
		}

		$rows[] = $row;
	}

	return array(
		'rows'  => $rows,
		'count' => $arrangements_db->count( $query_args ),
	);

}

/**
 * Get Transaction History, with queryable variables to modify results.
 *
 * @since 1.0.0
 * @param string|array $query_args {
 *                            Optional. Array or query string of item query parameters. Default empty.
 *
 * @type   int          $number         Maximum number of items to retrieve. Default 20.
 * @type   int          $offset         Number of items to offset the query. Default 0.
 * @type   string|array $orderby        Transactions status or array of statuses. To use 'meta_value'
 *                                        or 'meta_value_num', `$meta_key` must also be provided.
 *                                        To sort by a specific `$meta_query` clause, use that
 *                                        clause's array key. Accepts 'id', 'user_id', 'name',
 *                                        'email', 'payment_ids', 'purchase_value', 'purchase_count',
 *                                        'notes', 'date_created', 'meta_value', 'meta_value_num',
 *                                        the value of `$meta_key`, and the array keys of `$meta_query`.
 *                                        Also accepts false, an empty array, or 'none' to disable the
 *                                        `ORDER BY` clause. Default 'id'.
 * @type   string       $order          How to order retrieved items. Accepts 'ASC', 'DESC'.
 *                                        Default 'DESC'.
 * @type   string|array $columns_values_included        String or array of item IDs to include. Default empty.
 * @type   string|array $columns_values_excluded        String or array of item IDs to exclude. Default empty.
 *                                        empty.
 * @type   string       $search         Search term(s) to retrieve matching items for. Searches
 *                                        through item names. Default empty.
 * @type   string|array $search_columns Columns to search using the value of `$search`. Default 'name'.
 * @type   array        $date_query     Date query clauses to limit retrieved items by.
 *                                        See `WP_Date_Query`. Default empty.
 * @type   bool         $count          Whether to return a count (true) instead of an array of
 *                                        item objects. Default false.
 * @type   bool         $no_found_rows  Whether to disable the `SQL_CALC_FOUND_ROWS` query.
 *                                        Default true.
 * @return array
 */
function church_tithe_wp_get_transaction_history_admin( $query_args = array() ) {
	$transactions_db = new Church_Tithe_WP_Transactions_DB();
	$transactions    = $transactions_db->get_transactions( $query_args );

	// Create an array of rows that we'll use to output the rows in React.
	$rows = array();

	// Loop through each transaction.
	foreach ( $transactions as $transaction ) {

		// Get the User's Info.
		$user = get_userdata( $transaction->user_id );

		if ( empty( $user ) ) {
			continue;
		}

		// Format the row data.
		$row = array();

		// If the webhook has failed for this, output a notice at the top.
		if ( empty( $transaction->gateway_fee_hc ) && 'refund' !== $transaction->type ) {

			$notice_message = __( 'The Stripe webhook failed to reach your website for this transaction!', 'church-tithe-wp' );

			$row['webhook_notice'] = array(
				'show_in_list_view'                => false,
				'show_in_single_data_view'         => true,
				'title'                            => __( 'Notice', 'church-tithe-wp' ),
				'description'                      => __( 'The Stripe webhook failed to reach your website.', 'church-tithe-wp' ),
				'mpwpadmin_visual_state_onclick'   => array(
					'welcome' => array(),
				),
				'mpwpadmin_lightbox_state_onclick' => array(
					'stripe_live_webhook_signature_health_check' => array(),
				),
				'value'                            => $notice_message,
			);

		}

		$row['user_list_view'] = array(
			'show_in_list_view'        => true,
			'show_in_single_data_view' => false,
			'title'                    => __( 'User', 'church-tithe-wp' ),
			'description'              => __( 'This is the email of the user who tithed you.', 'church-tithe-wp' ),
			'value'                    => $user->user_email,
		);

		$church_tithe_wp_name       = get_user_meta( $transaction->user_id, 'church_tithe_wp_name', true );
		$user_name_email_value = ! empty( $church_tithe_wp_name ) ? $church_tithe_wp_name . ' - ' . $user->user_email : $user->user_email;

		$row['user_single_view'] = array(
			'show_in_list_view'        => false,
			'show_in_single_data_view' => true,
			'title'                    => __( 'User', 'church-tithe-wp' ),
			'description'              => __( 'This is the email of the user who tithed you.', 'church-tithe-wp' ),
			'link_url'                 => admin_url( 'user-edit.php?user_id=' . $user->ID ),
			'value'                    => $user_name_email_value,
		);

		$row['id'] = array(
			'show_in_list_view'        => true,
			'show_in_single_data_view' => true,
			'title'                    => __( 'Transaction ID', 'church-tithe-wp' ),
			'description'              => __( 'This is the ID of the transaction in your WordPress database.', 'church-tithe-wp' ),
			'value'                    => $transaction->id,
		);

		// If this is a refund, show the transaction ID that was refunded.
		if ( 'refund' === $transaction->type ) {
			$row['refunded_id'] = array(
				'show_in_list_view'              => false,
				'show_in_single_data_view'       => true,
				'title'                          => __( 'Refunded Transaction ID', 'church-tithe-wp' ),
				'description'                    => __( 'This is the ID of the transaction which was refunded.', 'church-tithe-wp' ),
				'mpwpadmin_visual_state_onclick' => array(
					'transactions' => array(
						'single_data_view' => array(
							$transaction->refund_id => array(),
						),
					),
				),
				'value'                          => $transaction->refund_id,
			);
		}

		$row['type'] = array(
			'show_in_list_view'        => true,
			'show_in_single_data_view' => true,
			'title'                    => __( 'Type', 'church-tithe-wp' ),
			'description'              => __( 'This is the type of transaction this is.', 'church-tithe-wp' ),
			'value'                    => ucfirst( $transaction->type ),
		);

		// If this transaction has been refunded set the status to "Refunded".
		if ( $transaction->refund_id && 'refund' !== $transaction->type ) {

			$row['status'] = array(
				'show_in_list_view'              => false,
				'show_in_single_data_view'       => true,
				'title'                          => __( 'Status', 'church-tithe-wp' ),
				'description'                    => __( 'This indicates whether the transaction is completed or refunded.', 'church-tithe-wp' ),
				'raw_value'                      => 'refunded',
				'mpwpadmin_visual_state_onclick' => array(
					'transactions' => array(
						'single_data_view' => array(
							$transaction->refund_id => array(),
						),
					),
				),
				'value'                          => __( 'Refunded (View refund details)', 'church-tithe-wp' ),
			);

			// If this is a refund transaction.
		} elseif ( $transaction->refund_id && 'refund' === $transaction->type ) {

			$row['status'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Status', 'church-tithe-wp' ),
				'description'              => __( 'This indicates whether the transaction is completed or refunded.', 'church-tithe-wp' ),
				'raw_value'                => 'refund',
				'value'                    => __( 'Refund completed', 'church-tithe-wp' ),
			);

			// If this is a normal transaction that has not been refunded.
		} else {

			$row['status'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Status', 'church-tithe-wp' ),
				'description'              => __( 'This indicates whether the transaction is completed or refunded.', 'church-tithe-wp' ),
				'raw_value'                => 'completed',
				'value'                    => __( 'Completed successfully', 'church-tithe-wp' ),
			);
		}

		$row['date_created_list_view'] = array(
			'show_in_list_view'        => true,
			'show_in_single_data_view' => false,
			'title'                    => __( 'Date Created', 'church-tithe-wp' ),
			'description'              => __( 'This is the date the transaction was initiated by the user.', 'church-tithe-wp' ),
			'value_type'               => 'date',
			'value_format_function'    => 'church_tithe_wp_list_view_format_date',
			'value'                    => $transaction->date_created,
		);

		$row['date_created_single_view'] = array(
			'show_in_list_view'        => false,
			'show_in_single_data_view' => true,
			'title'                    => __( 'Date Created', 'church-tithe-wp' ),
			'description'              => __( 'This is the date the transaction was initiated by the user.', 'church-tithe-wp' ),
			'value_type'               => 'date',
			'value_format_function'    => 'church_tithe_wp_list_view_format_date_and_time',
			'value'                    => $transaction->date_created,
		);

		// Don't show "date paid if this is a refund.
		if ( 'refund' !== $transaction->type ) {
			$row['date_paid'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Date Paid', 'church-tithe-wp' ),
				'description'              => __( 'This is the date the transaction was paid.', 'church-tithe-wp' ),
				'value_type'               => 'date',
				'value_format_function'    => 'church_tithe_wp_list_view_format_date_and_time',
				// translators: This placeholder conditionally adds additional context if the WordPress isntallation is on a localhost.
				'value'                    => ! empty( $transaction->date_paid ) ? $transaction->date_paid : sprintf( __( 'Stripe webhook failed! %s', 'church-tithe-wp' ), ( church_tithe_wp_is_site_localhost() ? __( '(Local websites cannot receive webhooks)', 'church-tithe-wp' ) : '' ) ),
			);
		}

		$row['amount'] = array(
			'show_in_list_view'        => true,
			'show_in_single_data_view' => true,
			'title'                    => __( 'Amount', 'church-tithe-wp' ),
			'description'              => __( 'This is the amount of the tithe.', 'church-tithe-wp' ),
			'value_type'               => 'money',
			'value_format_function'    => 'church_tithe_wp_list_view_format_money',
			'currency'                 => $transaction->charged_currency,
			'is_zero_decimal_currency' => church_tithe_wp_is_a_zero_decimal_currency( $transaction->charged_currency ),
			'cents'                    => $transaction->charged_amount,
			'locale'                   => get_user_locale(),
			'string_after'             => ' (' . strtoupper( $transaction->charged_currency ) . ')',
		);

		if ( 'refund' !== $transaction->type && empty( $transaction->gateway_fee_hc ) ) {
			// translators: This placeholder conditionally adds additional context if the WordPress isntallation is on a localhost.
			$gateway_fee = sprintf( __( 'Stripe webhook failed! %s', 'church-tithe-wp' ), ( church_tithe_wp_is_site_localhost() ? __( '(Local websites cannot receive webhooks)', 'church-tithe-wp' ) : '' ) );

			$row['gateway_fee'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Stripe Fee', 'church-tithe-wp' ),
				'description'              => __( 'This is the amount Stripe charged to process this transaction.', 'church-tithe-wp' ),
				'value'                    => $gateway_fee,
			);

		} else {

			$row['gateway_fee'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Stripe Fee', 'church-tithe-wp' ),
				'description'              => __( 'This is the amount Stripe charged to process this transaction.', 'church-tithe-wp' ),
				'value_type'               => 'money',
				'value_format_function'    => 'church_tithe_wp_list_view_format_money',
				'currency'                 => $transaction->home_currency,
				'is_zero_decimal_currency' => church_tithe_wp_is_a_zero_decimal_currency( $transaction->home_currency ),
				'cents'                    => $transaction->gateway_fee_hc,
				'locale'                   => get_user_locale(),
				'string_after'             => ' (' . strtoupper( $transaction->home_currency ) . ')',
			);

		}

		if ( 'refund' !== $transaction->type && empty( $transaction->gateway_fee_hc ) ) {
			// translators: This placeholder conditionally adds additional context if the WordPress isntallation is on a localhost.
			$earnings_hc = sprintf( __( 'Stripe webhook failed! %s', 'church-tithe-wp' ), ( church_tithe_wp_is_site_localhost() ? __( '(Local websites cannot receive webhooks)', 'church-tithe-wp' ) : '' ) );

			$row['earnings_hc'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Actual earnings after Stripe fees', 'church-tithe-wp' ),
				'description'              => __( 'This is the amount that Stripe will/did deposit into your bank account.', 'church-tithe-wp' ),
				'value'                    => $earnings_hc,
			);
		} else {
			$row['earnings_hc'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Actual earnings after Stripe fees', 'church-tithe-wp' ),
				'description'              => __( 'This is the amount that Stripe will/did deposit into your bank account.', 'church-tithe-wp' ),
				'value_type'               => 'money',
				'value_format_function'    => 'church_tithe_wp_list_view_format_money',
				'currency'                 => $transaction->home_currency,
				'is_zero_decimal_currency' => church_tithe_wp_is_a_zero_decimal_currency( $transaction->home_currency ),
				'cents'                    => $transaction->earnings_hc,
				'locale'                   => get_user_locale(),
				'string_after'             => ' (' . strtoupper( $transaction->home_currency ) . ')',
			);
		}

		$method = 'unknown';

		if ( 'payment-request' === $transaction->method ) {
			$method = __( 'Credit Card saved in browser (Google Pay, Microsoft Pay, Android Pay, or something similar)', 'church-tithe-wp' );
		}

		if ( 'apple-pay' === $transaction->method ) {
			$method = __( 'Apple Pay', 'church-tithe-wp' );
		}

		if ( 'basic-card' === $transaction->method ) {
			$method = __( 'Credit Card', 'church-tithe-wp' );
		}

		if ( 'subscription' === $transaction->method ) {
			$method = __( 'Automatic renewal payment', 'church-tithe-wp' );
		}

		$row['method'] = array(
			'show_in_list_view'        => false,
			'show_in_single_data_view' => true,
			'title'                    => __( 'Payment Method', 'church-tithe-wp' ),
			'description'              => __( 'This is the method used to pay (Apple Pay, Google Pay, Basic Card, etc).', 'church-tithe-wp' ),
			'value'                    => $method,
		);

		if ( 'refund' !== $transaction->type && empty( $transaction->charge_id ) ) {

			$row['charge_id'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Stripe ID', 'church-tithe-wp' ),
				'description'              => __( 'This is the ID of the transaction at Stripe.', 'church-tithe-wp' ),
				// translators: This placeholder conditionally adds additional context if the WordPress isntallation is on a localhost.
				'value'                    => sprintf( __( 'Stripe webhook failed! %s', 'church-tithe-wp' ), ( church_tithe_wp_is_site_localhost() ? __( '(Local websites cannot receive webhooks)', 'church-tithe-wp' ) : '' ) ),
			);

		} else {

			if ( $transaction->is_live_mode ) {
				$stripe_url = 'https://dashboard.stripe.com/search?query=' . $transaction->charge_id;
			} else {
				$stripe_url = 'https://dashboard.stripe.com/test/search?query=' . $transaction->charge_id;
			}

			$row['charge_id'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Stripe ID', 'church-tithe-wp' ),
				'description'              => __( 'This is the ID of the transaction at Stripe.', 'church-tithe-wp' ),
				'link_url'                 => $stripe_url,
				'link_target'              => '_blank',
				'value'                    => $transaction->charge_id,
			);

		}

		$arrangement = new Church_Tithe_WP_Arrangement( $transaction->arrangement_id );

		if ( ! empty( $arrangement->gateway_subscription_id ) ) {
			$row['arrangement'] = array(
				'show_in_list_view'              => false,
				'show_in_single_data_view'       => true,
				'title'                          => __( 'Related Plan', 'church-tithe-wp' ),
				'description'                    => __( 'Related Plan', 'church-tithe-wp' ),
				'mpwpadmin_visual_state_onclick' => array(
					'arrangements' => array(
						'single_data_view' => array(
							$transaction->arrangement_id => array(),
						),
					),
				),
				'value'                          => __( 'Plan:', 'church-tithe-wp' ) . ' ' . $transaction->arrangement_id,
			);
		}

		if (
			! empty( $transaction->period_start_date ) &&
			! empty( $transaction->period_end_date ) &&
			'0000-00-00 00:00:00' !== $transaction->period_start_date &&
			'0000-00-00 00:00:00' !== $transaction->period_end_date
		) {
			$row['period_start_date'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Period Start Date', 'church-tithe-wp' ),
				'description'              => __( 'This is the start date of the period this transaction covers.', 'church-tithe-wp' ),
				'value_type'               => 'date',
				'value_format_function'    => 'church_tithe_wp_list_view_format_date_and_time',
				'value'                    => $transaction->period_start_date,
			);

			$row['period_end_date'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Period End Date', 'church-tithe-wp' ),
				'description'              => __( 'This is the end date of the period this transaction covers.', 'church-tithe-wp' ),
				'value_type'               => 'date',
				'value_format_function'    => 'church_tithe_wp_list_view_format_date_and_time',
				'value'                    => $transaction->period_end_date,
			);
		}

		$row['page_url'] = array(
			'show_in_list_view'        => false,
			'show_in_single_data_view' => true,
			'title'                    => __( 'Page URL', 'church-tithe-wp' ),
			'description'              => __( 'This is the URL of the page where the tithe took place.', 'church-tithe-wp' ),
			'value'                    => $transaction->page_url,
		);

		$row['note_with_tithe'] = array(
			'show_in_list_view'        => false,
			'show_in_single_data_view' => true,
			'title'                    => __( 'Note with tithe', 'church-tithe-wp' ),
			'description'              => __( 'This is the note the user provided with their tithe.', 'church-tithe-wp' ),
			'value'                    => $transaction->note_with_tithe,
		);

		$row['is_live_mode'] = array(
			'show_in_list_view'        => true,
			'show_in_single_data_view' => true,
			'title'                    => __( 'Mode', 'church-tithe-wp' ),
			'description'              => __( 'This indicates whether the transaction was done in test mode or live mode at Stripe.', 'church-tithe-wp' ),
			'value'                    => $transaction->is_live_mode ? __( 'Live Mode', 'church-tithe-wp' ) : __( 'Test Mode', 'church-tithe-wp' ),
		);

		$rows[] = $row;
	}

	return array(
		'rows'  => $rows,
		'count' => $transactions_db->count( $query_args ),
	);

}
