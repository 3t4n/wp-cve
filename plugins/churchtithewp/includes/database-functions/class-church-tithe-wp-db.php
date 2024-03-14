<?php
/**
 * Church Tithe WP DB base class
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/Church Tithe WP DB
 * @copyright   Copyright (c) 2018, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Church_Tithe_WP_DB Class, which creates custom tables.
 *
 * @since 1.0.0
 */
abstract class Church_Tithe_WP_DB {

	/**
	 * The name of our database table
	 *
	 * @since   1.0.0
	 * @var    string
	 */
	public $table_name;

	/**
	 * The version of our database table
	 *
	 * @since   1.0.0
	 * @var    string
	 */
	public $version;

	/**
	 * The name of the primary column
	 *
	 * @since   1.0.0
	 * @var    string
	 */
	public $primary_key;

	/**
	 * Get things started
	 *
	 * @since   1.0.0
	 */
	public function __construct() {}

	/**
	 * Whitelist of columns
	 *
	 * @since   1.0.0
	 * @return  array
	 */
	public function get_columns() {
		return array();
	}

	/**
	 * Default column values
	 *
	 * @since   1.0.0
	 * @return  array
	 */
	public function get_column_defaults() {
		return array();
	}

	/**
	 * Retrieve a row by the primary key
	 *
	 * @since   1.0.0
	 * @param   int $row_id The id of the row being fetched.
	 * @return  object
	 */
	public function get( $row_id ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id ) );
	}

	/**
	 * Retrieve a row by a specific column / value
	 *
	 * @since   1.0.0
	 * @param   string $column The name of the column being fetched.
	 * @param   int    $row_id The id of the column being fetched.
	 * @return  object
	 */
	public function get_by( $column, $row_id ) {
		global $wpdb;
		$column = esc_sql( $column );
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE $column = %s LIMIT 1;", $row_id ) );
	}

	/**
	 * Retrieve a specific column's value by the primary key
	 *
	 * @since   1.0.0
	 * @param   string $column The name of the column being fetched.
	 * @param   int    $row_id The id of the column being fetched.
	 * @return  string
	 */
	public function get_column( $column, $row_id ) {
		global $wpdb;
		$column = esc_sql( $column );
		return $wpdb->get_var( $wpdb->prepare( "SELECT $column FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id ) );
	}

	/**
	 * Retrieve a specific column's value by the the specified column / value
	 *
	 * @since   1.0.0
	 * @param   string $column The name of the column being fetched.
	 * @param   string $column_where The where statement used for this mysql query.
	 * @param   string $column_value The value we are searching for.
	 * @return  string
	 */
	public function get_column_by( $column, $column_where, $column_value ) {
		global $wpdb;
		$column_where = esc_sql( $column_where );
		$column       = esc_sql( $column );
		return $wpdb->get_var( $wpdb->prepare( "SELECT $column FROM $this->table_name WHERE $column_where = %s LIMIT 1;", $column_value ) );
	}

	/**
	 * Insert a new row
	 *
	 * @since   1.0.0
	 * @param   array $data The data being input into the table.
	 * @return  int
	 */
	public function insert( $data ) {
		global $wpdb;

		// Set default values
		$data = wp_parse_args( $data, $this->get_column_defaults() );

		// Initialise column format array.
		$column_formats = $this->get_columns();

		// Force fields to lower case.
		$data = array_change_key_case( $data );

		// White list columns.
		$data = array_intersect_key( $data, $column_formats );

		// Reorder $column_formats to match the order of columns given in $data.
		$data_keys      = array_keys( $data );
		$column_formats = array_merge( array_flip( $data_keys ), $column_formats );

		$wpdb->insert( $this->table_name, $data, $column_formats );
		$wpdb_insert_id = $wpdb->insert_id;

		return $wpdb_insert_id;
	}

	/**
	 * Update a row
	 *
	 * @since   1.0.0
	 * @param   int    $row_id The id of the row being updated.
	 * @param   array  $data The data that will be updated in this row.
	 * @param   string $where The where clause to use for this update.
	 * @return  bool
	 */
	public function update( $row_id, $data = array(), $where = '' ) {

		global $wpdb;

		// Row ID must be positive integer.
		$row_id = absint( $row_id );

		if ( empty( $row_id ) ) {
			return false;
		}

		if ( empty( $where ) ) {
			$where = $this->primary_key;
		}

		// Initialise column format array.
		$column_formats = $this->get_columns();

		// Force fields to lower case.
		$data = array_change_key_case( $data );

		// White list columns.
		$data = array_intersect_key( $data, $column_formats );

		// Reorder $column_formats to match the order of columns given in $data.
		$data_keys      = array_keys( $data );
		$column_formats = array_merge( array_flip( $data_keys ), $column_formats );

		if ( false === $wpdb->update( $this->table_name, $data, array( $where => $row_id ), $column_formats ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Delete a row identified by the primary key
	 *
	 * @since   1.0.0
	 * @param   int $row_id The id of the row being deleted.
	 * @return  bool
	 */
	public function delete( $row_id = 0 ) {

		global $wpdb;

		// Row ID must be positive integer.
		$row_id = absint( $row_id );

		if ( empty( $row_id ) ) {
			return false;
		}

		if ( false === $wpdb->query( $wpdb->prepare( "DELETE FROM $this->table_name WHERE $this->primary_key = %d", $row_id ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if the given table exists
	 *
	 * @since  1.0
	 * @param  string $table The table name.
	 * @return bool          If the table name exists
	 */
	public function table_exists( $table ) {
		global $wpdb;
		$table = sanitize_text_field( $table );

		return $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE '%s'", $table ) ) === $table;
	}

	/**
	 * Check if the table was ever installed
	 *
	 * @since  1.0
	 * @return bool Returns if the customers table was installed and upgrade routine run
	 */
	public function installed() {
		return $this->table_exists( $this->table_name );
	}

}
