<?php

namespace CTXFeed\V5\API\V1;

use CTXFeed\V5\API\RestController;
use CTXFeed\V5\Common\Helper;
use CTXFeed\V5\Feed\Feed;
use CTXFeed\V5\Helper\FeedHelper;
use CTXFeed\V5\Utility\Config;
use WP_REST_Server;

/**
 * Class MakeFeed
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\API
 * @author     Azizul Hasan <azizulhasan.cr@gmail.com>
 * @link       https://azizulhasan.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 */
class MakeFeed extends RestController {

	/**
	 * The single instance of the class
	 *
	 * @var $_instance
	 *
	 */
	protected static $_instance = null;

	/**
	 * The instance of config
	 *
	 * @var $config
	 *
	 */
	protected static $config = null;

	public function __construct() {
		parent::__construct();
		$this->rest_base = 'make_feed';


	}

	/**
	 * Main MakeFeed Instance.
	 *
	 * Ensures only one instance of MakeFeed is loaded or can be loaded.
	 *
	 * @return $_instance Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function register_routes() {

		// Save feed
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/save_feed_config',
			[
				/**
				 * @endpoint: wp-json/ctxfeed/v1/make_feed/save_feed_config
				 *
				 * @param $file_ext_type String
				 */
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'save_feed_config' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [],
				],
				'schema' => [ $this, 'get_item_schema' ],
			]
		);

		// Get product ids Feed.
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/get_product_ids',
			[
				/**
				 * @endpoint: wp-json/ctxfeed/v1/make_feed/get_product_ids
				 *
				 * @param $file_ext_type String
				 */
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'get_product_ids' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [],
				],
				'schema' => [ $this, 'get_item_schema' ],
			]
		);

		// Generate Feed
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/make_per_batch_feed',
			[
				/**
				 * @endpoint: wp-json/ctxfeed/v1/make_feed/make_per_batch_feed
				 *
				 * @param $file_ext_type String
				 */
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'make_per_batch_feed' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [],
				],
				'schema' => [ $this, 'get_item_schema' ],
			]
		);

		// Save Feed File
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/save_feed_file',
			[
				/**
				 * @endpoint: wp-json/ctxfeed/v1/make_feed/save_feed_file
				 *
				 * @param $file_ext_type String
				 */
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'save_feed_file' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [],
				],
				'schema' => [ $this, 'get_item_schema' ],
			]
		);


	}


	/**
	 *
	 *
	 * @param $request
	 *
	 * @return void|\WP_Error|\WP_REST_Response
	 */
	public function save_feed_config( $request ) {
		$config_data      = json_decode( $request->get_body(), true );

		$feed_option_name = null;
		if ( ! empty( $config_data['feed_info']['option_name'] ) ) {
			$feed_option_name = $config_data['feed_info']['option_name'];
		}

		if ( ! isset( $config_data['feed_info']['option_value'], $config_data['feed_info']['option_value']['feedrules'] ) ) {
			return $this->error( __( "Feed rules not provided properly", 'woo-feed' ) );
		}

		$config = new Config( $config_data['feed_info'] );

		$feed_rules        = $config_data['feed_info']['option_value']['feedrules'];
		$saved_option_name = $config->save_config( $feed_rules, $feed_option_name );

		$single_feed     = Feed::get_single_feed( $saved_option_name );
		$saved_feed_info = is_array( $single_feed ) && count( $single_feed ) ? $single_feed[0] : [];
		$response        = [
			'feed_info' => $saved_feed_info
		];

		delete_transient( 'ctx_feed_structure_transient' );


		return $this->success( $response );
	}

	/**
	 *  Get product ids
	 *
	 * @param $request
	 *
	 * @return void|\WP_Error|\WP_REST_Response
	 */
	public function get_product_ids( $request ) {
		$body = json_decode( $request->get_body(), true );
		if ( empty( $body['feed_info'] ) ) {
			return $this->error( __( 'Feed Info Is Empty.', 'woo-feed' ) );
		}

		$feed_info = $body['feed_info'];
		$ids       = FeedHelper::get_product_ids( $feed_info );

		if ( empty( $ids ) ) {
			return $this->error( __( 'Product not found.', 'woo-feed' ) );
		}

		return $this->success( $ids );
	}

	/**
	 *  Make feed
	 *
	 * @param $request
	 *
	 * @return void|\WP_Error|\WP_REST_Response
	 */
	public function make_per_batch_feed( $request ) {
		$body        = json_decode( $request->get_body(), true );
		$offset      = (int) $body['offset'];
		$product_ids = array_map( 'absint', $body['product_ids'] );
		$feedrules   = $body['feed_info']['option_value']['feedrules'];


		// Write log if debug log is enabled.
		if ( Helper::is_debugging_enabled() ) {
			FeedHelper::log_data( $feedrules, $offset, $product_ids );
		}


		try {

			$status = false;
			$status = FeedHelper::generate_temp_feed_body( $body['feed_info'], $product_ids, $offset );

			return $this->success( [
				'status'  => $status,
				'offset'  => $offset,
				'message' => $status ? __( 'Temporary Feed Generated', 'woo-feed' ) : __( 'Something went wrong.', 'woo-feed' )
			] );
		} catch ( Exception $e ) {
			$message = 'Error Generating Product Data.' . PHP_EOL . 'Caught Exception :: ' . $e->getMessage();
			woo_feed_log( $feedrules['filename'], $message, 'critical', $e, true );
			woo_feed_log_fatal_error( $message, $e );

			return $this->error( [ 'status' => false, 'offset' => $offset, 'message' => $message ] );
		}


	}


	/**
	 *
	 *
	 * @param $request
	 *
	 * @return void|\WP_Error|\WP_REST_Response
	 */
	public function save_feed_file( $request ) {

		$body                           = json_decode( $request->get_body(), true );
		$should_update_last_update_time = false;
		if ( isset( $body['should_update_last_update_time'] ) && $body['should_update_last_update_time'] ) {
			$should_update_last_update_time = true;
		}
		$save_file = FeedHelper::save_feed_file( $body['feed_info'], $should_update_last_update_time );
		$status    = $save_file['status'];
		$feed_url  = $save_file['feed_url'];
		if ( is_wp_error( $status ) ) {
			return $this->error( $status->get_error_message(), $status->get_error_code() );
		}

		$response = [
			'message'  => __( 'Feed Successfully Generated', 'woo-feed' ),
			'feed_url' => $feed_url,
			'notice'   => [
				'type'    => 'warning',
				'message' => '',
				'link'    => ''
			]
		];

		return $this->success( $response );
	}


}

