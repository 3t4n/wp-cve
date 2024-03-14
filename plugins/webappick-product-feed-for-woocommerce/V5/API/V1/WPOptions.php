<?php

namespace CTXFeed\V5\API\V1;

use CTXFeed\V5\API\RestController;
use CTXFeed\V5\API\RestConstants;
use CTXFeed\V5\Output\WPOptions as WPOptionBase;
use \WP_REST_Server;

/**
 * Class WPOptions
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\API\V1
 * @author     Azizul Hasan <azizulhasan.cr@gmail.com>
 * @link       https://azizulhasan.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 */
class WPOptions extends RestController {
	/**
	 * @var array
	 */
	private static $option_lists = [];
	/**
	 * The single instance of the class
	 *
	 * @var WPOptions
	 */
	protected static $_instance = null;

	private function __construct() {
		parent::__construct();
		$this->rest_base = RestConstants::WP_OPTION_REST_BASE;
	}

	/**
	 * Main WPOptions Instance.
	 *
	 * Ensures only one instance of WPOptions is loaded or can be loaded.
	 *
	 * @return WPOptions Main instance
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
				 * @endpoint: wp-json/ctxfeed/v1/wp_options
				 * @description  Will get all feed lists
				 *
				 *
				 * @endpoint wp-json/ctxfeed/v1/wp_options/?page=1&per_page=2
				 * @descripton Get paginated value with previous page and next page link
				 *
				 * @param $name String
				 *
				 * @param $page Number
				 * @param $per_page Number
				 */
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_items' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
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
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'create_item' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [],
				],
				/**
				 * @endpoint wp-json/ctxfeed/v1/wp_options/?name=wf_feed_google_shopping
				 * @method DELETE
				 * @descripton Delete single attribute
				 *
				 * @param $name String
				 */
				[
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => [ $this, 'delete_item' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [],
				],
			],
		);
	}

	/**
	 * @param $request \WP_REST_Request request body will be  []
	 *
	 *
	 * @return \WP_Error|\WP_REST_Response|\WP_HTTP_Response
	 */
	public function delete_item( $request ) {
		$body = $request->get_body();
		$body = json_decode( $body );
		self::$option_lists = WPOptionBase::deleteWPOption( $body);

		return $this->success( self::$option_lists );
	}

	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function create_item( $request ) {
		$body               = $request->get_body();
		$body               = json_decode( $body );
		self::$option_lists = WPOptionBase::saveWPOption( $body );

		return $this->success( self::$option_lists );
	}


	/**
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function get_items( $request ) {
		$args               = $request->get_params();
		self::$option_lists = WPOptionBase::getWPOptions();
		$response           = rest_ensure_response( $this->response );
		$response           = $this->maybe_add_pagination( $args, self::$option_lists, $response );

		return $response;
	}

}
