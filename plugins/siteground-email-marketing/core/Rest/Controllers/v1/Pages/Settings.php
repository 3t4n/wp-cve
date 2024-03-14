<?php
namespace SG_Email_Marketing\Rest\Controllers\v1\Pages;

use SG_Email_Marketing\Traits\Rest_Trait;
use SG_Email_Marketing\Loader\Loader;

/**
 * Class responsible for the Settings plugin page.
 */
class Settings {
	use Rest_Trait;

	/**
	 * Integrations list
	 *
	 * @var array
	 */
	public $integrations;

	/**
	 * The Mailer API object.
	 *
	 * @var Mailer_Api
	 */
	public $mailer_api;

	/**
	 * The Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$loader             = Loader::get_instance();
		$this->integrations = $loader->get_integrations();
		$this->mailer_api   = $loader->mailer_api;
	}

	/**
	 * Register the rest routes for the Settings Page.
	 *
	 * @since 1.0.0
	 */
	public function register_rest_routes() {
		// Check connection status.
		register_rest_route(
			$this->rest_namespace,
			'/connect/',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_connection_status' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
				),
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'connect' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
				),
			),
		);

		// Disconnect from email marketing.
		register_rest_route(
			$this->rest_namespace,
			'/disconnect/',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'disconnect' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
			)
		);

		// Get all integration.
		register_rest_route(
			$this->rest_namespace,
			'/integrations/',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
			)
		);

		// Get single integration.
		register_rest_route(
			$this->rest_namespace,
			'/integrations/(?P<id>[\w]+)',
			array(
				'args' => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'siteground-email-marketing' ),
						'type'        => 'string',
					),
				),
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(
						'id' => array(
							'validate_callback' => function ( $id, $request, $key ) {
								return array_key_exists( $id, $this->integrations );
							},
						),
					),
				),
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'id' => array(
						'validate_callback' => function ( $id, $request, $key ) {
							return array_key_exists( $id, $this->integrations );
						},
					),
				),
			),
		);
	}

	/**
	 * Get the connection status.
	 *
	 * @since 1.0.0
	 */
	public function get_connection_status() {
		try {
			$response          = $this->mailer_api->get_status();
			$response['token'] = get_option( 'sg_email_marketing_token', null );

			return rest_ensure_response( $response );
		} catch ( \Exception $e ) {
			return $this->get_errors( $e );
		}
	}

	/**
	 * Connect to the remote API endpoint.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Request $request The incoming request object.
	 */
	public function connect( $request ) {
		$body = json_decode( $request->get_body(), true );

		if ( empty( $body['token'] ) ) {
			return new \WP_Error(
				'error',
				__( 'Missing token!', 'siteground-email-marketing' ),
				array( 'status' => 403 ),
			);
		}

		try {
			$response = $this->mailer_api->connect( $body['token'] );
			// If the token is existing update the token in the db.
			if ( in_array( $response['status_code'], array( 204, 403 ), true ) ) {
				update_option( 'sg_email_marketing_token', $body['token'] );
			}

			return rest_ensure_response( $response );
		} catch ( \Exception $e ) {
			return $this->get_errors( $e );
		}
	}

	/**
	 * Disconnect the Email marketing plugin.
	 *
	 * @since 1.0.0
	 */
	public function disconnect( $request ) {
		$body = json_decode( $request->get_body(), true );

		if ( empty( $body['token'] ) ) {
			return new \WP_Error(
				'error',
				__( 'Missing token!', 'siteground-email-marketing' ),
				array( 'status' => 403 ),
			);
		}

		return rest_ensure_response( $this->mailer_api->disconnect( $body['token'] ) );
	}

	/**
	 * Get all integrations.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Request $request The incoming request object.
	 */
	public function get_items( $request ) {
		// Prepare the integrations.
		$all_integrations = array();

		// Loop the integrations to prepare them for the SPA.
		foreach ( $this->integrations as $id => $integration ) {
			$all_integrations[ $id ] = $integration->fetch_settings();

		}

		return rest_ensure_response( $all_integrations );
	}

	/**
	 * Get single integrations.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Request $request The incoming request object.
	 */
	public function get_item( $request ) {
		$id = $request->get_params()['id'];

		return rest_ensure_response( $this->integrations[ $id ]->fetch_settings() );
	}

	/**
	 * Update single integration.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Request $request The incoming request object.
	 */
	public function update_item( $request ) {
		$body = json_decode( $request->get_body(), true );

		return rest_ensure_response(
			array(
				'body' => $this->integrations[ $body['id'] ]->update_settings( $body['body'] ),
				'meta' => $body['meta'],
			)
		);
	}
}
