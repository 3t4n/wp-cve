<?php
/**
 * Church Tithe WP
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/Church Tithe WP
 * @copyright   Copyright (c) 2018, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Get Arrangement History, with queryable variables to modify results.
 *
 * @since    1.0.0
 * @param mixed $query_args {
 *     Optional. Array or query string of item query parameters. Default empty.
 *
 *     @type int          $number         Maximum number of items to retrieve. Default 20.
 *     @type int          $offset         Number of items to offset the query. Default 0.
 *     @type string|array $orderby        Transactions status or array of statuses. To use 'meta_value'
 *                                        or 'meta_value_num', `$meta_key` must also be provided.
 *                                        To sort by a specific `$meta_query` clause, use that
 *                                        clause's array key. Accepts 'id', 'user_id', 'name',
 *                                        'email', 'payment_ids', 'purchase_value', 'purchase_count',
 *                                        'notes', 'date_created', 'meta_value', 'meta_value_num',
 *                                        the value of `$meta_key`, and the array keys of `$meta_query`.
 *                                        Also accepts false, an empty array, or 'none' to disable the
 *                                        `ORDER BY` clause. Default 'id'.
 *     @type string       $order          How to order retrieved items. Accepts 'ASC', 'DESC'.
 *                                        Default 'DESC'.
 *     @type string|array $columns_values_included        String or array of item IDs to include. Default empty.
 *     @type string|array $columns_values_excluded        String or array of item IDs to exclude. Default empty.
 *                                        empty.
 *     @type string       $search         Search term(s) to retrieve matching items for. Searches
 *                                        through item names. Default empty.
 *     @type string|array $search_columns Columns to search using the value of `$search`. Default 'name'.
 *     @type array        $date_query     Date query clauses to limit retrieved items by.
 *                                        See `WP_Date_Query`. Default empty.
 *     @type bool         $count          Whether to return a count (true) instead of an array of
 *                                        item objects. Default false.
 *     @type bool         $no_found_rows  Whether to disable the `SQL_CALC_FOUND_ROWS` query.
 *
 * @param    array $columns_to_return The columns we would like to get.
 * @return   array
 */
function church_tithe_wp_get_arrangement_history_frontend( $query_args = array(), $columns_to_return ) {

	$arrangements_db = new Church_Tithe_WP_Arrangements_DB();
	$arrangements    = $arrangements_db->get_arrangements( $query_args );

	// Create an array of rows that we'll use to output the rows in React.
	$rows = array();

	// Loop through each arrangement.
	foreach ( $arrangements as $arrangement ) {

		// Get the User's Info.
		$user = get_userdata( $arrangement->user_id );

		if ( empty( $user ) ) {
			continue;
		}

		// Format the row data.
		$row = array();

		if ( array_key_exists( 'manage', $columns_to_return ) ) {
			$row['manage'] = array(
				'show_in_list_view'        => true,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Manage', 'church-tithe-wp' ),
				'description'              => __( 'This is simply a button which can be used to manage this plan.', 'church-tithe-wp' ),
				'value'                    => __( 'Manage', 'church-tithe-wp' ),
			);
		}

		if ( array_key_exists( 'status', $columns_to_return ) ) {
			$row['status'] = array(
				'show_in_list_view'        => true,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Status', 'church-tithe-wp' ),
				'description'              => __( 'This indicates the status of the plan.', 'church-tithe-wp' ),
				'value'                    => ucfirst( $arrangement->recurring_status ),
			);
		}

		if ( array_key_exists( 'id', $columns_to_return ) ) {
			$row['id'] = array(
				'show_in_list_view'        => true,
				'show_in_single_data_view' => true,
				'title'                    => __( 'ID', 'church-tithe-wp' ),
				'description'              => __( 'This is the ID of the arrangement in your WordPress database.', 'church-tithe-wp' ),
				'value'                    => $arrangement->id,
			);
		}

		if ( array_key_exists( 'date_created', $columns_to_return ) ) {
			$row['date_created'] = array(
				'show_in_list_view'        => true,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Created Date', 'church-tithe-wp' ),
				'description'              => __( 'This is the date the Plan was created.', 'church-tithe-wp' ),
				'value_type'               => 'date',
				'value_format_function'    => 'church_tithe_wp_list_view_format_date',
				'value'                    => $arrangement->date_created,
			);
		}

		if ( array_key_exists( 'user', $columns_to_return ) ) {
			$row['user'] = array(
				'show_in_list_view'        => true,
				'show_in_single_data_view' => true,
				'title'                    => __( 'User', 'church-tithe-wp' ),
				'description'              => __( 'This is the email of the user who tithed you.', 'church-tithe-wp' ),
				'value'                    => $user->user_email,
			);
		}

		if ( array_key_exists( 'initial_transaction_id', $columns_to_return ) ) {
			$row['initial_transaction_id'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Initial Transaction ID', 'church-tithe-wp' ),
				'description'              => __( 'This is ID of the original transaction in thus plan.', 'church-tithe-wp' ),
				'value'                    => $arrangement->initial_transaction_id,
			);
		}

		if ( array_key_exists( 'amount_per_interval', $columns_to_return ) ) {
			$row['amount_per_interval'] = array(
				'show_in_list_view'        => true,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Interval', 'church-tithe-wp' ),
				'description'              => __( 'This is space between payments.', 'church-tithe-wp' ),
				'value_type'               => 'money',
				'value_format_function'    => 'church_tithe_wp_list_view_format_money',
				'currency'                 => $arrangement->currency,
				'is_zero_decimal_currency' => church_tithe_wp_is_a_zero_decimal_currency( $arrangement->currency ),
				'cents'                    => $arrangement->renewal_amount,
				'locale'                   => get_user_locale(),
				'string_after'             => ' ' . __( 'per', 'church-tithe-wp' ) . ' ' . $arrangement->interval_string,
			);
		}

		if ( array_key_exists( 'currency', $columns_to_return ) ) {
			$row['currency'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Currency', 'church-tithe-wp' ),
				'description'              => __( 'This is currency of the arrangement.', 'church-tithe-wp' ),
				'value'                    => $arrangement->currency,
			);
		}

		if ( array_key_exists( 'initial_amount', $columns_to_return ) ) {
			$row['initial_amount'] = array(
				'show_in_list_view'        => true,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Initial Amount', 'church-tithe-wp' ),
				'description'              => __( 'This is the initial amount of the plan.', 'church-tithe-wp' ),
				'value_type'               => 'money',
				'value_format_function'    => 'church_tithe_wp_list_view_format_money',
				'currency'                 => $arrangement->currency,
				'is_zero_decimal_currency' => church_tithe_wp_is_a_zero_decimal_currency( $arrangement->currency ),
				'cents'                    => $arrangement->initial_amount,
				'locale'                   => get_user_locale(),
				'string_after'             => ' (' . strtoupper( $arrangement->currency ) . ')',

			);
		}

		if ( array_key_exists( 'renewal_amount', $columns_to_return ) ) {
			$row['renewal_amount'] = array(
				'show_in_list_view'        => true,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Renewal Amount', 'church-tithe-wp' ),
				'description'              => __( 'This is the renewal amount of the plan.', 'church-tithe-wp' ),
				'value_type'               => 'money',
				'value_format_function'    => 'church_tithe_wp_list_view_format_money',
				'currency'                 => $arrangement->currency,
				'is_zero_decimal_currency' => church_tithe_wp_is_a_zero_decimal_currency( $arrangement->currency ),
				'cents'                    => $arrangement->renewal_amount,
				'locale'                   => get_user_locale(),
				'string_after'             => ' (' . strtoupper( $arrangement->currency ) . ')',
			);
		}

		if ( array_key_exists( 'recurring_status', $columns_to_return ) ) {
			$row['recurring_status'] = array(
				'show_in_list_view'        => true,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Recurring Status', 'church-tithe-wp' ),
				'description'              => __( 'This is the status of the plan.', 'church-tithe-wp' ),
				'value'                    => $arrangement->recurring_status,
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
 * @param array $query_args {
 *     Optional. Array or query string of item query parameters. Default empty.
 *
 *     @type int          $number         Maximum number of items to retrieve. Default 20.
 *     @type int          $offset         Number of items to offset the query. Default 0.
 *     @type string|array $orderby        Transactions status or array of statuses. To use 'meta_value'
 *                                        or 'meta_value_num', `$meta_key` must also be provided.
 *                                        To sort by a specific `$meta_query` clause, use that
 *                                        clause's array key. Accepts 'id', 'user_id', 'name',
 *                                        'email', 'payment_ids', 'purchase_value', 'purchase_count',
 *                                        'notes', 'date_created', 'meta_value', 'meta_value_num',
 *                                        the value of `$meta_key`, and the array keys of `$meta_query`.
 *                                        Also accepts false, an empty array, or 'none' to disable the
 *                                        `ORDER BY` clause. Default 'id'.
 *     @type string       $order          How to order retrieved items. Accepts 'ASC', 'DESC'.
 *                                        Default 'DESC'.
 *     @type string|array $columns_values_included        String or array of item IDs to include. Default empty.
 *     @type string|array $columns_values_excluded        String or array of item IDs to exclude. Default empty.
 *                                        empty.
 *     @type string       $search         Search term(s) to retrieve matching items for. Searches
 *                                        through item names. Default empty.
 *     @type string|array $search_columns Columns to search using the value of `$search`. Default 'name'.
 *     @type array        $date_query     Date query clauses to limit retrieved items by.
 *                                        See `WP_Date_Query`. Default empty.
 *     @type bool         $count          Whether to return a count (true) instead of an array of
 *                                        item objects. Default false.
 *     @type bool         $no_found_rows  Whether to disable the `SQL_CALC_FOUND_ROWS` query.
 *                                        Default true.
 * @param  array $columns_to_return The columns we want to return.
 * @return array
 */
function church_tithe_wp_get_transaction_history_frontend( $query_args = array(), $columns_to_return ) {

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

		if ( array_key_exists( 'manage', $columns_to_return ) ) {
			$row['manage'] = array(
				'show_in_list_view'        => true,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Manage', 'church-tithe-wp' ),
				'description'              => __( 'This is simply a button which can be used to manage this transaction.', 'church-tithe-wp' ),
				'value'                    => __( 'Receipt', 'church-tithe-wp' ),
			);
		}

		if ( array_key_exists( 'id', $columns_to_return ) ) {
			$row['id'] = array(
				'show_in_list_view'        => true,
				'show_in_single_data_view' => true,
				'title'                    => __( 'ID', 'church-tithe-wp' ),
				'description'              => __( 'This is the ID of the transaction in your WordPress database.', 'church-tithe-wp' ),
				'value'                    => $transaction->id,
			);
		}

		if ( array_key_exists( 'date_created', $columns_to_return ) ) {
			$row['date_created'] = array(
				'show_in_list_view'        => true,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Date', 'church-tithe-wp' ),
				'description'              => __( 'This is the date the transaction was created.', 'church-tithe-wp' ),
				'value_type'               => 'date',
				'value_format_function'    => 'church_tithe_wp_list_view_format_date',
				'value'                    => $transaction->date_created,
			);
		}

		if ( array_key_exists( 'date_paid', $columns_to_return ) ) {
			$row['date_paid'] = array(
				'show_in_list_view'        => true,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Date', 'church-tithe-wp' ),
				'description'              => __( 'This is the date the transaction was paid.', 'church-tithe-wp' ),
				'value_type'               => 'date',
				'value_format_function'    => 'church_tithe_wp_list_view_format_date',
				'value'                    => $transaction->date_paid,
			);
		}

		if ( array_key_exists( 'user', $columns_to_return ) ) {
			$row['user'] = array(
				'show_in_list_view'        => true,
				'show_in_single_data_view' => true,
				'title'                    => __( 'User', 'church-tithe-wp' ),
				'description'              => __( 'This is the email of the user who tithed you.', 'church-tithe-wp' ),
				'value'                    => $user->user_email,
			);
		}

		if ( array_key_exists( 'note_with_tithe', $columns_to_return ) ) {
			$row['note_with_tithe'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Note with tithe', 'church-tithe-wp' ),
				'description'              => __( 'This is the note the user provided with their tithe.', 'church-tithe-wp' ),
				'value'                    => $transaction->note_with_tithe,
			);
		}

		if ( array_key_exists( 'amount', $columns_to_return ) ) {
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
		}

		if ( array_key_exists( 'gateway_fee', $columns_to_return ) ) {
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

		if ( array_key_exists( 'earnings_hc', $columns_to_return ) ) {
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

		if ( array_key_exists( 'page_url', $columns_to_return ) ) {
			$row['page_url'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Page URL', 'church-tithe-wp' ),
				'description'              => __( 'This is the URL of the page where the tithe took place.', 'church-tithe-wp' ),
				'value'                    => $transaction->page_url,
			);
		}

		if ( array_key_exists( 'method', $columns_to_return ) ) {
			$row['method'] = array(
				'show_in_list_view'        => false,
				'show_in_single_data_view' => true,
				'title'                    => __( 'Payment Method', 'church-tithe-wp' ),
				'description'              => __( 'This is the method used to pay (Apple Pay, Google Pay, Basic Card, etc).', 'church-tithe-wp' ),
				'value'                    => $transaction->method,
			);
		}

		$rows[] = $row;
	}

	return array(
		'rows'  => $rows,
		'count' => $transactions_db->count( $query_args ),
	);

}
