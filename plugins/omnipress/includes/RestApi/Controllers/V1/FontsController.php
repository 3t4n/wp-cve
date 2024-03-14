<?php
/**
 * Patterns rest api controller.
 *
 * @package Omnipress\RestApi\Controllers\V1
 */

namespace Omnipress\RestApi\Controllers\V1;

use Omnipress\Abstracts\RestControllersBase;
use Omnipress\Models\FontsModel;

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FontsController extends RestControllersBase {

	/**
	 * {@inheritDoc}
	 */
	public function register_routes() {
		$this->register_rest_route(
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => '__return_true', //array( $this, 'get_items_permissions_check' )
				),
			)
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_items( $request ) {
		$fonts_model = new FontsModel();

		if ( 'raw' === $request->get_param( 'format' ) ) {
			return $fonts_model->get_raw();
		}

		if ( ! empty( $request->get_param( 'family' ) ) ) {
			return $fonts_model->get_font_attrs( $request->get_param( 'family' ) );
		}

		return $fonts_model->get();
	}

}
