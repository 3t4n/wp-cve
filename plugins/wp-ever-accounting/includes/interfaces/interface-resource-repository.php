<?php
/**
 * Interface Resource_Repository loader.
 *
 * @since       1.1.0
 * @subpackage  Interfaces
 * @package     EverAccounting\Includes
 */

namespace EverAccounting\Interfaces;

defined( 'ABSPATH' ) || exit;

interface Resource_Repository {
	/**
	 * Retrieves the list of columns for the database table.
	 *
	 * Sub-classes should define an array of columns here.
	 *
	 * @since 1.1.0
	 *
	 * @return array List of columns.
	 */
	public static function get_columns();

	/**
	 * Retrieves column defaults.
	 *
	 * Sub-classes can define default for any/all of columns defined in the get_columns() method.
	 *
	 * @since 1.1.0
	 *
	 * @return array All defined column defaults.
	 */
	public static function get_defaults();

	/**
	 * Retrieve any item for database.
	 *
	 * @since 1.1.0
	 *
	 * @param int $id Item ID.
	 *
	 * @return mixed
	 */
	public function get( $id );

	/**
	 * Insert item.
	 *
	 * @since 1.1.0
	 *
	 * @param array $data Item data.
	 *
	 * @return mixed
	 */
	public function insert( $data );

	/**
	 * Update item.
	 *
	 * @since 1.1.0
	 *
	 * @param int   $id   Item ID.
	 * @param array $data Item data.
	 *
	 * @return mixed
	 */
	public function update( $id, array $data);

	/**
	 * Duplicate item.
	 *
	 * @since 1.1.0
	 *
	 * @param int $id Item ID.
	 *
	 * @return mixed
	 */
	public function duplicate( $id );

	/**
	 * Delete's the entry.
	 *
	 * @since 1.1.0
	 *
	 * @param int $id Item ID.
	 *
	 * @return bool
	 */
	public function delete( $id );

	/**
	 * Retrieves items from the database.
	 *
	 * @since 1.1.0
	 *
	 * @param array $args Query arguments.
	 *
	 * @return array
	 */
	public function get_items( $args = array() );

	/**
	 * Count items.
	 *
	 * @since 1.1.0
	 *
	 * @param array $args Query arguments.
	 *
	 * @return mixed
	 */
	public function count( $args = array() );


	/**
	 * Truncate all entries.
	 *
	 * @since 1.1.0
	 *
	 * @return mixed
	 */
	public function truncate();

	/**
	 * Name of the table.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public function get_table();

	/**
	 * Primary key of the table.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public function get_primary_key();
}
