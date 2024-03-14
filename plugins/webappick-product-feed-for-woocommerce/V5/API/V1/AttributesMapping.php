<?php

namespace CTXFeed\V5\API\V1;

use CTXFeed\V5\API\RestConstants;
use CTXFeed\V5\API\RestController;
use CTXFeed\V5\Common\Helper;
use CTXFeed\V5\Output\AttributeMapping;
use Woo_Feed_Notices;
use \WP_REST_Server;
use CTXFeed\V5\Product\AttributeValueByType;
use CTXFeed\V5\Output;

/**
 * Class AttributesMapping
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\API
 * @author     Azizul Hasan <azizulhasan.cr@gmail.com>
 * @link       https://azizulhasan.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 */
class AttributesMapping extends RestController {
	/**
	 * @var array
	 */
	private static $attribute_mapping = null;
	/**
	 * @var array
	 */
	private static $attr_lists = array();
	/**
	 * The single instance of the class
	 *
	 * @var AttributesMapping
	 *
	 */
	protected static $_instance = null;

	private function __construct() {
		parent::__construct();
		$this->rest_base         = RestConstants::ATTRIBUTE_MAPPING_REST_BASE;
		self::$attribute_mapping = new AttributeMapping();
	}

	/**
	 * Main AttributesMapping Instance.
	 *
	 * Ensures only one instance of AttributesMapping is loaded or can be loaded.
	 *
	 * @return AttributesMapping Main instance
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
			array(
				/**
				 * @endpoint: wp-json/ctxfeed/v1/attributes_mapping
				 * @description  Will get all feed lists
				 *
				 *
				 * @endpoint wp-json/ctxfeed/v1/attributes_mapping/?page=1&per_page=2
				 * @descripton Get paginated value with previous page and next page link
				 *
				 * @endpoint wp-json/ctxfeed/v1/attributes_mapping/?name=google_shopping
				 * @method GET
				 * @descripton Get single attribute
				 *
				 * @param $name String
				 *
				 * @param $page Number
				 * @param $per_page Number
				 */
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(
						'name'     => array(
							'description'       => __( 'feed name', 'woo-feed' ),
							'type'              => 'string',
							'required'          => false,
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'page'     => array(
							'description'       => __( 'Page number', 'woo-feed' ),
							'type'              => 'number',
							'required'          => false,
							'sanitize_callback' => 'absint',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'per_page' => array(
							'description'       => __( 'Per page', 'woo-feed' ),
							'type'              => 'number',
							'required'          => false,
							'sanitize_callback' => 'absint',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(),
				),
				/**
				 * @endpoint wp-json/ctxfeed/v1/attributes_mapping/?name=google_shopping
				 * @method DELETE
				 * @descripton Delete single attribute
				 *
				 * @param $name String
				 */
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(
						'name' => array(
							'description'       => __( 'feed name', 'woo-feed' ),
							'type'              => 'string',
							'required'          => true,
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),

			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/unique_option_name',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'get_unique_option_name' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/attribute_preview',
			array(
				/**
				 * @endpoint wp-json/ctxfeed/v1/attributes_mapping/attribute_preview
				 * @description  will return a single product attribute based on arrtibute mapping.
				 *
				 * @param
				 * @method GET
				 *
				 */
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_attribute_preview_data' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(),
				),
			)
		);

	}

	/**
	 * @param $request
	 *
	 * @return \WP_REST_Response|object
	 */
	public function get_attribute_preview_data( $request ) {
		$option_name = $this->get_feed_option_name( $request );
		$attr_value  = AttributeMapping::get_attribute_value( $option_name );

		return $this->success( $attr_value );
	}

	/**
	 * @param $request
	 *
	 * @return \WP_REST_Response|null
	 */
	public function get_unique_option_name( $request ) {
		$body        = $request->get_body();
		$body        = (array) json_decode( $body );
		$option_name = $body['option_name'];

		$response = $this->unique_option_name( $option_name, AttributeValueByType::PRODUCT_ATTRIBUTE_MAPPING_PREFIX, false );

		return $this->success( $response );
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
		$name = $this->get_feed_option_name( $request );

		if ( self::$attribute_mapping->deleteMapping( $name ) ) {
			self::$attr_lists = self::$attribute_mapping->getMappings();

			$this->response['data'] = $this->get_lists( $request, self::$attr_lists );
			$attribute_preview_values = AttributeMapping::get_attributes_preview_data( $this->response['data'] );
			$this->response['extra'] = $attribute_preview_values;
			return rest_ensure_response( $this->response );
		}

		return $this->error( sprintf( __( 'No attribute found with name: %s', 'woo-feed' ), $name ) );
	}

	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function create_item( $request, $is_edit = '' ) {
		$body = $request->get_body();
		$body = (array) json_decode( $body );
		// Save option name.
		self::$attribute_mapping->saveMapping( $body );
		// Get attributes mapping.
		self::$attr_lists = self::$attribute_mapping->getMappings();

		$this->response['data'] = $this->get_lists( $request, self::$attr_lists );

		$attribute_preview_values = AttributeMapping::get_attributes_preview_data( $this->response['data'] );
		$this->response['extra'] = $attribute_preview_values;

		if ( $is_edit == '' ) {

			Woo_Feed_Notices::woo_feed_saved_attribute_mapping_notice_data();

		}

		return rest_ensure_response( $this->response );
	}

	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response|null
	 */
	public function get_item( $request ) {
		$feed_name = $this->get_feed_option_name( $request );

		$item                                                                                    = (array) self::$attribute_mapping->getMapping( $feed_name );
		self::$attr_lists[ AttributeValueByType::PRODUCT_ATTRIBUTE_MAPPING_PREFIX . $feed_name ] = $this->prepare_item_for_response( $item, $request );
		if ( count( self::$attr_lists ) && isset( self::$attr_lists[ AttributeValueByType::PRODUCT_ATTRIBUTE_MAPPING_PREFIX . $feed_name ] ) ) {
			return $this->success( self::$attr_lists );
		}

		return $this->error( sprintf( __( 'Not found with: %s or prefix: "' . AttributeValueByType::PRODUCT_ATTRIBUTE_MAPPING_PREFIX . '" does\'nt match.', 'woo-feed' ), $feed_name ) );
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
		self::$attr_lists = self::$attribute_mapping->getMappings();

		$data = $this->get_lists( $request, self::$attr_lists );

		$attribute_preview_values = AttributeMapping::get_attributes_preview_data( $data );
		$this->response['extra'] = $attribute_preview_values;
		$response = rest_ensure_response( $this->response );

		$response = $this->maybe_add_pagination( $args, $data, $response );

		return $response;
	}

}
