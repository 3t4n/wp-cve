<?php
/**
 * Elements Model.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Model_Elements extends Base_Model {

	/**
	 * @var null
	 */
	public static $instance = null;

	/**
	 * @return null|Model_Elements
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
		$result = $this->db->get_results( "SELECT * FROM `" . $this->db->prefix . $this->table_elements . "` WHERE `deleted_at` IS NULL" );

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
				"SELECT * FROM `" . $this->db->prefix . $this->table_elements . "` WHERE `uuid` = %s",
				$uuid
			)
		);

		return $result;
	}

	/**
	 * @param $element
	 */
	public function createOrUpdate( $element ) {
		$element = $this->clearTrash( $element );

		$this->db->replace(
			$this->db->prefix . $this->table_elements,
			[
				'uuid'       => $element->uuid,
				'properties' => json_encode( $element )
			]
		);
	}
}
