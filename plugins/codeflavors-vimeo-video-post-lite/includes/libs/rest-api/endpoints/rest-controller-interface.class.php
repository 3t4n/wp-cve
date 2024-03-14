<?php

namespace Vimeotheque\Rest_Api\Endpoints;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @ignore
 */
interface Rest_Controller_Interface {
	/**
	 * Returns the Rest API base of the resource
	 */
	public function get_rest_base();

	/**
	 * Returns Rest namespace
	 */
	public function get_namespace();

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed
	 */
	public function get_response( \WP_REST_Request $request );

	/**
	 * Route registration
	 *
	 * @return mixed
	 */
	public function register_routes();

	/**
	 * Returns endpoint route
	 *
	 * @return mixed
	 */
	public function get_route();
}