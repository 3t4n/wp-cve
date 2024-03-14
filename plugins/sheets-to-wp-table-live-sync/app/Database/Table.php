<?php
/**
 * Managing database operations for tables.
 *
 * @since 3.0.0
 * @package SWPTLS
 */

namespace SWPTLS\Database;

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Manages plugin database operations.
 *
 * @since 3.0.0
 */
class Table {

	/**
	 * Fetch table with specific ID.
	 *
	 * @param  int $id The table id.
	 * @return mixed
	 */
	public function get( int $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'gswpts_tables';

		$result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id=%d", absint( $id ) ), ARRAY_A ); // phpcs:ignore

		return ! is_null( $result ) ? $result : null;
	}

	/**
	 * Insert table into the db.
	 *
	 * @param array $data The data to save.
	 * @return int|false
	 */
	public function insert( array $data ) {
		global $wpdb;

		$table  = $wpdb->prefix . 'gswpts_tables';
		$format = [ '%s', '%s', '%s', '%s', '%s' ];

		$wpdb->insert( $table, $data, $format );
		return $wpdb->insert_id;
	}

	/**
	 * Update table with specific ID.
	 *
	 * @param int   $id The table id.
	 * @param array $data The data to update.
	 */
	public function update( int $id, array $data ) {
		global $wpdb;
		$table = $wpdb->prefix . 'gswpts_tables';

		$where  = [ 'id' => $id ];
		$format = [ '%s', '%s', '%s', '%s' ];

		$where_format = [ '%d' ];

		return $wpdb->update( $table, $data, $where, $format, $where_format );
	}

	/**
	 * Delete table data from the DB.
	 *
	 * @param int $id  The table id to delete.
	 * @return int|false
	 */
	public function delete( int $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'gswpts_tables';

		return $wpdb->delete( $table, [ 'id' => $id ], [ '%d' ] );
	}

	/**
	 * Fetch all the saved tables
	 *
	 * @return mixed
	 */
	public function get_all() {
		global $wpdb;

		$table  = $wpdb->prefix . 'gswpts_tables';
		$query  = "SELECT * FROM $table";
		$result = $wpdb->get_results( $query ); // phpcs:ignore

		return $result;
	}

	/**
	 * Checks for sheet duplication.
	 *
	 * @param string $url The sheet url.
	 * @return boolean
	 */
	public function has( string $url ): bool {
		global $wpdb;

		$result = $wpdb->get_row(
			$wpdb->prepare( "SELECT * from {$wpdb->prefix}gswpts_tables WHERE `source_url` LIKE %s", $url )
		);

		return ! is_null( $result );
	}
}
