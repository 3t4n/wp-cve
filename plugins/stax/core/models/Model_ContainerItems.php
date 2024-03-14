<?php
/**
 * ContainerItems Model.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Model_ContainerItems extends Base_Model {

	/**
	 * @var null
	 */
	public static $instance = null;

	/**
	 * @return null|Model_ContainerItems
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @param $uuid
	 * @param $viewport
	 *
	 * @return array|null|object
	 */
	public function getByContainerUuid( $uuid, $viewport = null ) {
		if ( $viewport ) {
			$result = $this->db->get_results(
				$this->db->prepare(
					"SELECT * FROM `" . $this->db->prefix . $this->table_container_items . "` WHERE `container_uuid` = %s AND `viewport` = %s ORDER BY `position`",
					$uuid,
					$viewport
				)
			);
		} else {
			$result = $this->db->get_results(
				$this->db->prepare(
					"SELECT * FROM `" . $this->db->prefix . $this->table_container_items . "` WHERE `container_uuid` = %s ORDER BY `position`",
					$uuid
				)
			);
		}


		return $result;
	}

	/**
	 * @param $uuid
	 *
	 * @return array|null|object
	 */
	public function getByColumnUuid( $uuid ) {
		$result = $this->db->get_results(
			$this->db->prepare(
				"SELECT * FROM `" . $this->db->prefix . $this->table_container_items . "` WHERE `column_uuid` = %s ORDER BY `position`",
				$uuid
			)
		);

		return $result;
	}

	/**
	 * @param $container_uuid
	 * @param $column_uuid
	 * @param $columnProps
	 * @param $viewport
	 */
	public function create( $container_uuid, $column_uuid, $columnProps, $viewport ) {
		$this->db->replace(
			$this->db->prefix . $this->table_container_items,
			[
				'container_uuid' => $container_uuid,
				'column_uuid' => $column_uuid,
				'elements'    => json_encode( $columnProps->elements ),
				'viewport'    => $viewport,
				'visibility'  => $columnProps->visibility,
				'position'    => $columnProps->position

			]
		);
	}

	/**
	 * @param $uuid
	 */
	public function deleteByContainerUuid( $uuid ) {
		$this->db->delete(
			$this->db->prefix . $this->table_container_items,
			[
				'container_uuid' => $uuid
			]
		);
	}

	/**
	 * @param $uuid
	 */
	public function deleteByColumnUuid( $uuid ) {
		$this->db->delete(
			$this->db->prefix . $this->table_container_items,
			[
				'column_uuid' => $uuid
			]
		);
	}
}
