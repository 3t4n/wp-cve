<?php
/**
 * Patterns rest api controller.
 *
 * @package Omnipress\RestApi\Controllers\V1
 */

namespace Omnipress\RestApi\Controllers\V1;

use Omnipress\Abstracts\RestControllersBase;
use Omnipress\Models\PatternsModel;

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PatternsController extends RestControllersBase {

	/**
	 * {@inheritDoc}
	 */
	public function register_routes() {
		$this->register_rest_route(
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' )
				),
			)
		);

		$this->register_rest_route(
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'update_favorites' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' )
				),
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_favorites' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' )
				),
				array(
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'remove_favorites' ),
					'permission_callback' => array( $this, 'delete_item_permissions_check' )
				),
			),
			'favorites'
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_items( $request ) {
		$patterns_model = new PatternsModel();

		if ( $request->get_param( 'sync' ) ) {
			$patterns_model->sync();
		}

		if ( $request->get_param( 'filter' ) ) {
			$patterns_model->filter( $request->get_param( 'filter' ) );
		}

		return $patterns_model->get();
	}

	/**
	 * Returns favorites.
	 *
	 * @param \WP_REST_Request $request
	 */
	public function get_favorites( $request ) {
		$patterns_model = new PatternsModel();
		return $patterns_model->get_favorites();
	}

	/**
	 * Update favorites stack.
	 *
	 * @param \WP_REST_Request $request
	 */
	public function update_favorites( $request ) {

		$key = $request->get_param('key');

		if ( ! $key ) {
			return;
		}

		$patterns_model = new PatternsModel();

		$patterns_model->set_favorite( $key );

		return $patterns_model->get_favorites();
	}

	/**
	 * Update favorites stack.
	 *
	 * @param \WP_REST_Request $request
	 */
	public function remove_favorites( $request ) {

		$key = $request->get_param('key');

		if ( ! $key ) {
			return;
		}

		$patterns_model = new PatternsModel();

		$patterns_model->remove_favorite( $key );

		return $patterns_model->get_favorites();
	}

}
