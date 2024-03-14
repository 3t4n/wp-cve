<?php
/**
 * EverAccounting Document Functions.
 *
 * All document related function of the plugin.
 *
 * @since   1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Bill;
use EverAccounting\Models\Invoice;
defined( 'ABSPATH' ) || exit;


/**
 * Main function for returning invoice.
 *
 * @since 1.1.0
 *
 * @param mixed $invoice Invoice ID or post object.
 *
 * @return EverAccounting\Models\Invoice|null
 */
function eaccounting_get_invoice( $invoice ) {
	if ( empty( $invoice ) ) {
		return null;
	}
	try {
		$result = new EverAccounting\Models\Invoice( $invoice );

		return $result->exists() ? $result : null;
	} catch ( \Exception $e ) {
		return null;
	}
}

/**
 *  Create new invoice programmatically.
 *  Returns a new invoice object on success.
 *
 * @since 1.1.0
 * @param  array $args   Invoice arguments.
 * @param bool  $wp_error Whether to return a WP_Error on failure.
 *
 * @return Invoice|false|int|WP_Error
 */
function eaccounting_insert_invoice( $args, $wp_error = true ) {
	// Ensure that we have data.
	if ( empty( $args ) ) {
		return false;
	}
	try {
		// The  id will be provided when updating an item.
		$args = wp_parse_args( $args, array( 'id' => null ) );

		// Retrieve the item.
		$item = new \EverAccounting\Models\Invoice( $args['id'] );

		// Load new data.
		$item->set_props( $args );

		// Save the item.
		$item->save();

		return $item;
	} catch ( \Exception $e ) {
		return $wp_error ? new WP_Error( 'insert_item', $e->getMessage(), array( 'status' => $e->getCode() ) ) : 0;
	}
}

/**
 * Delete an invoice.
 *
 * @since 1.1.0
 *
 * @param int $invoice_id Invoice ID.
 *
 * @return bool
 */
function eaccounting_delete_invoice( $invoice_id ) {
	try {
		$invoice = new EverAccounting\Models\Invoice( $invoice_id );

		return $invoice->exists() ? $invoice->delete() : false;
	} catch ( \Exception $e ) {
		return false;
	}
}

/**
 * Get invoices.
 *
 * @since 1.1.0
 *
 * @param array $args Query arguments.
 *
 * @return array|Invoice[]|int|
 */
function eaccounting_get_invoices( $args = array() ) {
	$args = array_merge( $args, array( 'type' => 'invoice' ) );
	if ( isset( $args['customer_id'] ) ) {
		$args['contact_id'] = $args['customer_id'];
		unset( $args['customer_id'] );
	}
	return eaccounting_get_documents( $args );
}


/**
 * Main function for returning bill.
 *
 * @since 1.1.0
 *
 * @param mixed $bill Bill ID or object.
 *
 * @return EverAccounting\Models\Bill|null
 */
function eaccounting_get_bill( $bill ) {
	if ( empty( $bill ) ) {
		return null;
	}
	try {
		$result = new EverAccounting\Models\Bill( $bill );

		return $result->exists() ? $result : null;
	} catch ( \Exception $e ) {
		return null;
	}
}

/**
 *  Create new bill programmatically.
 *  Returns a new bill object on success.
 *
 * @since 1.1.0
 * @param  array $args   Bill data.
 * @param bool  $wp_error Optional. Whether to return a WP_Error on failure.
 *
 * @return Bill|false|int|WP_Error
 */
function eaccounting_insert_bill( $args, $wp_error = true ) {
	// Ensure that we have data.
	if ( empty( $args ) ) {
		return false;
	}
	try {
		// The  id will be provided when updating an item.
		$args = wp_parse_args( $args, array( 'id' => null ) );

		// Retrieve the item.
		$item = new \EverAccounting\Models\Bill( $args['id'] );

		// Load new data.
		$item->set_props( $args );

		// Save the item.
		$item->save();

		return $item;
	} catch ( \Exception $e ) {
		return $wp_error ? new WP_Error( 'insert_item', $e->getMessage(), array( 'status' => $e->getCode() ) ) : 0;
	}
}

/**
 * Delete an bill.
 *
 * @since 1.1.0
 *
 * @param int $bill_id Bill ID.
 *
 * @return bool
 */
function eaccounting_delete_bill( $bill_id ) {
	try {
		$bill = new EverAccounting\Models\Bill( $bill_id );

		return $bill->exists() ? $bill->delete() : false;
	} catch ( \Exception $e ) {
		return false;
	}
}

/**
 * Get bills.
 *
 * @since 1.1.0
 *
 * @param array $args Query arguments.
 *
 * @return array|Invoice[]|int|
 */
function eaccounting_get_bills( $args = array() ) {
	$args = array_merge( $args, array( 'type' => 'bill' ) );
	if ( isset( $args['vendor_id'] ) ) {
		$args['contact_id'] = $args['vendor_id'];
		unset( $args['vendor_id'] );
	}
	return eaccounting_get_documents( $args );
}

/**
 * Get document items.
 *
 * @since 1.1.0
 *
 * @param array $args Query arguments.
 *
 * @return array|Bill[]|Invoice[]|null|int
 */
function eaccounting_get_documents( $args = array() ) {
	// Prepare args.
	$args = wp_parse_args(
		$args,
		array(
			'include'     => '',
			'search'      => '',
			'fields'      => array(),
			'orderby'     => 'issue_date',
			'order'       => 'ASC',
			'number'      => 20,
			'offset'      => 0,
			'paged'       => 1,
			'return'      => 'objects',
			'count_total' => false,
			'customer_id' => '',
		)
	);
	global $wpdb;
	$qv           = apply_filters( 'eaccounting_get_documents_args', $args );
	$table        = \EverAccounting\Repositories\Documents::TABLE;
	$columns      = \EverAccounting\Repositories\Documents::get_columns();
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
	$search_cols = array( 'document_number', 'order_number', 'address' );
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

	if ( ! empty( $qv['issue_date'] ) && is_array( $qv['issue_date'] ) ) {
		$date_created_query = new \WP_Date_Query( $qv['issue_date'], "{$table}.issue_date" );
		$where             .= $date_created_query->get_sql();
	}

	if ( ! empty( $qv['due_date'] ) && is_array( $qv['due_date'] ) ) {
		$date_created_query = new \WP_Date_Query( $qv['due_date'], "{$table}.due_date" );
		$where             .= $date_created_query->get_sql();
	}

	if ( ! empty( $qv['date_created'] ) && is_array( $qv['date_created'] ) ) {
		$date_created_query = new \WP_Date_Query( $qv['date_created'], "{$table}.date_created" );
		$where             .= $date_created_query->get_sql();
	}

	if ( ! empty( $qv['payment_date'] ) && is_array( $qv['payment_date'] ) ) {
		$date_created_query = new \WP_Date_Query( $qv['payment_date'], "{$table}.payment_date" );
		$where             .= $date_created_query->get_sql();
	}
	if ( ! empty( $qv['creator_id'] ) ) {
		$creator_id = implode( ',', wp_parse_id_list( $qv['creator_id'] ) );
		$where     .= " AND $table.`creator_id` IN ($creator_id)";
	}
	if ( ! empty( $qv['customer_id'] ) ) {
		$customer_id = implode( ',', wp_parse_id_list( $qv['customer_id'] ) );
		$where      .= " AND $table.`contact_id` IN ($customer_id)";
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
	$clauses     = compact( 'select', 'from', 'where', 'orderby', 'limit' );
	$cache_key   = 'query:' . md5( maybe_serialize( $qv ) ) . ':' . wp_cache_get_last_changed( 'ea_documents' );
	$results     = wp_cache_get( $cache_key, 'ea_documents' );
	if ( false === $results ) {
		if ( $count_total ) {
			$results = (int) $wpdb->get_var( "SELECT COUNT(id) $from $where" );
			wp_cache_set( $cache_key, $results, 'ea_documents' );
		} else {
			$results = $wpdb->get_results( implode( ' ', $clauses ) );
			if ( in_array( $fields, array( 'all', '*' ), true ) ) {
				foreach ( $results as $key => $item ) {
					wp_cache_set( $item->id, $item, 'ea_documents' );
				}
			}
			wp_cache_set( $cache_key, $results, 'ea_documents' );
		}
	}

	if ( 'objects' === $qv['return'] && true !== $qv['count_total'] ) {
		$results = array_map(
			function ( $item ) {
				switch ( $item->type ) {
					case 'invoice':
						$document = new Invoice();
						$document->set_props( $item );
						$document->set_object_read( true );
						break;
					case 'bill':
						$document = new \EverAccounting\Models\Bill();
						$document->set_props( $item );
						$document->set_object_read( true );
						break;
					default:
						$document = apply_filters( 'eaccounting_document_object_' . $item->type, $item );
				}

				return $document;
			},
			$results
		);
	}

	return $results;
}


/**
 * Get bill statuses.
 *
 * @return mixed|void
 */
function eaccounting_get_bill_statuses() {
	$statuses = array(
		'draft'     => esc_html__( 'Draft', 'wp-ever-accounting' ),
		'received'  => esc_html__( 'Received', 'wp-ever-accounting' ),
		'partial'   => esc_html__( 'Partial', 'wp-ever-accounting' ),
		'paid'      => esc_html__( 'Paid', 'wp-ever-accounting' ),
		'overdue'   => esc_html__( 'Overdue', 'wp-ever-accounting' ),
		'cancelled' => esc_html__( 'Cancelled', 'wp-ever-accounting' ),
	);

	return apply_filters( 'eaccounting_bill_statuses', $statuses );
}

/**
 * Get invoice statuses.
 *
 * @return mixed|void
 */
function eaccounting_get_invoice_statuses() {
	$statuses = array(
		'draft'     => esc_html__( 'Draft', 'wp-ever-accounting' ),
		'pending'   => esc_html__( 'Pending', 'wp-ever-accounting' ),
		'partial'   => esc_html__( 'Partial', 'wp-ever-accounting' ),
		'paid'      => esc_html__( 'Paid', 'wp-ever-accounting' ),
		'overdue'   => esc_html__( 'Overdue', 'wp-ever-accounting' ),
		'cancelled' => esc_html__( 'Cancelled', 'wp-ever-accounting' ),
		'refunded'  => esc_html__( 'Refunded', 'wp-ever-accounting' ),
	);

	return apply_filters( 'eaccounting_invoice_statuses', $statuses );
}
