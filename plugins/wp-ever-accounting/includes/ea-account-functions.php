<?php
/**
 * EverAccounting account Functions.
 *
 * All account related function of the plugin.
 *
 * @since   1.1.0
 * @package EverAccounting
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main function for returning account.
 *
 * @since 1.1.0
 *
 * @param mixed $account Account ID or object.
 *
 * @return EverAccounting\Models\Account|null
 */
function eaccounting_get_account( $account ) {
	if ( empty( $account ) ) {
		return null;
	}
	try {
		$result = new EverAccounting\Models\Account( $account );

		return $result->exists() ? $result : null;
	} catch ( \Exception $e ) {
		return null;
	}
}

/**
 * Get account currency code.
 *
 * @since 1.1.0
 *
 * @param mixed $account   Account ID or object.
 *
 * @return mixed|null
 */
function eaccounting_get_account_currency_code( $account ) {
	$exist = eaccounting_get_account( $account );
	if ( $exist ) {
		return $exist->get_currency_code();
	}

	return null;
}

/**
 *  Create new account programmatically.
 *
 *  Returns a new account object on success.
 *
 * @since 1.1.0
 *
 * @param array $data            {
 *                               An array of elements that make up an account to update or insert.
 *
 * @type int    $id              The account ID. If equal to something other than 0,
 *                                         the account with that id will be updated. Default 0.
 *
 * @type string $name            The name of the account . Default empty.
 *
 * @type string $number          The number of account. Default empty.
 *
 * @type string $currency_code   The currency_code for the account.Default is empty.
 *
 * @type double $opening_balance The opening balance of the account. Default 0.0000.
 *
 * @type string $bank_name       The bank name for the account. Default null.
 *
 * @type string $bank_phone      The phone number of the bank on which the account is opened. Default null.
 *
 * @type string $bank_address    The address of the bank. Default null.
 *
 * @type int    $enabled         The status of the account. Default 1.
 *
 * @type int    $creator_id      The creator id for the account. Default is current user id of the WordPress.
 *
 * @type string $date_created    The date when the account is created. Default is current time.
 * }
 * @param bool  $wp_error Optional. Whether to return a WP_Error on failure. Default false.
 *
 * @return EverAccounting\Models\Account|\WP_Error|bool
 * @throws \Exception When account is not created.
 */
function eaccounting_insert_account( $data, $wp_error = true ) {
	global $wpdb;
	// Ensure that we have data.
	if ( empty( $data ) ) {
		return false;
	}
	try {
		// The  id will be provided when updating an item.
		$data = wp_parse_args( $data, array( 'id' => null ) );

		// Retrieve the account.
		$item = new \EverAccounting\Models\Account( $data['id'] );

		// check if already account number exists for another user.
		$number           = ! empty( $data['number'] ) ? $data['number'] : $item->get_number();
		$existing_account = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}ea_accounts WHERE number='$number'" );

		if ( $existing_account ) {
			$existing_id = $existing_account->id;
		}
		if ( ! empty( $existing_id ) && absint( $existing_id ) !== $item->get_id() ) {
			throw new \Exception( __( 'Duplicate account number.', 'wp-ever-accounting' ) );
		}

		// Load new data.
		$item->set_props( $data );

		// Save the item.
		$item->save();

		return $item;
	} catch ( \Exception $e ) {
		return $wp_error ? new WP_Error( 'insert_account', $e->getMessage(), array( 'status' => $e->getCode() ) ) : 0;
	}
}

/**
 * Delete an account.
 *
 * @since 1.1.0
 *
 * @param int $account_id Account ID.
 *
 * @return bool
 */
function eaccounting_delete_account( $account_id ) {
	try {
		$account = new EverAccounting\Models\Account( $account_id );

		return $account->exists() ? $account->delete() : false;
	} catch ( \Exception $e ) {
		return false;
	}
}

/**
 * Get account items.
 *
 * @since 1.1.0
 *
 * @param array $args            {
 *                               Optional. Arguments to retrieve accounts.
 *
 * @type string $name            The name of the account .
 *
 * @type string $number          The number of account.
 *
 * @type string $currency_code   The currency_code for the account.
 *
 * @type double $opening_balance The opening balance of the account.
 *
 * @type string $bank_name       The bank name for the account.
 *
 * @type string $bank_phone      The phone number of the bank on which the account is opened.
 *
 * @type string $bank_address    The address of the bank.
 *
 * @type int    $enabled         The status of the account.
 *
 * @type int    $creator_id      The creator id for the account.
 *
 * @type string $date_created    The date when the account is created.
 *
 *
 * }
 *
 * @return array|int
 */
function eaccounting_get_accounts( $args = array() ) {
	global $wpdb;
	// Prepare args.
	$args = wp_parse_args(
		$args,
		array(
			'status'      => 'all',
			'include'     => '',
			'search'      => '',
			'balance'     => false,
			'fields'      => '*',
			'orderby'     => 'id',
			'order'       => 'ASC',
			'number'      => 20,
			'offset'      => 0,
			'paged'       => 1,
			'return'      => 'objects',
			'count_total' => false,
		)
	);

	$qv           = apply_filters( 'eaccounting_get_accounts_args', $args );
	$table        = \EverAccounting\Repositories\Accounts::TABLE;
	$columns      = \EverAccounting\Repositories\Accounts::get_columns();
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

	if ( ! empty( $qv['status'] ) && ! in_array( $qv['status'], array( 'all', 'any' ), true ) ) {
		$status = eaccounting_string_to_bool( $qv['status'] );
		$status = eaccounting_bool_to_number( $status );
		$where .= " AND $table.`enabled` = ('$status')";
	}

	$join = '';
	if ( true === $qv['balance'] && ! $qv['count_total'] ) {
		$sub_query = "
		SELECT account_id, SUM(CASE WHEN ea_transactions.type='income' then amount WHEN ea_transactions.type='expense' then - amount END) as total from
		{$wpdb->prefix}ea_transactions as ea_transactions LEFT JOIN {$wpdb->prefix}$table ea_accounts ON ea_accounts.id=ea_transactions.account_id GROUP BY account_id";
		$join     .= " LEFT JOIN ($sub_query) as calculated ON calculated.account_id = {$table}.id";
		$fields   .= " , ( {$table}.opening_balance + IFNULL( calculated.total, 0) ) as balance ";
	}

	// search.
	$search_cols = array( 'name', 'number', 'currency_code', 'bank_name', 'bank_phone', 'bank_address' );
	if ( ! empty( $qv['search'] ) ) {
		$searches = array();
		$where   .= ' AND (';
		foreach ( $search_cols as $col ) {
			$searches[] = $wpdb->prepare( $col . ' LIKE %s', '%' . $wpdb->esc_like( $qv['search'] ) . '%' );
		}
		$where .= implode( ' OR ', $searches );
		$where .= ')';
	}

	if ( ! empty( $qv['due_date'] ) && is_array( $qv['due_date'] ) ) {
		$date_created_query = new \WP_Date_Query( $qv['due_date'], "{$table}.due_date" );
		$where             .= $date_created_query->get_sql();
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
	$clauses     = compact( 'select', 'from', 'join', 'where', 'orderby', 'limit' );
	$cache_key   = 'query:' . md5( maybe_serialize( $qv ) ) . ':' . wp_cache_get_last_changed( 'ea_accounts' );
	$results     = wp_cache_get( $cache_key, 'ea_accounts' );
	if ( false === $results ) {
		if ( $count_total ) {
			$results = (int) $wpdb->get_var( "SELECT COUNT(id) $from $where" );
			wp_cache_set( $cache_key, $results, 'ea_accounts' );
		} else {
			$results = $wpdb->get_results( implode( ' ', $clauses ) );
			if ( in_array( $fields, array( 'all', '*' ), true ) ) {
				foreach ( $results as $key => $item ) {
					wp_cache_set( $item->id, $item, 'ea_accounts' );
					if ( true === $qv['balance'] ) {
						wp_cache_set( 'balance-' . $item->id, $item->balance, 'ea_accounts' );
					}
				}
			}
			wp_cache_set( $cache_key, $results, 'ea_accounts' );
		}
	}

	if ( 'objects' === $qv['return'] && true !== $qv['count_total'] ) {
		$results = array_map( 'eaccounting_get_account', $results );
	}

	return $results;
}

