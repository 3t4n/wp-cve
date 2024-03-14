<?php

namespace CTXFeed\V5\API;

use CTXFeed\V5\API\V1\AttributesMapping;
use CTXFeed\V5\API\V1\CategoryMapping;
use CTXFeed\V5\API\V1\DropDown;
use CTXFeed\V5\API\V1\DynamicAttributes;
use CTXFeed\V5\API\V1\MakeFeed;
use CTXFeed\V5\API\V1\ManageFeeds;
use CTXFeed\V5\API\V1\MerchantConfig;
use CTXFeed\V5\API\V1\MerchantInfo;
use CTXFeed\V5\API\V1\ProductCategories;
use CTXFeed\V5\API\V1\Products;
use CTXFeed\V5\API\V1\ProductTaxonomy;
use CTXFeed\V5\API\V1\Settings;
use CTXFeed\V5\API\V1\WooFeedDocs;
use CTXFeed\V5\API\V1\WPStatus;
use CTXFeed\V5\API\V1\WPOptions;
use \WP_REST_Controller;
use \WP_Error;
use CTXFeed\V5\Helper\CommonHelper;

/**
 * Class RestController
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\API
 * @author     Azizul Hasan <azizulhasan.cr@gmail.com>
 * @link       https://azizulhasan.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 */
class RestController extends WP_REST_Controller {

	/**
	 * @var array $response ;
	 */
	public $response = [
		'status' => 200,
		'data'   => [],
		'extra'  => null
	];
	/**
	 * The single instance of the class
	 *
	 * @var RestController
	 *
	 */
	protected static $_instance = null;

	/**
	 * @var $version ;
	 */
	private $version = WOO_FEED_API_VERSION;

	protected function __construct() {

		$this->namespace = WOO_FEED_API_NAMESPACE . '/' . $this->version;
		add_action( 'rest_api_init', [ $this, 'register_api' ] );
		add_action( 'rest_api_init', function ( $var ) {
			remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
		}, 15, 1 );

	}


	/**
	 * Main RestController Instance.
	 *
	 * Ensures only one instance of RestController is loaded or can be loaded.
	 *
	 * @return RestController Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Return true only user is logged in as $mange_ctx_feed roles.
	 * Using postman or other API client Basic-Auth plugin must be installed ( URL is below )
	 * authorization system should be Basic Auth.
	 *
	 * @@ -86,7 +86,16 @@ public static function instance() {
	 * @see https://github.com/WP-API/Basic-Auth
	 */
	public function get_item_permissions_check( $request ) {
		$user               = wp_get_current_user();
		$mange_ctx_feed     = apply_filters('ctx_feed_api_accessed_users', [ 'manage_options', 'manage_woocommerce' ]);
		$current_user_roles = $user->get_role_caps();
		$current_user_roles = array_keys( $current_user_roles );
		foreach ( $mange_ctx_feed as $role ) {
			if ( in_array( $role, $current_user_roles ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Register routes according to $_SERVER['REQUEST_URI'].
	 * After 'wp-json' value will be considered as namespace.
	 * After that v1/v2 will be as api version number.
	 * Then next value will be considered as route name.
	 *
	 * Example : wp-json/ctxfeed/v1/drop_down/?type=feed_country
	 *
	 * @description
	 * ctxfeed:  is namespace
	 * v1: is version number and will indicate version folder number
	 * drop_down: route. It will look into 'DropDown' class in V1 folder.
	 * @return void
	 */
	public function register_api() {
//		$uri           = trim( $_SERVER['REQUEST_URI'], '/' );
//		$uri_arr       = explode( '/', $uri );
//		$namespace     = explode( '/', $this->namespace );
//		$uri_namespace = '';
//		$class_name    = '';
//		$version       = $this->version;
		// Get rest base like : 'wp-json' or 'index.php?rest_route='
//		$rest_base = str_replace( home_url(), '', get_rest_url() );
//		$rest_base = trim( $rest_base, '/' );
//
//		foreach ( $uri_arr as $i => $value ) {
//			// Get namespace name;
//			if ( $value === $rest_base ) {
//				$i ++;
//				if ( ! isset( $uri_arr[ $i ] ) ) {
//					break;
//				}
//				$uri_namespace = $uri_arr[ $i ];
//				$i             += 2;
//				// Get current classname from url after version number.
//				if ( isset( $uri_arr[ $i ] ) ) {
//					$class_name = $uri_arr[ $i ];
//					$version    = $uri_arr[ -- $i ];
//				}
//				break;
//			}
//		}

//		 If current namespace and url namespace are equal
//		 load class name from version folder.
//		if ( $class_name && count( $namespace ) && $namespace[0] == $uri_namespace ) {
//			if ( class_exists( $class_name ) ) {
//				self::load_class( $class_name, $version )->register_routes();
//			}else{
				$classes = [
					AttributesMapping::instance(),
					CategoryMapping::instance(),
					DropDown::instance(),
					DynamicAttributes::instance(),
					ManageFeeds::instance(),
					MerchantConfig::instance(),
					MerchantInfo::instance(),
					ProductCategories::instance(),
					Products::instance(),
					ProductTaxonomy::instance(),
					Settings::instance(),
					WPOptions::instance(),
					MakeFeed::instance(),
					WPStatus::instance(),
					WooFeedDocs::instance(),

				];
				foreach ( $classes as $class ) {
					$class->register_routes();
				}
//			}
//		}
	}


	/**
	 * Cloning is forbidden.
	 */
	final public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cloning is forbidden.', 'woo-feed' ), WOO_FEED_FREE_VERSION );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	final public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'woo-feed' ), WOO_FEED_FREE_VERSION );
	}


	/**
	 * @param $data
	 *
	 * @return void|\WP_REST_Response
	 */
	public function success( $data, $status = 200 ) {
		$this->response['status'] = $status;
		$this->response['data']   = $data;

		$response = rest_ensure_response( $this->response );
		$response = $this->add_additional_headers( $response );

		return $response;
	}


	/**
	 * @param $data
	 *
	 * @return void|\WP_Error
	 */
	public function error( $data = '', $code = 'rest_no_data_found', $status = 404 ) {
		$this->response['status'] = $status;
		$this->response['data']   = $data;
		$this->response['code']   = $code;

		$this->response['status'] = $status;
		$this->response['data']   = $data;

		$response = rest_ensure_response( $this->response );
		$response = $this->add_additional_headers( $response );

		return $response;
	}

	/**
	 * @param $response
	 *
	 * @return \WP_REST_Response
	 */
	protected function add_additional_headers( $response ) {
		$admin_origin = parse_url( admin_url() );
		$response->header( 'Access-Control-Allow-Origin', $admin_origin['host'] );

		return $response;
	}

	/**
	 * @param $args
	 * @param $data
	 * @param $response
	 *
	 * @return mixed
	 */
	protected function maybe_add_pagination( $args, $data, $response ) {
		// Get data according to pagination. If $page and $per_page params are passed in the url.
		$total = count( $data );
		if ( isset( $args['per_page'], $args['page'] ) ) {
			$total_pages = ceil( $total / (int) $args['per_page'] );
			// Set current page data.
			$offset                 = $args['per_page'] * ( $args['page'] - 1 );
			$this->response['data'] = array_slice( $data, $offset, $args['per_page'] );
			$response               = $this->add_pagination_links( $response, $args, $total_pages, $total );
		} else {
			$this->response['data'] = $data;
		}
		$response->data = $this->response;

		return $response;
	}

	/**
	 * @param $response
	 * @param $args
	 * @param $total_pages
	 * @param $total
	 *
	 * @return mixed
	 */
	protected function add_pagination_links( $response, $args, $total_pages, $total ) {

		$url = get_site_url() . '/wp-json/' . $this->namespace . '/' . $this->rest_base . '/?';

		$page = (int) $args['page'];
		unset( $args['page'] );
		$total_args = count( $args );
		$count      = 0;
		foreach ( $args as $arg => $value ) {
			$count ++;
			if ( $count === $total_args ) {
				$url .= $arg . '=' . $value;
			} else {
				$url .= $arg . '=' . $value . '&';
			}
		}
		// Next page link add.
		if ( $total_pages == $page ) {
			$next_url = $url . '&page=' . $page;
		} else {
			$next_page = $page + 1;
			$next_url  = $url . '&page=' . $next_page;
		}
		$response->add_link( 'next_page', $next_url );
		// Previous page link add.
		if ( $page == 1 ) {
			$prev_url = $url . '&page=' . $page;
		} else {
			$prev_page = $page - 1;
			$prev_url  = $url . '&page=' . $prev_page;
		}
		$response->add_link( 'prev_page', $prev_url );
		// add headers.
		$response->header( 'X-WP-TotalPages', (int) $total_pages );
		$response->header( 'X-WP-Total', (int) $total );

		return $response;
	}

	/**
	 * @param $array
	 *
	 * @return bool
	 */
	protected function is_assoc( $array ) {
		if ( array() === $array ) {
			return false;
		}

		return ( $array !== array_values( $array ) );
	}

	public function is_prefix_matched( $string, $prefix ) {
		return str_starts_with( $string, $prefix );
	}


	private static function load_class( $class = null, $version = 'v1' ) {
		$class_name = self::get_class_name( $class );

		return RestFactory::load( $class_name, $version ) ?? null;
	}

	/**
	 * @param $class
	 *
	 * @return string
	 */
	private static function get_class_name( $class ) {
		$api_class = array_map( function ( $part ) {
			if ( 'wp' === $part ) {
				return strtoupper( $part );
			}

			return ucfirst( $part );
		}, explode( '_', $class ) );

		return implode( '', $api_class );
	}


	/**
	 * @param $request
	 *
	 * @return array
	 */
	protected function get_lists( $request, $arr ) {
		$lists = [];

		if ( ! empty( $arr ) ) {
			foreach ( $arr as $option_name => $attr_list ) {
				$item                  = $this->prepare_item_for_response( $attr_list, $request );
				$lists[ $option_name ] = $item;
			}
		}

		return $lists;
	}

	/**
	 * @param $item
	 * @param $request
	 *
	 * @return void|\WP_Error|\WP_REST_Response
	 */
	public function prepare_item_for_response( $item, $request ) {
		return maybe_unserialize( $item );
	}


	public function unique_option_name( $option_name, $prefix, $add_prefix = false ) {
		$option_name = preg_replace( "/[^A-Za-z0-9_]/", '', $option_name );
		if ( false !== get_option( $prefix . $option_name, false ) ) {
			$option = CommonHelper::unique_option_name( $option_name, $prefix );
		} else {
			$option = $add_prefix ? $prefix . $option_name : $option_name;
		}

		$response = [ 'option_name' => $option ];

		return $response;
	}

	/**
	 * @param $request
	 *
	 * @return string
	 * If Dynamic Attribute, Category mapping and Attribute mapping option name with special char like '&' by default php split the link when
	 * get &, to make unique option name with & we use this function.
	 */
	public function get_feed_option_name( $request ) {
		$args      = $request->get_params();
		$feed_name = $args['name'];

		$feed_name = str_replace( "plus", "+", $feed_name );
		array_shift( $args );
		$temp_arr = [ $feed_name ];
		if ( count( array_keys( $args ) ) > 1 ) {
			$temp_arr2 = implode( '&', array_keys( $args ) );
			array_push( $temp_arr, $temp_arr2 );
			$feed_name = implode( '&', $temp_arr );
		}


		return $feed_name;
	}
}
