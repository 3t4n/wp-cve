<?php
/**
 * Columns Model.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Model_Columns extends Base_Model {

	/**
	 * @var null
	 */
	public static $instance = null;

	/**
	 * @return null|Model_Columns
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @return array|null|object
	 */
	public function getAll() {
		$result = $this->db->get_results( "SELECT * FROM `" . $this->db->prefix . $this->table_columns . "` WHERE `deleted_at` IS NULL" );

		return $result;
	}

	/**
	 * @param $uuid
	 *
	 * @return array|null|object|void
	 */
	public function get( $uuid ) {
		$result = $this->db->get_row(
			$this->db->prepare(
				"SELECT * FROM `" . $this->db->prefix . $this->table_columns . "` WHERE `deleted_at` IS NULL AND `uuid` = %s",
				$uuid
			)
		);

		return $result;
	}

	/**
	 * @param $column
	 */
	public function createOrUpdate( $column ) {
		$column = $this->clearTrash( $column );

		$this->db->replace(
			$this->db->prefix . $this->table_columns,
			[
				'uuid'       => $column->uuid,
				'properties' => json_encode( $column )
			]
		);
	}
}
