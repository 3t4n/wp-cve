<?php

namespace CTXFeed\V5\API\V1;

use CTXFeed\V5\API\RestConstants;
use CTXFeed\V5\API\RestController;
use CTXFeed\V5\Common\Helper;
use CTXFeed\V5\Output\CategoryMapping as CategoryMappingBase;
use CTXFeed\V5\Product\AttributeValueByType;
use Woo_Feed_Notices;
use \WP_REST_Server;

/**
 *
 */
class CategoryMapping extends RestController {
	/**
	 * @var array
	 */
	private static $category_lists = [];

	/**
	 * The single instance of the class
	 *
	 * @var CategoryMapping
	 *
	 */
	protected static $_instance = null;
	/**
	 * @var CategoryMappingBase|null
	 */
	private static $category_mapping = null;

	private function __construct() {
		parent::__construct();
		$this->rest_base          = RestConstants::CATEGORY_MAPPING_REST_BASE;
		self::$category_mapping = new CategoryMappingBase();
	}

	/**
	 * Main CategoryMapping Instance.
	 *
	 * Ensures only one instance of CategoryMapping is loaded or can be loaded.
	 *
	 * @return CategoryMapping Main instance
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
				 * @endpoint: wp-json/ctxfeed/v1/category_mapping
				 * @description  Will get all feed lists
				 *
				 *
				 * @endpoint wp-json/ctxfeed/v1/category_mapping/?page=1&per_page=2
				 * @descripton Get paginated value with previous page and next page link
				 *
				 * @endpoint wp-json/ctxfeed/v1/category_mapping/?name=wf_feed_google_shopping
				 * @method GET
				 * @descripton Get single attribute
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
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'create_item' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [],
				],
				[
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'update_item' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [],
				],
				/**
				 * @endpoint wp-json/ctxfeed/v1/category_mapping/?name=wf_feed_google_shopping
				 * @method DELETE
				 * @descripton Delete single attribute
				 *
				 * @param $name String
				 */
				[
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => [ $this, 'delete_item' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
						'name' => [
							'description'       => __( 'feed name', 'woo-feed' ),
							'type'              => 'string',
							'required'          => true,
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						],
					],
				],

			]
		);


		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/unique_option_name',
			[
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'get_unique_option_name' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [],
				],
			]
		);
	}

	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response|\WP_HTTP_Response
	 */
	public function update_item( $request ) {
		$is_edit = 'edit';
		return $this->create_item( $request, $is_edit );
	}

	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response|\WP_HTTP_Response
	 */
	public function delete_item( $request ) {
//		$name = $request->get_param( 'name' );
		$name = $this->get_feed_option_name($request);

		if ( self::$category_mapping->deleteCategoryMapping( $name ) ) {

			self::$category_lists   = self::$category_mapping->getCategoryMappings();
			$this->response['data'] = $this->get_lists( $request, self::$category_lists );

			return $this->success( $this->response['data'] );

		}

		return $this->error( sprintf( __( 'No attribute found with name: %s', 'woo-feed' ), $name ) );
	}

	/**
	 * @param $request
	 *
	 * @return \WP_REST_Response|null
	 */
	public function get_unique_option_name( $request ) {
		$body        = $request->get_body();
		$body        = (array) json_decode( $body );
		$option_name = $body['option'];
		$response = $this->unique_option_name( $option_name, AttributeValueByType::PRODUCT_CATEGORY_MAPPING_PREFIX );

		return $this->success( $response );
	}

	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function create_item( $request, $is_edit = '' ) {
		$body = $request->get_body();
		$body = Helper::object_to_array( json_decode( $body ) );
		// Save option name.
		self::$category_mapping->saveCategoryMapping( $body );
		// Get option name.
		$this->response['data'] = self::$category_mapping->getCategoryMappings();

		$this->response['data'] = $this->get_lists( $request, $this->response['data'] );

		if( $is_edit == '' ) {

			Woo_Feed_Notices :: woo_feed_saved_category_mapping_notice_data();

		}

		return rest_ensure_response( $this->response );
	}

	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response|null
	 */
	public function get_item( $request ) {

		$feed_name = $this->get_feed_option_name($request);

		self::$category_lists = self::$category_mapping->getCategoryMapping( $feed_name );
		if ( ! empty( self::$category_lists ) ) {
			$item = $this->prepare_item_for_response( self::$category_lists, $request );

			return $this->success( $item );
		}

		return $this->error( sprintf( __( 'Not found with: %s or prefix: "' . AttributeValueByType::PRODUCT_CATEGORY_MAPPING_PREFIX . '" does\'nt match.', 'woo-feed' ), $feed_name ) );
	}

	/**
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function get_items( $request ) {
		$args = $request->get_params();
		if ( isset( $args['name'] ) ) {
			return $this->get_item( $request );
		}
		$this::$category_lists = self::$category_mapping->getCategoryMappings();

		$data                   = $this->get_lists( $request, $this::$category_lists );
		$this->response['data'] = $data;
		$response               = rest_ensure_response( $this->response );
		$response               = $this->maybe_add_pagination( $args, $data, $response );

		return $response;
	}

}
