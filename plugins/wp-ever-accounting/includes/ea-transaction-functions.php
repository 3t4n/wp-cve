<?php
/**
 * EverAccounting Transaction functions.
 *
 * Functions for all kind of transaction of the plugin.
 *
 * @since   1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Payment;
use EverAccounting\Models\Revenue;

defined( 'ABSPATH' ) || exit;

/**
 * Get Transaction Types
 *
 * @return array
 * @since 1.1.0
 */
function eaccounting_get_transaction_types() {
	$types = array(
		'income'  => esc_html__( 'Income', 'wp-ever-accounting' ),
		'expense' => esc_html__( 'Expense', 'wp-ever-accounting' ),
	);

	return apply_filters( 'eaccounting_transaction_types', $types );
}

/**
 * Get a single payment.
 *
 * @param Payment $payment Payment object.
 *
 * @return Payment|null
 * @since 1.1.0
 */
function eaccounting_get_payment( $payment ) {
	if ( empty( $payment ) ) {
		return null;
	}
	try {
		$result = new EverAccounting\Models\Payment( $payment );

		return $result->exists() ? $result : null;
	} catch ( \Exception $e ) {
		return null;
	}
}


/**
 *  Create new payment programmatically.
 *
 *  Returns a new payment object on success.
 *
 * @param array $args {
 * An array of elements that make up an expense to update or insert.
 *
 * @type int $id Transaction id. If the id is something other than 0 then it will update the transaction.
 * @type string $payment_date Time of the transaction. Default null.
 * @type string $amount Transaction amount. Default null.
 * @type int $account_id From/To which account the transaction is. Default empty.
 * @type int $contact_id Contact id related to the transaction. Default empty.
 * @type int $document_id Transaction related invoice id(optional). Default empty.
 * @type int $category_id Category of the transaction. Default empty.
 * @type string $payment_method Payment method used for the transaction. Default empty.
 * @type string $reference Reference of the transaction. Default empty.
 * @type string $description Description of the transaction. Default empty.
 *
 * }
 * @param bool  $wp_error Optional. Whether to return a WP_Error on failure. Default false.
 *
 * @return EverAccounting\Models\Payment|\WP_Error|bool
 * @since 1.1.0
 */
function eaccounting_insert_payment( $args, $wp_error = true ) {
	// Ensure that we have data.
	if ( empty( $args ) ) {
		return false;
	}
	try {
		// The  id will be provided when updating an item.
		$args = wp_parse_args( $args, array( 'id' => null ) );

		// Retrieve the expense.
		$item = new Payment( $args['id'] );

		// Load new data.
		$item->set_props( $args );

		// Save the item.
		$item->save();

		return $item;
	} catch ( \Exception $e ) {
		return $wp_error ? new WP_Error( 'insert_payment', $e->getMessage(), array( 'status' => $e->getCode() ) ) : 0;
	}
}

/**
 * Delete a payment.
 *
 * @param int $payment_id  Payment ID.
 *
 * @return bool
 * @since 1.1.0
 */
function eaccounting_delete_payment( $payment_id ) {
	try {
		$payment = new EverAccounting\Models\Payment( $payment_id );

		return $payment->exists() ? $payment->delete() : false;
	} catch ( \Exception $e ) {
		return false;
	}

}

/**
 * Get payment items.
 *
 * @param array $args { .
 *
 * @type int $id Transaction id.
 * @type string $payment_date Time of the transaction.
 * @type string $amount Transaction amount.
 * @type int $account_id From/To which account the transaction is.
 * @type int $contact_id Contact id related to the transaction.
 * @type int $document_id Transaction related invoice id(optional).
 * @type int $category_id Category of the transaction.
 * @type string $payment_method Payment method used for the transaction.
 * @type string $reference Reference of the transaction.
 * @type string $description Description of the transaction.
 *
 * }
 *
 * @return array|int
 * @since 1.1.0
 */
function eaccounting_get_payments( $args = array() ) {
	return eaccounting_get_transactions( array_merge( $args, array( 'type' => 'expense' ) ) );
}

/**
 * Get revenue.
 *
 * @param mixed $revenue Revenue ID or object.
 *
 * @return Revenue|null
 * @since 1.1.0
 */
function eaccounting_get_revenue( $revenue ) {
	if ( empty( $revenue ) ) {
		return null;
	}
	try {
		$result = new EverAccounting\Models\Revenue( $revenue );

		return $result->exists() ? $result : null;
	} catch ( \Exception $e ) {
		return null;
	}
}


/**
 *  Create new revenue programmatically.
 *
 *  Returns a new revenue object on success.
 *
 * @param array $args {
 *                              An array of elements that make up an expense to update or insert.
 *
 * @type int $id Transaction id. If the id is something other than 0 then it will update the transaction.
 * @type string $payment_date Time of the transaction. Default null.
 * @type string $amount Transaction amount. Default null.
 * @type int $account_id From/To which account the transaction is. Default empty.
 * @type int $contact_id Contact id related to the transaction. Default empty.
 * @type int $category_id Category of the transaction. Default empty.
 * @type string $payment_method Payment method used for the transaction. Default empty.
 * @type string $reference Reference of the transaction. Default empty.
 * @type string $description Description of the transaction. Default empty.
 *
 * }
 * @param bool  $wp_error Optional. Whether to return a WP_Error on failure. Default false.
 *
 * @return EverAccounting\Models\Revenue|\WP_Error|bool
 * @since 1.1.0
 */
function eaccounting_insert_revenue( $args, $wp_error = true ) {
	// Ensure that we have data.
	if ( empty( $args ) ) {
		return false;
	}
	try {
		// The  id will be provided when updating an item.
		$args = wp_parse_args( $args, array( 'id' => null ) );

		// Retrieve the income.
		$item = new Revenue( $args['id'] );

		// Load new data.
		$item->set_props( $args );

		// Save the item.
		$item->save();

		return $item;
	} catch ( \Exception $e ) {
		return $wp_error ? new WP_Error( 'insert_revenue', $e->getMessage(), array( 'status' => $e->getCode() ) ) : 0;
	}
}

/**
 * Delete a revenue.
 *
 * @param int $revenue_id  Revenue ID.
 *
 * @return bool
 * @since 1.1.0
 */
function eaccounting_delete_revenue( $revenue_id ) {
	try {
		$revenue = new EverAccounting\Models\Revenue( $revenue_id );

		return $revenue->exists() ? $revenue->delete() : false;
	} catch ( \Exception $e ) {
		return false;
	}
}

/**
 * Get revenues items.
 *
 * @param array $args {
 * An array of elements that make up an expense to update or insert.
 * @type int $id Transaction id.
 * @type string $payment_date Time of the transaction.
 * @type string $amount Transaction amount.
 * @type int $account_id From/To which account the transaction is.
 * @type int $contact_id Contact id related to the transaction.
 * @type int $document_id Transaction related invoice id(optional).
 * @type int $category_id Category of the transaction.
 * @type string $payment_method Payment method used for the transaction.
 * @type string $reference Reference of the transaction.
 * @type string $description Description of the transaction.
 *
 * }
 * @return Revenue[]|int
 * @since 1.1.0
 */
function eaccounting_get_revenues( $args = array() ) {
	return eaccounting_get_transactions( array_merge( $args, array( 'type' => 'income' ) ) );
}

/**
 * Get transfer.
 *
 * @param mixed $transfer Transfer id.
 *
 * @return \EverAccounting\Models\Transfer|null
 * @since 1.1.0
 */
function eaccounting_get_transfer( $transfer ) {
	if ( empty( $transfer ) ) {
		return null;
	}
	try {
		$result = new EverAccounting\Models\Transfer( $transfer );

		return $result->exists() ? $result : null;
	} catch ( \Exception $e ) {
		return null;
	}
}

/**
 * Create new transfer programmatically.
 *
 * Returns a new transfer object on success.
 *
 * @param array $args {
 *                               An array of elements that make up an transfer to update or insert.
 *
 * @type int $id ID of the transfer. If equal to something other than 0,
 *                               the post with that ID will be updated. Default 0.
 * @type int $from_account_id ID of the source account from where transfer is initiating.
 *                               default null.
 * @type int $to_account_id ID of the target account where the transferred amount will be
 *                               deposited. default null.
 * @type string $amount Amount of the money that will be transferred. default 0.
 * @type string $date Date of the transfer. default null.
 * @type string $payment_method Payment method used in transfer. default null.
 * @type string $reference Reference used in transfer. Default empty.
 * @type string $description Description of the transfer. Default empty.
 *
 * }
 * @param bool  $wp_error Optional. Whether to return a WP_Error on failure. Default false.
 *
 * @return \EverAccounting\Models\Transfer|\WP_Error|\bool
 * @throws \Exception When the transfer cannot be created.
 * @since 1.1.0
 */
function eaccounting_insert_transfer( $args, $wp_error = true ) {
	// Ensure that we have data.
	if ( empty( $args ) ) {
		return false;
	}

	try {
		// The id will be provided when updating an item.
		$args = wp_parse_args( $args, array( 'id' => null ) );

		if ( absint( $args['from_account_id'] ) === absint( $args['to_account_id'] ) ) {
			throw new \Exception( __( "Source and Destination account can't be same.", 'wp-ever-accounting' ) );
		}

		// Retrieve the transfer.
		$item = new \EverAccounting\Models\Transfer( $args['id'] );

		// Load new data.
		$item->set_props( $args );

		// Save the item.
		$item->save();

		return $item;
	} catch ( \Exception $e ) {
		return $wp_error ? new WP_Error( 'insert_transfer', $e->getMessage(), array( 'status' => $e->getCode() ) ) : 0;
	}
}

/**
 * Delete a transfer.
 *
 * @param int $transfer_id Transfer ID.
 *
 * @return bool
 * @since 1.1.0
 */
function eaccounting_delete_transfer( $transfer_id ) {
	try {
		$transfer = new EverAccounting\Models\Transfer( $transfer_id );

		return $transfer->exists() ? $transfer->delete() : false;
	} catch ( \Exception $e ) {
		return false;
	}
}

/**
 * Get transfers.
 *
 * @param array $args { An array of arguments.
 *
 * @type int $id ID of the transfer.
 * @type int $from_account_id ID of the source account from where transfer is initiating.
 * @type int $to_account_id ID of the target account where the transferred amount will be deposited.
 * @type string $amount Amount of the money that will be transferred.
 * @type string $date Date of the transfer.
 * @type string $payment_method Payment method used in transfer.
 * @type string $reference Reference used in transfer.
 * @type string $description Description of the transfer.
 *
 * }
 *
 * @return array|int
 * @since 1.1.0
 */
function eaccounting_get_transfers( $args = array() ) {
	// Prepare args.
	$args = wp_parse_args(
		$args,
		array(
			'include'     => '',
			'search'      => '',
			'from_id'     => '',
			'fields'      => '',
			'orderby'     => 'date_created',
			'order'       => 'ASC',
			'number'      => 20,
			'offset'      => 0,
			'paged'       => 1,
			'return'      => 'objects',
			'count_total' => false,
		)
	);
	global $wpdb;
	$qv           = apply_filters( 'eaccounting_get_transfers_args', $args );
	$table        = \EverAccounting\Repositories\Transfers::TABLE;
	$columns      = \EverAccounting\Repositories\Transfers::get_columns();
	$qv['fields'] = wp_parse_list( $qv['fields'] );
	foreach ( $qv['fields'] as $index => $field ) {
		if ( ! in_array( $field, $columns, true ) ) {
			unset( $qv['fields'][ $index ] );
		}
	}
	$fields = is_array( $qv['fields'] ) && ! empty( $qv['fields'] ) ? implode( ',', $qv['fields'] ) : 'ea_transfers.*';
	$where  = 'WHERE 1=1';

	if ( ! empty( $qv['include'] ) ) {
		$include = implode( ',', wp_parse_id_list( $qv['include'] ) );
		$where  .= " AND $table.`id` IN ($include)";
	} elseif ( ! empty( $qv['exclude'] ) ) {
		$exclude = implode( ',', wp_parse_id_list( $qv['exclude'] ) );
		$where  .= " AND $table.`id` NOT IN ($exclude)";
	}

	if ( ! empty( $qv['from_account_id'] ) ) {
		$from_account_in = implode( ',', wp_parse_id_list( $qv['from_account_id'] ) );
		$where          .= " AND expense.`account_id` IN ($from_account_in)";
	}

	if ( ! empty( $qv['to_account_id'] ) ) {
		$to_account_in = implode( ',', wp_parse_id_list( $qv['to_account_id'] ) );
		$where        .= " AND income.`account_id` IN ($to_account_in)";
	}

	$join  = " LEFT JOIN {$wpdb->prefix}ea_transactions expense ON (expense.id = ea_transfers.expense_id) ";
	$join .= " LEFT JOIN {$wpdb->prefix}ea_transactions income ON (income.id = ea_transfers.income_id) ";

	if ( ! empty( $qv['date_created'] ) && is_array( $qv['date_created'] ) ) {
		$date_created_query = new \WP_Date_Query( $qv['date_created'], "{$table}.date_created" );
		$where             .= $date_created_query->get_sql();
	}

	if ( ! empty( $qv['payment_date'] ) && is_array( $qv['payment_date'] ) ) {
		$date_created_query = new \WP_Date_Query( $qv['payment_date'], 'expense.payment_date' );
		$where             .= $date_created_query->get_sql();
	}

	$order   = isset( $qv['order'] ) ? strtoupper( $qv['order'] ) : 'ASC';
	$orderby = empty( $qv['orderby'] ) ? 'date_created' : eaccounting_clean( $qv['orderby'] );
	if ( in_array( $qv['orderby'], $columns, true ) ) {
		$orderby = "$table." . $qv['orderby'];
	} elseif ( in_array( $qv['orderby'], array( 'from_account_id' ), true ) ) {
		$orderby = 'expense.account_id';
	} elseif ( in_array( $qv['orderby'], array( 'amount', 'reference' ), true ) ) {
		$orderby = 'expense.' . $qv['orderby'];
	} elseif ( in_array( $qv['orderby'], array( 'to_account_id' ), true ) ) {
		$orderby = 'income.account_id';
	} else {
		$orderby = "$table.id";
	}

	$limit = '';
	if ( isset( $qv['number'] ) && $qv['number'] > 0 ) {
		if ( $qv['offset'] ) {
			$limit = $wpdb->prepare( 'LIMIT %d, %d', $qv['offset'], $qv['number'] );
		} else {
			$limit = $wpdb->prepare( 'LIMIT %d, %d', $qv['number'] * ( $qv['paged'] - 1 ), $qv['number'] );
		}
	}
	$select      = "SELECT {$fields}";
	$from        = "FROM {$wpdb->prefix}$table $table";
	$orderby     = "ORDER BY {$orderby} {$order}";
	$count_total = true === $qv['count_total'];
	$clauses     = compact( 'select', 'from', 'join', 'where', 'orderby', 'limit' );
	$cache_key   = 'query:' . md5( maybe_serialize( $qv ) ) . ':' . wp_cache_get_last_changed( 'ea_transfers' );
	$results     = wp_cache_get( $cache_key, 'ea_transfers' );
	if ( false === $results ) {
		if ( $count_total ) {
			$results = (int) $wpdb->get_var( "SELECT COUNT($table.id) $from $join $where" );
			wp_cache_set( $cache_key, $results, 'ea_transfers' );
		} else {
			$results = $wpdb->get_results( implode( ' ', $clauses ) );
			if ( in_array( $fields, array( 'all', '*', 'ea_transfers.*' ), true ) ) {
				foreach ( $results as $key => $item ) {
					wp_cache_set( $item->id, $item, 'ea_transfers' );
				}
			}
			wp_cache_set( $cache_key, $results, 'ea_transfers' );
		}
	}

	if ( 'objects' === $qv['return'] && true !== $qv['count_total'] ) {
		$results = array_map( 'eaccounting_get_transfer', $results );
	}

	return $results;
}

/**
 * Get transaction items.
 *
 * @param array $args Query arguments.
 *
 * @return array|Payment[]|Revenue[]|int
 * @since 1.0.
 */
function eaccounting_get_transactions( $args = array() ) {
	// Prepare args.
	$args = wp_parse_args(
		$args,
		array(
			'type'        => '',
			'include'     => '',
			'search'      => '',
			'transfer'    => true,
			'fields'      => '*',
			'orderby'     => 'payment_date',
			'order'       => 'ASC',
			'number'      => 20,
			'offset'      => 0,
			'paged'       => 1,
			'return'      => 'objects',
			'count_total' => false,
		)
	);
	global $wpdb;
	$qv           = apply_filters( 'eaccounting_get_transactions_args', $args );
	$table        = \EverAccounting\Repositories\Transactions::TABLE;
	$columns      = \EverAccounting\Repositories\Transactions::get_columns();
	$qv['fields'] = wp_parse_list( $qv['fields'] );
	foreach ( $qv['fields'] as $index => $field ) {
		if ( ! in_array( $field, $columns, true ) ) {
			unset( $qv['fields'][ $index ] );
		}
	}
	$fields = is_array( $qv['fields'] ) && ! empty( $qv['fields'] ) ? implode( ',', $qv['fields'] ) : '*';
	$where  = 'WHERE 1=1';
	if ( ! empty( $qv['include'] ) ) {
		$include = implode( ',', wp_parse_id_list( $qv['include'] ) );
		$where  .= " AND $table.`id` IN ($include)";
	} elseif ( ! empty( $qv['exclude'] ) ) {
		$exclude = implode( ',', wp_parse_id_list( $qv['exclude'] ) );
		$where  .= " AND $table.`id` NOT IN ($exclude)";
	}
	// search.
	$search_cols = array( 'description', 'reference' );
	if ( ! empty( $qv['search'] ) ) {
		$searches = array();
		$where   .= ' AND (';
		foreach ( $search_cols as $col ) {
			$searches[] = $wpdb->prepare( $col . ' LIKE %s', '%' . $wpdb->esc_like( $qv['search'] ) . '%' );
		}
		$where .= implode( ' OR ', $searches );
		$where .= ')';
	}

	if ( ! empty( $qv['type'] ) ) {
		$types  = implode( "','", wp_parse_list( $qv['type'] ) );
		$where .= " AND $table.`type` IN ('$types')";
	}

	if ( ! empty( $qv['currency_code'] ) ) {
		$currency_code = implode( "','", wp_parse_list( $qv['currency_code'] ) );
		$where        .= " AND $table.`currency_code` IN ('$currency_code')";
	}

	if ( ! empty( $qv['payment_method'] ) ) {
		$payment_method = implode( "','", wp_parse_list( $qv['payment_method'] ) );
		$where         .= " AND $table.`payment_method` IN ('$payment_method')";
	}

	if ( ! empty( $qv['account_id'] ) ) {
		$account_id = implode( ',', wp_parse_id_list( $qv['account_id'] ) );
		$where     .= " AND $table.`account_id` IN ($account_id)";
	}

	if ( ! empty( $qv['document_id'] ) ) {
		$document_id = implode( ',', wp_parse_id_list( $qv['document_id'] ) );
		$where      .= " AND $table.`document_id` IN ($document_id)";
	}

	if ( ! empty( $qv['category_id'] ) ) {
		$category_in = implode( ',', wp_parse_id_list( $qv['category_id'] ) );
		$where      .= " AND $table.`category_id` IN ($category_in)";
	}

	if ( ! empty( $qv['contact_id'] ) ) {
		$contact_id = implode( ',', wp_parse_id_list( $qv['contact_id'] ) );
		$where     .= " AND $table.`contact_id` IN ($contact_id)";
	}

	if ( ! empty( $qv['parent_id'] ) ) {
		$parent_id = implode( ',', wp_parse_id_list( $qv['parent_id'] ) );
		$where    .= " AND $table.`parent_id` IN ($parent_id)";
	}

	if ( ! empty( $qv['date_created'] ) && is_array( $qv['date_created'] ) ) {
		$date_created_query = new \WP_Date_Query( $qv['date_created'], "{$table}.date_created" );
		$where             .= $date_created_query->get_sql();
	}

	if ( ! empty( $qv['payment_date'] ) && is_array( $qv['payment_date'] ) ) {
		$before = $qv['payment_date']['before'];
		$after  = $qv['payment_date']['after'];
		$where .= " AND $table.`payment_date` BETWEEN '$before' AND '$after'";
	}

	if ( ! empty( $qv['creator_id'] ) ) {
		$creator_id = implode( ',', wp_parse_id_list( $qv['creator_id'] ) );
		$where     .= " AND $table.`creator_id` IN ($creator_id)";
	}

	if ( true === $qv['transfer'] ) {
		$where .= " AND $table.`category_id` NOT IN (SELECT id from {$wpdb->prefix}ea_categories where type='other' )";
	}

	$order   = isset( $qv['order'] ) ? strtoupper( $qv['order'] ) : 'ASC';
	$orderby = isset( $qv['orderby'] ) && in_array( $qv['orderby'], $columns, true ) ? eaccounting_clean( $qv['orderby'] ) : "{$table}.id";

	$limit = '';
	if ( isset( $qv['number'] ) && $qv['number'] > 0 ) {
		if ( $qv['offset'] ) {
			$limit = $wpdb->prepare( 'LIMIT %d, %d', $qv['offset'], $qv['number'] );
		} else {
			$limit = $wpdb->prepare( 'LIMIT %d, %d', $qv['number'] * ( $qv['paged'] - 1 ), $qv['number'] );
		}
	}

	$select      = "SELECT {$fields}";
	$from        = "FROM {$wpdb->prefix}$table $table";
	$orderby     = "ORDER BY {$orderby} {$order}";
	$count_total = true === $qv['count_total'];
	$cache_key   = 'query:' . md5( maybe_serialize( $qv ) ) . ':' . wp_cache_get_last_changed( 'ea_transactions' );
	$results     = wp_cache_get( sanitize_key( $cache_key ), 'ea_transactions' );
	$clauses     = compact( 'select', 'from', 'where', 'orderby', 'limit' );

	if ( false === $results ) {
		if ( $count_total ) {
			$results = (int) $wpdb->get_var( "SELECT COUNT(id) $from $where" );
			wp_cache_set( $cache_key, $results, 'ea_transactions' );
		} else {
			$results = $wpdb->get_results( implode( ' ', $clauses ) );
			if ( in_array( $fields, array( 'all', '*' ), true ) ) {
				foreach ( $results as $key => $item ) {
					wp_cache_set( $item->id, $item, 'ea_transactions' );
				}
			}
			wp_cache_set( $cache_key, $results, 'ea_transactions' );
		}
	}

	if ( 'objects' === $qv['return'] && true !== $qv['count_total'] ) {
		$results = array_map(
			function ( $item ) {
				switch ( $item->type ) {
					case 'income':
						$transaction = new Revenue();
						$transaction->set_props( $item );
						$transaction->set_object_read( true );

						break;
					case 'expense':
						$transaction = new Payment();
						$transaction->set_props( $item );
						$transaction->set_object_read( true );

						break;
					default:
						$transaction = apply_filters( 'eaccounting_transaction_object_' . $item->type, null, $item );
				}

				return $transaction;
			},
			$results
		);
	}

	return $results;
}

/**
 * Get total income.
 *
 * @param null $year Year.
 *
 * @return float
 * @since 1.1.0
 */
function eaccounting_get_total_income( $year = null ) {
	global $wpdb;
	$total_income = wp_cache_get( 'total_income_' . $year, 'ea_transactions' );
	if ( false === $total_income ) {
		$where = '';
		if ( absint( $year ) ) {
			$financial_start = eaccounting_get_financial_start( $year );
			$financial_end   = eaccounting_get_financial_end( $year );
			$where          .= $wpdb->prepare( 'AND ( payment_date between %s AND %s )', $financial_start, $financial_end );
		}

		$results      = $wpdb->get_results(
			$wpdb->prepare(
				" SELECT Sum(amount) amount,currency_code,currency_rate
				FROM   {$wpdb->prefix}ea_transactions
				WHERE 1=1 $where AND type = %s AND category_id NOT IN (SELECT id FROM   {$wpdb->prefix}ea_categories WHERE  type = 'other')
				GROUP  BY currency_code, currency_rate
			",
				'income'
			)
		);
		$total_income = 0;
		foreach ( $results as $result ) {
			$total_income += eaccounting_price_to_default( $result->amount, $result->currency_code, $result->currency_rate );
		}
		wp_cache_add( 'total_income_' . $year, $total_income, 'ea_transactions' );
	}

	return $total_income;
}

/**
 * Get total expense.
 *
 * @param null $year Year.
 *
 * @return float
 * @since 1.1.0
 */
function eaccounting_get_total_expense( $year = null ) {
	global $wpdb;
	$total_expense = wp_cache_get( 'total_expense_' . $year, 'ea_transactions' );
	if ( false === $total_expense ) {
		$where = '';
		if ( absint( $year ) ) {
			$financial_start = eaccounting_get_financial_start( $year );
			$financial_end   = eaccounting_get_financial_end( $year );
			$where          .= $wpdb->prepare( 'AND ( payment_date between %s AND %s )', $financial_start, $financial_end );
		}

		$results       = $wpdb->get_results(
			$wpdb->prepare(
				" SELECT Sum(amount) amount,currency_code,currency_rate
				FROM   {$wpdb->prefix}ea_transactions
				WHERE 1=1 $where AND type = %s AND category_id NOT IN (SELECT id FROM   {$wpdb->prefix}ea_categories WHERE  type = 'other')
				GROUP  BY currency_code, currency_rate
			",
				'expense'
			)
		);
		$total_expense = 0;
		foreach ( $results as $result ) {
			$total_expense += eaccounting_price_to_default( $result->amount, $result->currency_code, $result->currency_rate );
		}
		wp_cache_add( 'total_expense_' . $year, $total_expense, 'ea_transactions' );
	}

	return $total_expense;
}

/**
 * Get total profit.
 *
 * @param null $year Year.
 *
 * @return float
 * @since 1.1.0
 */
function eaccounting_get_total_profit( $year = null ) {
	$total_income  = (float) eaccounting_get_total_income( $year );
	$total_expense = (float) eaccounting_get_total_expense( $year );
	$profit        = $total_income - $total_expense;

	return $profit < 0 ? 0 : $profit;
}

/**
 * Get total receivable.
 *
 * @return false|float|int|mixed|string
 * @since 1.1.0
 */
function eaccounting_get_total_receivable() {
	global $wpdb;
	$total_receivable = wp_cache_get( 'total_receivable', 'ea_transactions' );
	if ( false === $total_receivable ) {
		$total_receivable = 0;
		$invoices_sql     = $wpdb->prepare(
			"
			SELECT SUM(total) amount, currency_code, currency_rate  FROM   {$wpdb->prefix}ea_documents
			WHERE  status NOT IN ( 'draft', 'cancelled', 'refunded' )
			AND `status` <> 'paid'  AND type = %s GROUP BY currency_code, currency_rate
			",
			'invoice'
		);
		$invoices         = $wpdb->get_results( $invoices_sql );
		foreach ( $invoices as $invoice ) {
			$total_receivable += eaccounting_price_to_default( $invoice->amount, $invoice->currency_code, $invoice->currency_rate );
		}
		$sql     = $wpdb->prepare(
			"
		  SELECT Sum(amount) amount, currency_code, currency_rate
		  FROM   {$wpdb->prefix}ea_transactions
		  WHERE  type = %s
				 AND document_id IN (SELECT id FROM   {$wpdb->prefix}ea_documents WHERE  status NOT IN ( 'draft', 'cancelled', 'refunded' )
				 AND `status` <> 'paid'
				 AND type = 'invoice')
		  GROUP  BY currency_code,currency_rate
		  ",
			'income'
		);
		$results = $wpdb->get_results( $sql );
		foreach ( $results as $result ) {
			$total_receivable -= eaccounting_price_to_default( $result->amount, $result->currency_code, $result->currency_rate );
		}
		wp_cache_add( 'total_receivable', $total_receivable, 'ea_transactions' );
	}

	return $total_receivable;
}

/**
 * Get total payable.
 *
 * @return float
 * @since 1.1.0
 */
function eaccounting_get_total_payable() {
	global $wpdb;
	$total_payable = wp_cache_get( 'total_payable', 'ea_transactions' );
	if ( false === $total_payable ) {
		$total_payable = 0;
		$bills_sql     = $wpdb->prepare(
			"
			SELECT SUM(total) amount, currency_code, currency_rate  FROM   {$wpdb->prefix}ea_documents
			WHERE  status NOT IN ( 'draft', 'cancelled', 'refunded' )
			AND `status` <> 'paid'  AND type = %s GROUP BY currency_code, currency_rate
			",
			'bill'
		);
		$bills         = $wpdb->get_results( $bills_sql );
		foreach ( $bills as $bill ) {
			$total_payable += eaccounting_price_to_default( $bill->amount, $bill->currency_code, $bill->currency_rate );
		}
		$sql     = $wpdb->prepare(
			"
		  SELECT Sum(amount) amount, currency_code, currency_rate
		  FROM   {$wpdb->prefix}ea_transactions
		  WHERE  type = %s
				 AND document_id IN (SELECT id FROM   {$wpdb->prefix}ea_documents WHERE  status NOT IN ( 'draft', 'cancelled', 'refunded' )
				 AND `status` <> 'paid'
				 AND type = 'bill')
		  GROUP  BY currency_code,currency_rate
		  ",
			'expense'
		);
		$results = $wpdb->get_results( $sql );
		foreach ( $results as $result ) {
			$total_payable -= eaccounting_price_to_default( $result->amount, $result->currency_code, $result->currency_rate );
		}
		wp_cache_add( 'total_payable', $total_payable, 'ea_transactions' );
	}

	return $total_payable;
}

/**
 * Get total upcoming profit
 *
 * @return float
 * @since 1.1.0
 */
function eaccounting_get_total_upcoming_profit() {
	$total_payable    = (float) eaccounting_get_total_payable();
	$total_receivable = (float) eaccounting_get_total_receivable();
	$upcoming         = $total_receivable - $total_payable;

	return $upcoming < 0 ? 0 : $upcoming;
}
