<?php
/**
 * LogRequest
 *
 * Request the log files.
 *
 * @since   2.3.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LogRequest extends Request {

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
	 * @var string
	 */

	private $callback;

	/**
	 * @var LogRequest
	 */

	public static $instance;

	/**
	 * LogRequest constructor.
	 */

	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * Instance.
	 *
	 * @return LogRequest
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
	 * @link https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
	 */

	public function register_route() {

		register_rest_route(
			'wp-data-sync',
			'/' . WPDSYNC_EP_VERSION . '/log/(?P<callback>\S+)/(?P<access_token>\S+)/(?P<cache_buster>\S+)/',
			[
				'methods' => WP_REST_Server::READABLE,
				'args'    => [
					'callback' => [
						'sanitize_callback' => 'sanitize_text_field',
						'validate_callback' => [ $this, 'set_callback' ]
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
	 * Set callback.
	 *
	 * @param $callback
	 *
	 * @return bool
	 */

	public function set_callback( $callback ) {

		if ( is_string( $callback ) ) {

			$this->callback = sanitize_text_field( $callback );

			return true;

		}

		return false;

	}

	/**
	 * Process the request.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */

	public function request() {

		switch( $this->callback ) {

			case 'list':
				$response = Log::log_files();
				break;

			default:
				$contents = Log::contents( $this->callback );
				$response = json_encode( $contents, JSON_PRETTY_PRINT );
				break;

		}

		Log::write( 'log-request-response', $response );

		return rest_ensure_response( $response );

	}

}
