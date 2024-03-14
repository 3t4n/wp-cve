<?php

namespace CTXFeed\V5\API\V1;

use CTXFeed\V5\API\RestController;
use CTXFeed\V5\Helper\FeedHelper;
use CTXFeed\V5\Merchant\TemplateConfig;
use CTXFeed\V5\Query\WCQuery;
use WP_REST_Server;
use CTXFeed\V5\Common\DropDownOptions;
use \WP_Error;

/**
 * Class AttributesMapping
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\API
 * @author     Azizul Hasan <azizulhasan.cr@gmail.com>
 * @link       https://azizulhasan.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 */
class DropDown extends RestController {

	/**
	 * @var $dropdown
	 */
	protected $dropdown;
	/**
	 * The single instance of the class
	 *
	 * @var DropDown
	 *
	 */
	protected static $_instance = null;

	private function __construct() {
		parent::__construct();

		$this->rest_base = 'drop_down';
		$this->dropdown  = DropDownOptions::instance();

	}

	/**
	 * Main DropDownOptionsApi Instance.
	 *
	 * Ensures only one instance of DropDownOptionsApi is loaded or can be loaded.
	 *
	 * @return DropDown Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				/**
				 * @endpoint: wp-json/ctxfeed/v1/drop_down/?type=feed_country
				 *
				 * @param $type String  will be DropDownOptions class\'s method name
				 */
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_item' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
						'type'   => [
							'description' => __( 'Dropdown type name. $type will be DropDownOptions class\'s method name. Example: wp-json/ctxfeed/v1/drop_down/?type=feed_country. Here fee_country is DropDownOptions method name.' ),
							'type'        => 'string',
							'required'    => true
						],
						'search' => [
							'description' => __( 'Search with search string in the specific method. If search string exists.' ),
							'type'        => 'string',
							'required'    => false,
							'default'     => ''
						],
					],
				],
				'schema' => [ $this, 'get_item_schema' ],
			]
		);
		// single feed rules
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/feed_rules',
			[
				/**
				 * @endpoint: wp-json/ctxfeed/v1/drop_down/feed_rules/?template=custom
				 *
				 * @param $template String  feed template name.
				 */
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_feed_rules' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
						'template' => [
							'description' => __( 'Template name' ),
							'type'        => 'string',
							'required'    => true
						],
					],
				],
				'schema' => [ $this, 'get_item_schema' ],
			]
		);

		// fetch all initial feed rules
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/initial_feed_rules',
			[
				/**
				 * @endpoint: wp-json/ctxfeed/v1/drop_down/initial_feed_rules
				 *
				 * @param $template String  feed template name.
				 */
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'get_initial_feed_rules' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [],
				],
			]
		);
	}


	/**
	 * Get dropdown based on type params. If parameter 'type' is not passed then it will give error.
	 * $type will be DropDownOptions class's method name.
	 *
	 * if method name is @param DropDownOptions@get_categories extra parameter cal be
	 *                  "slug"   =>    string|comma separated 'string'
	 *                  "search" =>   string
	 *
	 * if method name is @param DropDownOptions@all_product_ids extra parameter cal be
	 *                  "include" =>    product id |comma separated  product ids
	 *                  "s"       =>   string
	 *
	 * @param $request
	 *
	 * @return void|\WP_Error|\WP_REST_Response
	 */
	public function get_item( $request ) {

		$method = $request->get_param( 'type' );

		$query_args = $this->get_query_args( $request->get_params() );

		if ( method_exists( $this->dropdown, $method ) && count( $query_args ) ) {
			$this->response['data'] = $this->dropdown::$method( $query_args );
		} else if ( method_exists( $this->dropdown, $method ) ) {
			$this->response['data'] = $this->dropdown::$method( '', false );
		} else {
			return $this->error( __( 'Method Does not exist !', 'woo-feed' ) );
		}

		$response = $this->success( $this->response['data'] );
		if ( is_array( $this->response['data'] ) ) {
			$response->header( 'X-WP-Total', count( $this->response['data'] ) );
		}

		return $response;
	}

	/**
	 * @param $query_args
	 *
	 * @return mixed
	 */
	public function get_query_args( $query_args ) {
		foreach ( $query_args as $key => $value ) {
			// if parameter value is empty remove it.
			if ( empty( $value ) ) {
				unset( $query_args[ $key ] );
			}

			if ( in_array( $key, [ 'slug', 'include' ] ) ) {
				// if value is comma ( , ) separated then make it an array
				if ( false !== strpos( $value, ',' ) ) {
					$query_args[ $key ] = explode( ',', $value );
				} else {
					$query_args[ $key ] = [ $value ];
				}
			}
		}
		// unset method name
		unset( $query_args['type'] );
		if ( isset( $query_args['rest_route'] ) ) {
			unset( $query_args['rest_route'] );
		}

		return $query_args;
	}

	/**
	 * Get feed configuration based on template name.
	 *
	 * @param $request
	 *
	 * @return void|\WP_Error|\WP_REST_Response
	 */
	public function get_feed_rules( $request ) {

		$template         = $request->get_param( 'template' );
		$specialTemplates = FeedHelper::get_special_templates();
		if ( in_array( $template, $specialTemplates ) ) {
			$merchantData           = TemplateConfig::get( $template );
			$this->response['data'] = $merchantData['feed_config_custom2'];
            $this->response['extra'] = [
                'provider' => $template,
            ];
			$response               = $this->success( $this->response['data'] );
			$response->header( 'X-WP-Total', 1 );
		} else {
			$this->response['data'] = TemplateConfig::get( $template );
            $this->response['extra'] = [
                'provider' => $template,
            ];
            $response               = $this->success( $this->response['data'] );
			$response->header( 'X-WP-Total', count( $this->response['data'] ) );
		}


		return $response;
	}


	/**
	 * Get feed configuration based on template name.
	 *
	 * @param $request
	 *
	 * @return void|\WP_Error|\WP_REST_Response
	 */
	public function get_initial_feed_rules( $request ) {
		$templates              = $request->get_body();
		$templates              = json_decode( $templates , true);
		$this->response['data'] = TemplateConfig::getMultiple( $templates );
		$response               = $this->success( $this->response['data'] );
		$response->header( 'X-WP-Total', count( $this->response['data'] ) );

		return $response;
	}

	/**
	 * Retrieves the contact schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			return $this->add_additional_fields_schema( $this->schema );
		}

		$schema = [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'dropdown',
			'type'       => 'array',
			'properties' => [
				'dropdown' => [
					'description' => __( 'Unique identifier for the object.' ),
					'type'        => 'array',
					'context'     => [ 'view', 'edit' ],
					'readonly'    => false,
				],
			]
		];

		$this->schema = $schema;

		return $this->add_additional_fields_schema( $this->schema );
	}

	/**
	 * Retrieves the query params for collections.
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		unset( $params['search'] );

		return $params;
	}

}
