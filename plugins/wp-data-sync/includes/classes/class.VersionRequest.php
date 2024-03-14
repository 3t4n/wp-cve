<?php
/**
 * Version Request
 *
 * Request plugin version.
 *
 * @since   1.4.2
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VersionRequest extends Request {

	/**
	 * @var string
	 */

	protected $access_token_key = 'wp_data_sync_access_token';

	/**
	 * @var string
	 */

	protected $private_token_key = 'wp_data_sync_private_token';

	/**
	 * @var string
	 */

	protected $permissions_key = 'wp_data_sync_allowed';

	/**
	 * @var VersionRequest
	 */

	public static $instance;

	/**
	 * VersionRequest constructor.
	 */

	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * Instance.
	 *
	 * @return VersionRequest
	 */

	public static function instance() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Register the route.
	 *
	 * @since 1.0.0
	 *        2.0.0 Require version as any 2 character string
	 */

	public function register_route() {

		register_rest_route(
			'wp-data-sync',
			'/(?P<ep_version>\S+)/get-version/(?P<access_token>\S+)/(?P<cache_buster>\S+)/',
			[
				'methods' => WP_REST_Server::READABLE,
				'args'    => [
					'ep_version' => [
						'validate_callback' => function( $param ) {
						    return is_string( $param ) && 2 === strlen( $param );
					    }
					],
					'access_token' => [
						'sanitize_callback' => 'sanitize_text_field',
						'validate_callback' => [ $this, 'access_token' ]
					],
					'cache_buster' => [
						'validate_callback' => function( $param ) {
							return is_string( $param );
						}
					]
				],
				'permission_callback' => [ $this, 'access' ],
				'callback'            => [ $this, 'request' ],
			]
		);

	}

	/**
	 * Process the request.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */

	public function request() {

		return rest_ensure_response( [
			'version' => WPDSYNC_VERSION
		] );

	}

}
