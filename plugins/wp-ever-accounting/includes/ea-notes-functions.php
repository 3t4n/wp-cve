<?php
/**
 * EverAccounting Notes Functions.
 *
 * All notes related function of the plugin.
 *
 * @since   1.1.0
 * @package EverAccounting
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main function for returning note.
 *
 * @param mixed $item Note item.
 *
 * @return EverAccounting\Models\Note|null
 * @since 1.1.0
 */
function eaccounting_get_note( $item ) {
	if ( empty( $item ) ) {
		return null;
	}
	try {
		$result = new EverAccounting\Models\Note( $item );

		return $result->exists() ? $result : null;
	} catch ( \Exception $e ) {
		return null;
	}
}

/**
 * Insert note.
 *
 * @param  array $args Note arguments.
 * @param bool  $wp_error Return WP_Error on failure.
 * @since 1.1.0
 *
 * @return \EverAccounting\Models\Note|false|int|WP_Error
 */
function eaccounting_insert_note( $args, $wp_error = true ) {
	// Ensure that we have data.
	if ( empty( $args ) ) {
		return false;
	}
	try {
		// The  id will be provided when updating an item.
		$args = wp_parse_args( $args, array( 'id' => null ) );

		// Retrieve the item.
		$item = new \EverAccounting\Models\Note( $args['id'] );

		// Load new data.
		$item->set_props( $args );

		// Save the item.
		$item->save();

		return $item;
	} catch ( \Exception $e ) {
		return $wp_error ? new WP_Error( $e->getMessage(), array( 'status' => $e->getCode() ) ) : 0;
	}
}

/**
 * Delete an item.
 *
 * @param int $note_id Item ID.
 *
 * @return bool
 * @since 1.1.0
 */
function eaccounting_delete_note( $note_id ) {
	try {
		$item = new EverAccounting\Models\Note( $note_id );

		return $item->exists() ? $item->delete() : false;
	} catch ( \Exception $e ) {
		return false;
	}
}

/**
 * Get notes.
 *
 * @param array $args Query arguments.
 * @since 1.1.0
 *
 * @return array|void
 */
function eaccounting_get_notes( $args = array() ) {
	// Prepare args.
	$args = wp_parse_args(
		$args,
		array(
			'include'     => '',
			'parent_id'   => '',
			'type'        => '',
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
	$qv           = apply_filters( 'eaccounting_get_documents_args', $args );
	$table        = \EverAccounting\Repositories\Notes::TABLE;
	$columns      = \EverAccounting\Repositories\Notes::get_columns();
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
	$search_cols = array( 'note', 'extra' );
	if ( ! empty( $qv['search'] ) ) {
		$searches = array();
		$where    = ' AND (';
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

	if ( ! empty( $qv['parent_id'] ) ) {
		$parent_id = implode( ',', wp_parse_id_list( $qv['parent_id'] ) );
		$where    .= " AND $table.`parent_id` IN ($parent_id)";
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
	$cache_key   = 'query:' . md5( maybe_serialize( $qv ) ) . ':' . wp_cache_get_last_changed( 'ea_notes' );
	$results     = wp_cache_get( $cache_key, 'ea_notes' );
	$clauses     = compact( 'select', 'from', 'where', 'orderby', 'limit' );

	if ( false === $results ) {
		if ( $count_total ) {
			$results = (int) $wpdb->get_var( "SELECT COUNT(id) $from $where" );
			wp_cache_set( $cache_key, $results, 'ea_notes' );
		} else {
			$results = $wpdb->get_results( implode( ' ', $clauses ) );
			if ( in_array( $fields, array( 'all', '*' ), true ) ) {
				foreach ( $results as $key => $item ) {
					wp_cache_set( $item->id, $item, 'ea_notes' );
				}
			}
			wp_cache_set( $cache_key, $results, 'ea_notes' );
		}
	}

	if ( 'objects' === $qv['return'] && true !== $qv['count_total'] ) {
		$results = array_map( 'eaccounting_get_note', $results );
	}

	return $results;
}
