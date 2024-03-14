<?php
/**
 * EverAccounting Item Functions.
 *
 * All item related function of the plugin.
 *
 * @since   1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Item;

defined( 'ABSPATH' ) || exit;

/**
 * Main function for returning item.
 *
 * @param Item $item Item object.
 *
 * @return EverAccounting\Models\Item|null
 * @since 1.1.0
 */
function eaccounting_get_item( $item ) {
	if ( empty( $item ) ) {
		return null;
	}
	try {
		$result = new EverAccounting\Models\Item( $item );

		return $result->exists() ? $result : null;
	} catch ( \Exception $e ) {
		return null;
	}
}


/**
 * Get item by sku.
 *
 * @since 1.1.0
 *
 * @param string $sku Item sku.
 *
 * @return Item
 */
function eaccounting_get_item_by_sku( $sku ) {
	global $wpdb;
	$sku = eaccounting_clean( $sku );
	if ( empty( $sku ) ) {
		return null;
	}
	$cache_key = "item-sku-$sku";
	$item      = wp_cache_get( $cache_key, 'ea_items' );
	if ( false === $item ) {
		$item = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ea_items where `sku`=%s", eaccounting_clean( $sku ) ) );
		wp_cache_set( $cache_key, $item, 'ea_items' );
	}
	if ( $item ) {
		wp_cache_set( $item->id, $item, 'ea_items' );
		return eaccounting_get_item( $item );
	}

	return null;
}

/**
 *  Create new item programmatically.
 *
 *  Returns a new item object on success.
 *
 * @param array $args {
 *                              An array of elements that make up an invoice to update or insert.
 *
 * @type int $id The item ID. If equal to something other than 0,
 *                                         the item with that id will be updated. Default 0.
 * @type string $name The name of the item.
 * @type string $sku The sku of the item.
 * @type int $image_id The image_id for the item.
 * @type string $description The description of the item.
 * @type double $sale_price The sale_price of the item.
 * @type double $purchase_price The purchase_price for the item.
 * @type int $quantity The quantity of the item.
 * @type int $category_id The category_id of the item.
 * @type int $tax_id The tax_id of the item.
 * @type int $enabled The enabled of the item.
 * }
 * @param bool  $wp_error Optional. Whether to return a WP_Error on failure. Default false.
 *
 * @return EverAccounting\Models\Item|WP_Error|bool
 * @since 1.1.0
 */
function eaccounting_insert_item( $args, $wp_error = true ) {
	// Ensure that we have data.
	if ( empty( $args ) ) {
		return false;
	}
	try {
		// The  id will be provided when updating an item.
		$args = wp_parse_args( $args, array( 'id' => null ) );

		// Retrieve the item.
		$item = new Item( $args['id'] );

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
 * Delete an item.
 *
 * @param int $item_id Item ID.
 *
 * @return bool
 * @since 1.1.0
 */
function eaccounting_delete_item( $item_id ) {
	try {
		$item = new EverAccounting\Models\Item( $item_id );

		return $item->exists() ? $item->delete() : false;
	} catch ( \Exception $e ) {
		return false;
	}
}

/**
 * Get items.
 *
 * @param array $args { .
 *    Optional. Arguments to retrieve items.
 * @type string $name The name of the item.
 * @type string $sku The sku of the item.
 * @type int $image_id The image_id for the item.
 * @type string $description The description of the item.
 * @type double $sale_price The sale_price of the item.
 * @type double $purchase_price The purchase_price for the item.
 * @type int $quantity The quantity of the item.
 * @type int $category_id The category_id of the item.
 * @type int $tax_id The tax_id of the item.
 * @type int $enabled The enabled of the item.
 * }
 *
 * @return array|int
 * @since 1.1.0
 */
function eaccounting_get_items( $args = array() ) {
	// Prepare args.
	$args = wp_parse_args(
		$args,
		array(
			'status'      => 'all',
			'include'     => '',
			'search'      => '',
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
	global $wpdb;
	$qv           = apply_filters( 'eaccounting_get_items_args', $args );
	$table        = \EverAccounting\Repositories\Items::TABLE;
	$columns      = \EverAccounting\Repositories\Items::get_columns();
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
	$search_cols = array( 'name', 'sku', 'description' );
	if ( ! empty( $qv['search'] ) ) {
		$searches = array();
		$where   .= ' AND (';
		foreach ( $search_cols as $col ) {
			$searches[] = $wpdb->prepare( $col . ' LIKE %s', '%' . $wpdb->esc_like( $qv['search'] ) . '%' );
		}
		$where .= implode( ' OR ', $searches );
		$where .= ')';
	}

	if ( ! empty( $qv['status'] ) && ! in_array( $qv['status'], array( 'all', 'any' ), true ) ) {
		$status = eaccounting_string_to_bool( $qv['status'] );
		$status = eaccounting_bool_to_number( $status );
		$where .= " AND $table.`enabled` = ('$status')";
	}

	if ( ! empty( $qv['date_created'] ) && is_array( $qv['date_created'] ) ) {
		$date_created_query = new \WP_Date_Query( $qv['date_created'], "{$table}.date_created" );
		$where             .= $date_created_query->get_sql();
	}

	if ( ! empty( $qv['creator_id'] ) ) {
		$creator_id = implode( ',', wp_parse_id_list( $qv['creator_id'] ) );
		$where     .= " AND $table.`creator_id` IN ($creator_id)";
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
	$cache_key   = 'query:' . md5( maybe_serialize( $qv ) ) . ':' . wp_cache_get_last_changed( 'ea_items' );
	$results     = wp_cache_get( $cache_key, 'ea_items' );
	$clauses     = compact( 'select', 'from', 'where', 'orderby', 'limit' );
	if ( false === $results ) {
		if ( $count_total ) {
			$results = (int) $wpdb->get_var( "SELECT COUNT(id) $from $where" );
			wp_cache_set( $cache_key, $results, 'ea_items' );
		} else {
			$results = $wpdb->get_results( implode( ' ', $clauses ) );
			if ( in_array( $fields, array( 'all', '*' ), true ) ) {
				foreach ( $results as $key => $item ) {
					if ( ! empty( $item->sku ) ) {
						wp_cache_set( 'item-sku-' . $item->sku, $item, 'ea_items' );
					}
					wp_cache_set( $item->id, $item, 'ea_items' );
				}
			}
			wp_cache_set( $cache_key, $results, 'ea_items' );
		}
	}

	if ( 'objects' === $qv['return'] && true !== $qv['count_total'] ) {
		$results = array_map( 'eaccounting_get_item', $results );
	}

	return $results;
}
