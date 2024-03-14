<?php
/**
 * Templates Model.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Model_Templates extends Base_Model {

	/**
	 * @var null
	 */
	public static $instance = null;

	/**
	 * @return null|Model_Templates
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
		$templates = $this->db->get_results(
			"SELECT * FROM `" . $this->db->prefix . $this->table_templates . "` WHERE `deleted_at` IS NULL ORDER BY `created_at` DESC"
		);

		return $templates;
	}

	/**
	 * @param $id
	 *
	 * @return array|null|object|void
	 */
	public function getById( $id ) {
		$template = $this->db->get_row(
			$this->db->prepare(
				"SELECT * FROM `" . $this->db->prefix . $this->table_templates . "` WHERE `deleted_at` IS NULL AND `id` = %s",
				$id
			)
		);

		return $template;
	}

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function save( \WP_REST_Request $request ) {
		$data = $request->get_params();

		if ( ! is_array( $data )
		     || ! isset( $data['name'] )
		     || ! isset( $data['zone'] )
		     || ! isset( $data['containers'] )
		     || ! isset( $data['columns'] )
		     || ! isset( $data['elements'] )
		     || ! isset( $data['group'] )
		     || ! isset( $data['fonts'] ) ) {
			return $this->response( self::STATUS_FAILED );
		}

		$pack = [
			'zone'       => json_decode( $data['zone'] ),
			'containers' => [],
			'columns'    => [],
			'elements'   => [],
			'group'      => json_decode( $data['group'] )
		];

		$containers = json_decode( $data['containers'] );
		$columns    = json_decode( $data['columns'] );
		$elements   = json_decode( $data['elements'] );
		$fonts      = json_decode( $data['fonts'] );
		$saveFonts  = [];

		foreach ( $containers as $key => $container ) {
			$saveFonts = $this->getItemFonts( $saveFonts, $fonts, $container );
			$container = $this->clearTrash( $container );

			$pack['containers'][ $container->uuid ] = $container;
		}

		foreach ( $columns as $key => $column ) {
			$saveFonts                        = $this->getItemFonts( $saveFonts, $fonts, $column );
			$column                           = $this->clearTrash( $column );
			$pack['columns'][ $column->uuid ] = $column;
		}

		foreach ( $elements as $key => $element ) {
			$saveFonts                          = $this->getItemFonts( $saveFonts, $fonts, $element );
			$element                            = $this->clearTrash( $element );
			$pack['elements'][ $element->uuid ] = $element;
		}

		$pack['fonts'] = $saveFonts;

		$template_id = $this->createOrUpdate( $data['name'], $pack );

		return $this->response( self::STATUS_OK, [ $template_id ] );
	}

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function update( \WP_REST_Request $request ) {
		$data = $request->get_params();

		if ( ! isset( $data['id'] ) || ! isset( $data['name'] ) ) {
			return $this->response( self::STATUS_FAILED );
		}

		$this->db->update(
			$this->db->prefix . $this->table_templates,
			[
				'name'       => $data['name'],
				'updated_at' => ( new \DateTime() )->format( 'Y-m-d H:i:s' )
			],
			[
				'id' => $data['id']
			]
		);

		return $this->response( self::STATUS_OK );
	}

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function delete( \WP_REST_Request $request ) {
		$data = $request->get_params();

		if ( ! isset( $data['id'] ) ) {
			return $this->response( self::STATUS_FAILED );
		}

		$this->db->update(
			$this->db->prefix . $this->table_templates,
			[
				'deleted_at' => ( new \DateTime() )->format( 'Y-m-d H:i:s' )
			],
			[
				'id' => $data['id']
			]
		);

		return $this->response( self::STATUS_OK );
	}

	/**
	 * @param $name
	 * @param $pack
	 *
	 * @return int
	 */
	public function createOrUpdate( $name, $pack ) {
		$this->db->insert(
			$this->db->prefix . $this->table_templates,
			[
				'name' => $name,
				'pack' => json_encode( $pack )
			]
		);

		return $this->db->insert_id;
	}
}
