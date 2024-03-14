<?php
/**
 * REST API Roles controller
 *
 * Handles requests to the roles endpoint.
 *
 * @category API
 * @package Masteriyo\RestApi
 * @since 1.7.3
 */

 namespace Masteriyo\RestApi\Controllers\Version1;

 use Masteriyo\Helper\Permission;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * REST API roles Roles controller class.
 * @package Masteriyo\RestApi
 * @extends CrudController
 */
class RolesController extends CrudController {
	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'roles';

	/**
	 * Object type.
	 *
	 * @var string
	 */
	protected $object_type = 'roles';

	/**
	 * Register routes.
	 *
	 * @since 1.7.3
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_roles' ),
					'permission_callback' => 'is_user_logged_in',
				),
			)
		);
	}


	/**
	 * Get roles.
	 *
	 * @since 1.7.3
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_roles( $request ) {
		$roles  = get_editable_roles();
		$result = array();

		foreach ( $roles as $key => $role ) {
			$result[] = array(
				'value' => $key,
				'label' => $role['name'],
			);
		}

		return rest_ensure_response( $result );
	}
}
