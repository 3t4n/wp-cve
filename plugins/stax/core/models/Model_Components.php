<?php
/**
 * Components Model.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Model_Components extends Base_Model {

	/**
	 * @var null
	 */
	public static $instance = null;

	/**
	 * @return null|Model_Components
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
	public function get() {
		$components = $this->db->get_results(
			"SELECT * FROM `" . $this->db->prefix . $this->table_components . "` WHERE `deleted_at` IS NULL ORDER BY `created_at` DESC"
		);

		return $components;
	}

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function save( \WP_REST_Request $request ) {
		$data = $request->get_params();

		if ( isset( $data['name'] ) && isset( $data['element'] ) ) {
			$inserted_id = $this->create( $data['name'], $data['element'] );

			return $this->response( self::STATUS_OK, [ $inserted_id ] );
		}

		return $this->response( self::STATUS_FAILED );
	}

	/**
	 * @param $name
	 * @param $properties
	 *
	 * @return int
	 */
	public function create( $name, $properties ) {
		$properties = @json_decode( $properties );
		$properties = $this->clearTrash( $properties );

		$this->db->insert(
			$this->db->prefix . $this->table_components,
			[
				'name'       => $name,
				'properties' => json_encode( $properties )
			]
		);

		return $this->db->insert_id;
	}

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function delete( \WP_REST_Request $request ) {
		$data = $request->get_params();

		if ( ! isset( $data['id'] ) || ! $data['id'] ) {
			return $this->response( self::STATUS_NOTFOUND );
		}

		$this->db->update(
			$this->db->prefix . $this->table_components,
			[
				'deleted_at' => ( new \DateTime() )->format( 'Y-m-d H:i:s' )
			],
			[
				'id' => $data['id']
			]
		);

		return $this->response( self::STATUS_OK );
	}

}
