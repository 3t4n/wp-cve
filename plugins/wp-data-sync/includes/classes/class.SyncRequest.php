<?php
/**
 * SyncRequest
 *
 * Process the DataSync Request.
 *
 * @since   1.0.0
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

class SyncRequest extends Request {

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

	protected $log_key = 'sync-request-data';

	/**
	 * @var SyncRequest
	 */

	public static $instance;

	/**
	 * SyncRequest constructor.
	 */

	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * Instance.
	 *
	 * @return SyncRequest
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
			'wp-data-sync/' . WPDSYNC_EP_VERSION,
			'/sync/(?P<access_token>\S+)/(?P<cache_buster>\S+)/',
			[
				'methods' => WP_REST_Server::CREATABLE,
				'args'    => [
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
				'callback'            => [ $this, 'process' ],
			]
		);

	}

	/**
	 * Process the request.
	 *
	 * @return mixed|\WP_REST_Response
	 */

	public function process() {

		global $wpds_response;

		/**
		 * Disable revisions.
		 */

		add_filter( 'wp_revisions_to_keep', '__return_false' );

		$start_request  = microtime( true );
		$wpds_response  = [];
		$data_sync = DataSync::instance();
		$data      = $this->request_data();

		if ( isset( $data['items'] ) && is_array( $data['items'] ) ) {

			foreach ( $data['items'] as $key => $data ) {

				$data_sync->set_properties( $data );
				$data_sync->process();

			}

		}
		else {

			$data_sync->set_properties( $data );
			$data_sync->process();

		}

		$wpds_response['request_time'] = microtime( true ) - $start_request;
		Log::write( 'sync-request-response', $wpds_response );

		return rest_ensure_response( $wpds_response );

	}

}
