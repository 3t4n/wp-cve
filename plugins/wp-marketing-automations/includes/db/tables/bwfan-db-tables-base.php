<?php

abstract class BWFAN_DB_Tables_Base {
	public $table_name = '';
	public $db_errors = '';
	public $max_index_length = 191;

	public $collation = null;

	/**
	 * Checking table exists or not
	 *
	 * @return bool
	 */
	public function is_exists() {
		global $wpdb;

		return ! empty( $wpdb->query( "SHOW TABLES LIKE '{$wpdb->prefix}$this->table_name'" ) );
	}

	/**
	 * Check missing columns and return missing ones only
	 *
	 * @return array
	 */
	public function check_missing_columns() {
		global $wpdb;

		/** Get defined columns */
		$columns = $this->get_columns();
		/** Get columns from db */
		$db_columns = $wpdb->get_results( "DESCRIBE {$wpdb->prefix}$this->table_name", ARRAY_A );

		$result = array_diff( $columns, array_column( $db_columns, 'Field' ) );
		sort( $result );

		return $result;
	}

	public function get_columns() {
		return [];
	}

	/**
	 * Create table
	 *
	 * @return void
	 */
	public function create_table() {
		global $wpdb;
		$sql = $this->get_create_table_query();
		if ( empty( $sql ) ) {
			return;
		}

		dbDelta( $sql );

		if ( ! empty( $wpdb->last_error ) ) {
			$this->db_errors = $this->table_name . ' create table method triggered an error - ' . $wpdb->last_error;
		}
	}

	public function get_create_table_query() {
		return '';
	}

	public function get_collation() {
		if ( ! is_null( $this->collation ) ) {
			return $this->collation;
		}

		global $wpdb;
		$collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		$this->collation = $collate;

		return $collate;
	}
}
