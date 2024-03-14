<?php

/**
 * class User
 *
 * @link       https://appcheap.io
 * @since      1.0.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 *
 */

namespace AppBuilder\Api;

defined( 'ABSPATH' ) || exit;

class User extends Base {

	public function __construct() {
		parent::__construct();
		$this->namespace = APP_BUILDER_REST_BASE . '/v1';
	}

	/**
	 * Add the endpoints to the API
	 * @since 1.0.0
	 * @author ngocdt
	 */
	public function register_routes() {
		add_filter( 'rest_prepare_user', array( $this, 'rest_prepare_user' ), 10, 3 );
	}

	public function rest_prepare_user( \WP_REST_Response $response, \WP_User $user, \WP_REST_Request $request ): \WP_REST_Response {
		$data = $response->get_data();

		// Set count post for user
		$data['count_posts'] = (int) count_user_posts( $user->ID );

		// Avatar
		$data['avatar_urls'] = apply_filters( 'app_builder_prepare_avatar_data', $user->ID, [] );

		$response->set_data( $data );

		return $response;
	}
}
