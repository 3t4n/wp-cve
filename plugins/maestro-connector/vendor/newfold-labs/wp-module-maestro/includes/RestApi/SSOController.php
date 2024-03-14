<?php

namespace NewfoldLabs\WP\Module\Maestro\RestApi;

use Exception;
use WP_REST_Server;
use WP_REST_Response;

use NewfoldLabs\WP\Module\Maestro\Auth\WebPro;
use NewfoldLabs\WP\Module\Maestro\Auth\Token;

/**
 * Class SSOController
 */
class SSOController extends \WP_REST_Controller {

	/**
	 * The namespace of this controller's route.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	protected $namespace = 'bluehost/maestro/v1';

	/**
	 * The current Web Pro accessing the endpoint
	 *
	 * @since 1.0
	 *
	 * @var WebPro
	 */
	private $webpro;

	/**
	 * Registers the SSO route
	 *
	 * @since 1.0
	 */
	public function register_routes() {

		register_rest_route(
			$this->namespace,
			'/sso',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'new_sso' ),
					'args'                => array(
						'bounce' => array(
							'required' => false,
							'type'     => 'string',
						),
					),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

	}

	/**
	 * Callback for the SSO Endpoint
	 *
	 * Returns a short-lived JWT that can be passed to wp-login for instant authentication
	 *
	 * @since 1.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Rest_Response Returns a standard rest response with the SSO link included
	 */
	public function new_sso( $request ) {

		// We want to SSO into the same user making the current request
		// User is also already verified as a Maestro using the permission callback
		// Create a temporary single-use JWT; expires in 30 seconds
		$token  = new Token();
		$jwt    = $token->generate_token( $this->webpro, 30, true, array( 'type' => 'sso' ) );
		$bounce = $request['bounce'];

		if ( is_wp_error( $jwt ) ) {
			return $jwt;
		}

		// Parms for the auto-login URL
		$params   = array(
			'action' => 'bh-maestro-sso',
			'token'  => $jwt,
			'bounce' => $bounce,
		);
		$link     = add_query_arg( $params, admin_url( 'admin-ajax.php' ) );
		$response = array( 'link' => $link );

		return new WP_Rest_Response( $response );
	}

	/**
	 * Verify permission to access this endopint
	 *
	 * By registering a permission callback, we already limit the endpoint to authenticated users,
	 * but we should also verify the actual current user making the request is a connected Web Pro.
	 * Regular admins shouldn't be able to use this endpoint. They should log in like normal.
	 *
	 * @since 1.0
	 *
	 * @return boolean Whether to allow access to endpoint.
	 */
	public function check_permission() {

		// We want to SSO into the same user making the current request
		// User is also already verified as a Maestro using the permission callback
		$user_id = get_current_user_id();

		try {
			$this->webpro = new WebPro( $user_id );
		} catch ( Exception $e ) {
			return false;
		}

		return $this->webpro->is_connected();

	}

}
