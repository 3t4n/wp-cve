<?php

namespace Vimeotheque\Vimeo_Api;

use Vimeotheque\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Vimeo_Api_Query
 * @package Vimeotheque
 * @ignore
 */
class Vimeo_Api_Query extends Vimeo {

	/**
	 * Store parameters.
	 * @var array
	 */
	private $params;

	/**
	 * The type of resource that should be queried: album, channel, etc.
	 *
	 * @var string
	 */
	private $resource_type;

	/**
	 * The resource ID: album id, channel id, etc.
	 *
	 * @var bool|string
	 */
	private $resource_id;

	/**
	 * Vimeo user ID
	 *
	 * @var bool|string
	 */
	private $api_user_id;

	/**
	 * Vimeo_Api_Query constructor.
	 *
	 * @param string        $resource_type  The type of resource that should be queried (ie. album, channel, etc).
	 * @param string|bool   $resource_id    The API resource ID (ie. channel ID, album ID, user ID, etc).
	 * @param string|bool   $api_user_id    The Vimeo user ID that should be used when making queries for albums or portfolios.
	 * @param array $args   {
	 *      Additional request parameters.
	 *
	 *      @type   int $page                   The page number to retrieve from Vimeo API.
	 *      @type   int $per_page               Number of results per page.
	 *      @type   string $query               The search query string or resource ID (showcase ID, folder ID, username, etc.).
	 *      @type   string $filter              Results filtering; has specific value based on the required feed type (ie. playable,
	 *                                          embeddable, featured, live, etc.).
	 *                                          See Vimeo API docs for the spcific resource imported to get the available
	 *                                          filtering options.
	 *      @type   bool $filter_embeddable     Filter results by embeddable videos (true) or non-embeddable videos (false). Requires
	 *                                          parameter "filter" to be set to "embeddable".
	 *      @type   bool $filter_playable       Whether to filter the results by playable videos (true) or non-playable videos (false).
	 *      @type   string $links               The page containing the video URI.
	 *      @type   string $password            Password for password restricted resources (ie. showcases).
	 * }
	 *
	 */
	public function __construct( $resource_type, $resource_id = false, $api_user_id = false, $args = [] ){

		$this->resource_type = $resource_type;
		$this->resource_id = $resource_id;
		$this->api_user_id = $api_user_id;
		/**
		 * Defaults must not include parameters "sort" and "direction". If not specified by
		 * the concrete implementation, the resource default will be used. This is useful when
		 * performing automatic imports which implement ordering by default and allows different
		 * order parameters to be used.
		 */
		$default = [
			'page' => 1,
			'per_page' => 20,
			'query' => '',
			'filter' => '',
			'filter_embeddable' => false,
			'filter_playable' => false,
			'links' => '',
			'password' => ''
		];
		
		$this->params = wp_parse_args( $args, $default );		
	}
	
	/**
	 * Makes a request based on the params passed on constructor
	 */
	public function request_feed(){

		$endpoint = $this->_get_endpoint();
		$api_resource = $this->get_api_resource();

		if( is_wp_error( $endpoint ) ){
			// send a debug message for any client listening to plugin messages
			Helper::debug_message(
				sprintf(
					__( 'Endpoint API returned an error: %s.' ),
					$endpoint->get_error_message()
				)
			);

			return $endpoint;
		}

		// send a debug message for any client listening to plugin messages
		Helper::debug_message(
			sprintf(
				__( 'Making %s remote request to: %s.' ),
				$api_resource->get_request_method(),
				$endpoint
			)
		);

		$request_args = [
			'method' => $api_resource->get_request_method(),
			/**
			 * Vimeo API query request timeout filter.
			 *
			 * @param int $timeouot     The request timeout.
			 */
			'timeout' => apply_filters( 'vimeotheque\vimeo_api\request_timeout' , 30 ),
			'sslverify' => false,
			'headers' => [
				'user-agent' => Helper::request_user_agent(),
				'authorization' => 'bearer ' . Helper::get_access_token(),
				'accept' => parent::VERSION_STRING
			]
		];

		if( in_array( $api_resource->get_request_method(), ['POST', 'PATCH', 'PUT', 'DELETE'] ) ){
			// send only the variables set as defaults for the resource
			$request_args['body'] = array_intersect_key(
				$this->get_api_request_params(),
				$api_resource->get_default_params()
			);

			// send a debug message for any client listening to plugin messages
			Helper::debug_message(
				sprintf(
					__( 'The request is sending the following variables: %s.' ),
					implode( ', ', array_keys( $request_args['body'] ) )
				)
			);
		}

		$request = wp_remote_request( $endpoint, $request_args );
		
		$rate_limit = wp_remote_retrieve_header( $request, 'x-ratelimit-limit' );
		if( $rate_limit ){
			// send a debug message for any client listening to plugin messages
			Helper::debug_message(
				sprintf( 
					__( 'Current rate limit: %s (%s remaining). Limit reset time set at %s.' ), 
					$rate_limit, 
					wp_remote_retrieve_header( $request , 'x-ratelimit-remaining' ),
					wp_remote_retrieve_header( $request , 'x-ratelimit-reset' )
				) 
			);
		}
		
		// if Vimeo returned error, return the error
		if( 200 != wp_remote_retrieve_response_code( $request ) ){
			// get request data
			$data = json_decode( wp_remote_retrieve_body( $request ), true );

			Helper::debug_message(
				sprintf(
					'Vimeo API query returned error: "%s"',
					isset( $data['error'] ) ? $data['error'] : 'unknown error'
				)
			);

			$data['response'] = $request['response'];

			return parent::api_error( $data );
		}	
		
		return $request;
	}

	/**
	 * Returns endpoint URL complete with params for a given existing action.
	 *
	 * @return string|\WP_Error
	 */
	private function _get_endpoint(){

		$api_resource = $this->get_api_resource();
		if( is_wp_error( $api_resource ) ){
			return $api_resource;
		}

		$api_resource->set_resource_id( $this->resource_id );
		$api_resource->set_user_id( $this->api_user_id );
		$api_resource->set_params( $this->get_api_request_params() );
		$endpoint = $api_resource->get_endpoint();

		if( is_wp_error( $endpoint ) ){
			return $endpoint;
		}

		return parent::API_ENDPOINT . $endpoint;
	}

	/**
	 * Returns reference for resource
	 * @return Resource_Interface|Resource_Abstract
	 */
	public function get_api_resource() {
		return Resource_Objects::instance()->get_resource( $this->resource_type );
	}

	/**
	 * Returns request parameters
	 *
	 * @return array
	 */
	public function get_api_request_params(){
		if( $this->get_api_resource()->is_single_entry() ){
			return $this->params;
		}

		$sort_option = Resource_Objects::instance()->get_sort_option( false );

		if( isset( $this->params['order'] ) ){
			$sort_option = Resource_Objects::instance()->get_sort_option( $this->params['order'] );
		}else{
			$_options = $this->get_api_resource()->get_default_params();

			if( isset( $_options['sort'] ) && isset( $_options['direction'] ) ){
				$sort_option = [
					'sort' => $_options['sort'],
					'direction' => $_options['direction']
				];
			}
		}

		return array_merge( $this->params, $sort_option );
	}
}