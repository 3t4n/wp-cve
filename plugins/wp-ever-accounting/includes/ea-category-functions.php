<?php
/**
 * EverAccounting category Functions.
 *
 * All category related function of the plugin.
 *
 * @since   1.1.0
 * @package EverAccounting
 */

defined( 'ABSPATH' ) || exit();

/**
 * Get all the available type of category the plugin support.
 *
 * @return array
 * @since 1.1.0
 */
function eaccounting_get_category_types() {
	$types = array(
		'expense' => esc_html__( 'Expense', 'wp-ever-accounting' ),
		'income'  => esc_html__( 'Income', 'wp-ever-accounting' ),
		'other'   => esc_html__( 'Other', 'wp-ever-accounting' ),
		'item'    => esc_html__( 'Item', 'wp-ever-accounting' ),
	);

	return apply_filters( 'eaccounting_category_types', $types );
}

/**
 * Get the category type label of a specific type.
 *
 * @param string $type Category type.
 *
 * @return string
 * @since 1.1.0
 */
function eaccounting_get_category_type( $type ) {
	$types = eaccounting_get_category_types();

	return array_key_exists( $type, $types ) ? $types[ $type ] : null;
}

/**
 * Get category.
 *
 * @param mixed $category Category ID or object.
 *
 * @return null|EverAccounting\Models\Category
 * @since 1.1.0
 */
function eaccounting_get_category( $category ) {
	if ( empty( $category ) ) {
		return null;
	}
	try {
		$result = new EverAccounting\Models\Category( $category );

		return $result->exists() ? $result : null;
	} catch ( \Exception $e ) {
		return null;
	}
}

/**
 * Get category by name.
 *
 * @param string $name Category name.
 * @param string $type Category type.
 *
 * @return \EverAccounting\Models\Category|null
 * @since 1.1.0
 */
function eaccounting_get_category_by_name( $name, $type ) {
	global $wpdb;
	$cache_key = "$name-$type";
	$category  = wp_cache_get( $cache_key, 'ea_categories' );
	if ( false === $category ) {
		$category = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ea_categories where `name`=%s AND `type`=%s", eaccounting_clean( $name ), eaccounting_clean( $type ) ) );
		wp_cache_set( $cache_key, $category, 'ea_categories' );
	}
	if ( $category ) {
		wp_cache_set( $category->id, $category, 'ea_categories' );

		return eaccounting_get_category( $category );
	}

	return null;
}

/**
 * Insert a category.
 *
 * @param array $data {
 *                            An array of elements that make up an category to update or insert.
 *
 * @type int $id The category ID. If equal to something other than 0, the category with that ID will be updated. Default 0.
 *
 * @type string $name Unique name of the category.
 *
 * @type string $type Category type.
 *
 * @type string $color Color of the category.
 *
 * @type int $enabled The status of the category. Default 1.
 *
 * @type string $date_created The date when the category is created. Default is current current time.
 *
 * }
 * @param bool  $wp_error Whether to return false or WP_Error on failure.
 *
 * @return int|\WP_Error|\EverAccounting\Models\Category|bool The value 0 or WP_Error on failure. The Category object on success.
 * @since 1.1.0
 */
function eaccounting_insert_category( $data = array(), $wp_error = true ) {
	// Ensure that we have data.
	if ( empty( $data ) ) {
		return false;
	}
	try {
		// The  id will be provided when updating an item.
		$data = wp_parse_args( $data, array( 'id' => null ) );

		// Retrieve the category.
		$item = new \EverAccounting\Models\Category( $data['id'] );

		// Load new data.
		$item->set_props( $data );

		// If no color set.
		if ( empty( $item->get_color() ) ) {
			$item->set_color( eaccounting_get_random_color() );
		}

		$item->save();

		return $item;
	} catch ( \Exception $e ) {
		return $wp_error ? new WP_Error( 'insert_category', $e->getMessage() ) : 0;
	}
}

/**
 * Delete a category.
 *
 * @param int $category_id Category ID.
 *
 * @return bool
 * @since 1.1.0
 */
function eaccounting_delete_category( $category_id ) {
	try {
		$category = new EverAccounting\Models\Category( $category_id );

		return $category->exists() ? $category->delete() : false;
	} catch ( \Exception $e ) {
		return false;
	}
}

/**
 * Get category items.
 *
 * @param array $args Query arguments.
 *
 * @return int|array|null
 * @since 1.1.0
 */
function eaccounting_get_categories( $args = array() ) {
	global $wpdb;
	// Prepare args.
	$args = wp_parse_args(
		$args,
		array(
			'status'      => 'all',
			'type'        => '',
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

	$qv           = apply_filters( 'eaccounting_get_categories_args', $args );
	$table        = \EverAccounting\Repositories\Categories::TABLE;
	$columns      = \EverAccounting\Repositories\Categories::get_columns();
	$qv['fields'] = wp_parse_list( $qv['fields'] );
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

	if ( ! empty( $qv['type'] ) ) {
		$types  = implode( "','", wp_parse_list( $qv['type'] ) );
		$where .= " AND $table.`type` IN ('$types')";
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

	$search_cols = array( 'name', 'type' );
	if ( ! empty( $qv['search'] ) ) {
		$searches = array();
		$where   .= ' AND (';
		foreach ( $search_cols as $col ) {
			$searches[] = $wpdb->prepare( $col . ' LIKE %s', '%' . $wpdb->esc_like( $qv['search'] ) . '%' );
		}
		$where .= implode( ' OR ', $searches );
		$where .= ')';
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
	$cache_key   = 'query:' . md5( maybe_serialize( $qv ) ) . ':' . wp_cache_get_last_changed( 'ea_categories' );
	$results     = wp_cache_get( $cache_key, 'ea_categories' );
	if ( false === $results ) {
		if ( $count_total ) {
			$results = (int) $wpdb->get_var( "SELECT COUNT(id) $from $where" );
			wp_cache_set( $cache_key, $results, 'ea_categories' );
		} else {
			$results = $wpdb->get_results( implode( ' ', $clauses ) );
			if ( in_array( $fields, array( 'all', '*' ), true ) ) {
				foreach ( $results as $key => $item ) {
					wp_cache_set( $item->id, $item, 'ea_categories' );
					wp_cache_set( $item->name . '-' . $item->type, $item, 'ea_categories' );
				}
			}
			wp_cache_set( $cache_key, $results, 'ea_categories' );
		}
	}

	if ( 'objects' === $qv['return'] && true !== $qv['count_total'] ) {
		$results = array_map( 'eaccounting_get_category', $results );
	}

	return $results;
}
