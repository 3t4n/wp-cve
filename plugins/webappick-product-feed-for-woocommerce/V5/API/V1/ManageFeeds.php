<?php

namespace CTXFeed\V5\API\V1;

use CTXFeed\V5\API\RestConstants;
use CTXFeed\V5\API\RestController;
use CTXFeed\V5\Common\DownloadFiles;
use CTXFeed\V5\Common\Helper;
use CTXFeed\V5\Feed\Feed;
use CTXFeed\V5\Helper\FeedHelper;
use \WP_REST_Server;

/**
 * Class ManageFeeds
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\API
 * @author     Azizul Hasan <azizulhasan.cr@gmail.com>
 * @link       https://azizulhasan.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 */
class ManageFeeds extends RestController {

	private static $status = null;
	private static $feed_lists = [];
	/**
	 * The single instance of the class
	 *
	 * @var ManageFeeds
	 *
	 */
	protected static $_instance = null;

	private function __construct() {
		parent::__construct();
		$this->rest_base = RestConstants::MANAGE_FEEDS_REST_BASE;
	}

	/**
	 * Main ManageFeeds Instance.
	 *
	 * Ensures only one instance of ManageFeeds is loaded or can be loaded.
	 *
	 * @return ManageFeeds Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Register routes.
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				/**
				 * @endpoint: wp-json/ctxfeed/v1/manage_feeds
				 * @description  Will get all feed lists
				 *
				 * @endpoint wp-json/ctxfeed/v1/manage_feeds/?status=inactive
				 * @method GET
				 * @description  Only inactive feed lists will be returned.
				 *
				 *
				 * @endpoint wp-json/ctxfeed/v1/manage_feeds/?status=active
				 * @method GET
				 * @description  Only active feed lists will be returned.
				 *
				 * @endpoint wp-json/ctxfeed/v1/manage_feeds/?status=active&page=1&per_page=2
				 * @method GET
				 * @descripton Get paginated value with previous page and next page link
				 *
				 *
				 * @endpoint wp-json/ctxfeed/v1/manage_feeds/?name=wf_feed_google_shopping
				 * @method GET
				 * @descripton Get single feed
				 *
				 * @param $name String
				 *
				 * @param $status String
				 * @param $page Number
				 * @param $per_page Number
				 */
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_items' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
						'status'   => [
							'description'       => __( 'Is active or inactive', 'woo-feed' ),
							'type'              => 'string',
							'required'          => false,
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						],
						'name'     => [
							'description'       => __( 'feed name', 'woo-feed' ),
							'type'              => 'string',
							'required'          => false,
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						],
						'page'     => [
							'description'       => __( 'Page number', 'woo-feed' ),
							'type'              => 'number',
							'required'          => false,
							'sanitize_callback' => 'absint',
							'validate_callback' => 'rest_validate_request_arg',
						],
						'per_page' => [
							'description'       => __( 'Per page', 'woo-feed' ),
							'type'              => 'number',
							'required'          => false,
							'sanitize_callback' => 'absint',
							'validate_callback' => 'rest_validate_request_arg',
						],
					],
				],
			]
		);
		// Duplicate feed.
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/duplicate',
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'duplicate_feed' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
						'feed' => [
							'description'       => __( 'Feed slug', 'woo-feed' ),
							'type'              => 'string',
							'required'          => true,
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						],
					]
				]
			]
		);

		// Update feed status
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/update_feed_status',
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'update_feed_status' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
						'feed'   => [
							'description'       => __( 'Feed slug', 'woo-feed' ),
							'type'              => 'string',
							'required'          => true,
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						],
						'status' => [
							'description'       => __( 'Feed status enabled or disabled', 'woo-feed' ),
							'type'              => 'number',
							'required'          => true,
							'sanitize_callback' => 'absint',
							'validate_callback' => 'rest_validate_request_arg',
						],
					]
				]
			]
		);

		// Delete feed
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/delete_feed',
			[
				[
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => [ $this, 'delete_feed' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
						'feed_id' => [
							'description'       => __( 'Feed id', 'woo-feed' ),
							'type'              => 'string',
							'required'          => true,
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						],
					]
				]
			]
		);

		// Download feed file/log/config.
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/download',
			[
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'download' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => []
				]
			]
		);

		// Get Auto Interval Schedules
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/schedules',
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_schedules' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => []
				]
			]
		);
		// Set Update Interval
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/update_interval',
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'update_interval' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
						'time' => [
							'description'       => __( 'Initerval time', 'woo-feed' ),
							'type'              => 'number',
							'required'          => true,
							'sanitize_callback' => 'absint',
							'validate_callback' => 'rest_validate_request_arg',
						],
					]
				]
			]
		);

		// Clear cache
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/clear_cache',
			[
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'clear_cache' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => []
				]
			]
		);

	}

	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response|null
	 */
	public function get_item( $request ) {
		$args      = $request->get_params();
		$feed_name = $args['name'];
		$feed_name = sanitize_text_field( wp_unslash( $feed_name ) );
		if ( $feed_name ) {
			$current_feed = Feed::get_single_feed( $feed_name );
			if ( $current_feed ) {
				if( !is_array( $current_feed[0]['option_value']['feedrules']['product_ids'] ) ){
					$included_ids_str = $current_feed[0]['option_value']['feedrules']['product_ids'];
					if( $included_ids_str == "" ){
						$included_ids = [];
					}else{
						$included_ids = [];
						$included_ids_array = explode(",",$included_ids_str);
						foreach ( $included_ids_array as $singleId ){
							$included_ids[] = (int) $singleId;
						}
					}
					$current_feed[0]['option_value']['feedrules']['product_ids'] = $included_ids;
				}else{
					$included_ids = array_map('absint', $current_feed[0]['option_value']['feedrules']['product_ids']);
					$current_feed[0]['option_value']['feedrules']['product_ids'] = $included_ids;
				}
				return $this->success( $current_feed );
			}
		}

		return $this->error( sprintf( __( 'Not found with: %s ', 'woo-feed' ), $feed_name ) );
	}

	/**
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function get_items( $request ) {

		$args         = $request->get_params();
		self::$status = isset( $args['status'] ) ? $args['status'] : $this::$status;
		if ( isset( $args['name'] ) ) {
			return $this->get_item( $request );
		}
		$this::$feed_lists = Feed::get_all_feeds( self::$status );
		if ( self::$status ) {
			// True if status is active/inactive
			if ( 'active' === self::$status || 'inactive' === self::$status ) {
				$data = $this::$feed_lists;
			} else {
				return $this->error( __( 'Status should be active/inactive !', 'woo-feed' ) );
			}
		} else {
			$data = $this::$feed_lists;
		}

		$response = rest_ensure_response( $this->response );
		$response = $this->maybe_add_pagination( $args, $data, $response );

		return $response;
	}


	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response|null
	 */
	public function duplicate_feed( $request ) {
		$args = $request->get_params();

		$response = Feed::duplicate_feed( $args['feed'] );

		if ( is_wp_error( $response ) ) {
			return $this->error( $response->get_error_message(), $response->get_error_code() );
		}

		$all_feeds = Feed::get_all_feeds();

		return $this->success( $all_feeds );
	}


	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response|null
	 */
	public function update_feed_status( $request ) {

		$args     = $request->get_params();
		$response = Feed::update_feed_status( $args['feed'], $args['status'] );

		if ( $response ) {
			$all_feeds = Feed::get_all_feeds();

			return $this->success( $all_feeds );
		}

		return $this->error( 'Something went wrong.' );

	}

	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response|null
	 */
	public function delete_feed( $request ) {
		$feed_id  = $request->get_param( 'feed_id' );
		$response = Feed::delete_feed( $feed_id );

		if ( $response ) {
			$all_feeds = Feed::get_all_feeds();

			return $this->success( $all_feeds );
		}

		return $this->error( 'Something went wrong' );
	}

	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response|null
	 */
	public function download( $request ) {
		$body = $request->get_body();
		$body = (array) json_decode( $body );
		if ( $body['type'] == '' ) {
			$body['type'] = 'feed';
		}
		$data    = false;
		$message = '';
		switch ( $body['type'] ) {
			case 'feed':
				$data    = DownloadFiles::rest_download_feed( $body['rest_download_feed'] );
				$message = sprintf( __( 'Downloaded feed:  %s file', 'woo-feed' ), $body['feed_name'] );
				break;
			case 'config':
				$data    = DownloadFiles::rest_download_config( $body['feed'] );
				$message = sprintf( __( 'Downloaded feed config:  %s file', 'woo-feed' ), $body['feed_name'] );
				break;
			case 'log':
				$data    = DownloadFiles::rest_download_log( $body['feed'] );
				$message = sprintf( __( 'Downloaded feed log:  %s file', 'woo-feed' ), $body['feed_name'] );
				break;
			default:
				$data    = DownloadFiles::rest_download_feed( $body['feed'] );
				$message = sprintf( __( 'Downloaded feed:  %s file', 'woo-feed' ), $body['feed_name'] );

		}

		if ( is_wp_error( $data ) ) {
			return $this->error( $data->get_error_message(), $data->get_error_code() );
		}

		return $this->success( $message );

	}

	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response|null
	 */
	public function get_schedules( $request ) {

		$schedules             = FeedHelper::get_schedule_interval_options();
		$response['schedules'] = $schedules;
		$response['interval']  = get_option( 'wf_schedule' );

		return $this->success( $response );
	}

	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response|null
	 */
	public function update_interval( $request ) {
		$args     = $request->get_params();
		$response = [
			'status'   => 0,
			'interval' => $args['time']
		];
		$interval = absint( $args['time'] );

		if ( $interval >= FeedHelper::get_minimum_interval_option() ) {
			if ( update_option( 'wf_schedule', sanitize_text_field( wp_unslash( $interval ) ), false ) ) {
				wp_clear_scheduled_hook( 'woo_feed_update' );
				if ( Helper::is_pro() ) {
					add_filter( 'cron_schedules', 'Woo_Feed_Pro_Installer::cron_schedules' ); // phpcs:ignore
				} else {
					add_filter( 'cron_schedules', 'Woo_Feed_installer::cron_schedules' ); // phpcs:ignore
				}

				wp_schedule_event( time(), 'woo_feed_corn', 'woo_feed_update' );

				$response['status'] = 1; // success.
			} else {
				$response['status'] = 2; // db fail.
			}
		} else {
			$response['status'] = 3; // invalid value.
		}

		return $this->success( $response );
	}

	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response|null
	 */
	public function clear_cache( $request ) {
		$body   = $request->get_body();
		$result = Helper::clear_cache_data( $body );

		return $this->success( $result );
	}

}
