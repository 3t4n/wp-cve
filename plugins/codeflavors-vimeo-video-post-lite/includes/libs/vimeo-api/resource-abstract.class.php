<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Resource_Abstract
 * @package Vimeotheque\Vimeo_Api
 * @ignore
 */
class Resource_Abstract implements Resource_Interface {
	/**
	 * The resource ID (album ID, channel ID, ...)
	 *
	 * @var string
	 */
	protected $resource_id;

	/**
	 * Vimeo user ID
	 *
	 * @var bool|string
	 */
	protected $user_id;

	/**
	 * Request parameters
	 *
	 * @var array
	 */
	protected $params;

	/**
	 * Default params
	 *
	 * @var array
	 */
	protected $default_params = [];

	/**
	 * Stores extra fields required by concrete implementations
	 *
	 * @var array
	 */
	protected $request_fields = [];

	/**
	 * @var string
	 */
	protected $request_method = 'GET';

	/**
	 * Set sorting options
	 *
	 * @var array
	 */
	private $sort_options = [];

	/**
	 * Results filtering options
	 *
	 * @var array
	 */
	private $filtering_options = [];

	/**
	 * Output name for the resource
	 *
	 * @var string
	 */
	private $output_name = '';

	/**
	 * @var string
	 */
	private $name;

	/**
	 * Resource_Abstract constructor.
	 *
	 * @param $resource_id
	 * @param bool $user_id
	 * @param array $params
	 */
	public function __construct( $resource_id, $user_id = false, $params = [] ) {
		$this->resource_id = $resource_id;
		$this->user_id = $user_id;
		$this->params = $params;
	}

	/**
	 * @param string $resource_id
	 */
	public function set_resource_id( $resource_id ) {
		$this->resource_id = $resource_id;
	}

	/**
	 * @param bool|string $user_id
	 */
	public function set_user_id( $user_id ) {
		$this->user_id = $user_id;
	}

	/**
	 * @param array $params
	 */
	public function set_params( $params ) {
		$this->params = $params;
	}

	/**
	 * Set the remote requets method to be used
	 *
	 * @param string $method    Method to be used (ie. GET, POST, PATCH, DELETE)
	 */
	public function set_request_method( $method ){
		$allowed = ['GET', 'HEAD', 'POST', 'PATCH', 'PUT', 'DELETE'];
		$_method = strtoupper( $method );
		if( !in_array( $_method, $allowed ) ){
			trigger_error(
				sprintf(
					'Request method %s is not allowed. Use one of the following: %s.',
					$method,
					implode( ', ', $allowed )
				),
				E_USER_WARNING
			);
		}

		$this->request_method = strtoupper( $method );
	}

	/**
	 * Set default params.
	 *
	 * @param $params
	 */
	protected function set_default_params( $params ){
		$this->default_params = $params;
	}

	/**
	 * Set results sorting options
	 *
	 * @param $sort_options
	 */
	protected function set_sort_options( $sort_options ){
		$this->sort_options = $sort_options;
	}

	/**
	 * @param $filtering_options
	 */
	protected function set_filtering_options( $filtering_options ){
		$this->filtering_options = $filtering_options;
	}

	/**
	 * @see Resource_Interface::get_endpoint()
	 *
	 * Return endpoint URI
	 *
	 * @return string|\WP_Error
	 */
	public function get_endpoint(){
		$_params = $this->get_default_params();
		foreach ( $_params as $k => $v ){
			if( isset( $this->params[ $k ] ) ){
				$_params[ $k ] = $this->params[ $k ];
			}

			if( is_string( $_params[ $k ] ) && empty( $_params[ $k ] ) ){
				unset( $_params[ $k ] );
			}
		}

		// Concrete implementation method returns false for all fields; check that fields are specified
        $_params['fields'] = $this->get_fields() ? implode( ',', $this->get_fields() ) : '';

		if( isset( $_params['sort'] ) && !in_array( $_params['sort'], $this->sort_options ) ){
			return new \WP_Error(
				'cvm-unknown-sort-options',
				sprintf(
					__('Sort option "%s" is not available in feed resource.'),
					$this->params['sort']
				)
			);
		}

		// unset query parameter to avoid empty answers from the API
		if( isset( $_params['query'] ) && empty( $_params['query'] ) ){
			unset( $_params['query'] );
		}

		// if direction parameter is false, the sorting doesn't need the direction parameter
		if( isset( $_params['direction'] ) && false === $_params['direction'] ){
			unset( $_params['direction'] );
		}

		/**
		 * Filter API query params.
		 *
		 * @param array $_params    Request parameters.
		 */
		$_params = apply_filters( 'vimeotheque\vimeo_api\query_params', $_params );

		if( !$this->get_api_endpoint() ){
			return new \WP_Error(
				'vimeotheque-vimeo-api-resource-endpoint-error',
				'Plugin error occurred! Method ' . get_class( $this ) . '::get_api_endpoint() returned an empty response.'
			);
		}

		return $this->get_api_endpoint() . ( $_params['fields'] ? '?' . http_build_query( $_params ) : '' );
	}

	/**
	 *
	 * For all available fields see: https://developer.vimeo.com/api/reference/responses/video
	 *
	 * If concrete implementation returns false,
	 *
	 * @return array
	 */
	protected function get_fields(){

		$fields = [
			'categories',
			'content_rating',
			'created_time',
			'description',
			'duration',
			'height',
			'link',
			'player_embed_url',
			'modified_time',
			'name',
			'pictures',
			'privacy',
			'release_time',
			'stats',
			'tags',
			'type',
			'uri',
			'user',
			'width',
			'metadata.connections.comments.total',
			'metadata.connections.likes.total'
		];

		$optional = $this->get_optional_fields();
		$f = array_unique(
				array_merge(
					$fields,
					$this->request_fields,
					$optional
				),
				SORT_STRING );

		return $f;
	}

	/**
	 * Set resource output name
	 *
	 * @param string $name
	 * @param string $output_name
	 */
	protected function set_name( $name, $output_name ){
		$this->name = $name;
		$this->output_name = $output_name;
	}

	/**
	 * Allows concrete implementations to add extra fields
	 *
	 * @param array $fields
	 *
	 * @return array
	 */
	protected function set_fields( $fields = [] ){
		return $this->request_fields = $fields;
	}

	/**
	 * @see Resource_Interface::get_optional_fields()
	 *
	 * Optionl additional fields that can be set by third party scripts
	 *
	 * @return array
	 */
	public function get_optional_fields(){
		/**
		 * Filter that allows setup of additional JSON fields in Vimeo API requests.
		 *
		 * @see https://developer.vimeo.com/api/reference/responses/video
		 *
		 * @param array $fields The additional JSON fields.
		 */
		return apply_filters( 'vimeotheque\vimeo_api\add_json_fields', [] );
	}

	/**
	 * @see Resource_Interface::get_formatted_entry()
	 *
	 * @param $raw_entry
	 *
	 * @return array
	 */
	public function get_formatted_entry( $raw_entry ){
		$format = new Entry_Format( $raw_entry, $this );
		$formatted = $format->get_entry();

		return $formatted;
	}

	/**
	 * @see Resource_Interface::is_single_entry()
	 *
	 * Registers the resource as a single entry query; single entries should not be
	 * displayed in front-end resource query pages, like video import page or automatic
	 * import.
	 *
	 * Can be overridden in concrete classes
	 *
	 * @return bool
	 */
	public function is_single_entry(){
		return false;
	}

	/**
	 * If the resource is not a single entry resource and mustn't be displayed
	 * into the importers feed types option, concrete implementation must return
	 * false.
	 *
	 * @return bool
	 */
	public function enabled_for_importers(){
		return true;
	}

	/**
	 * @see Resource_Interface::has_automatic_import()
	 *
	 * Feed can be proccessed by automatic import.
	 * Return true in concrete implementation if it can be processed.
	 *
	 * Can be overridden in concrete class
	 *
	 * @return bool
	 */
	public function has_automatic_import() {
		return true;
	}

	/**
	 * @see Resource_Interface::can_import_new_videos()
	 *
	 * After processing the entire feed, only new videos can be imported.
	 * Feed will be parsed once and all future queries will only check for new videos.
	 * Return true in concrete implementation if this applies to feed.
	 *
	 * @return bool
	 */
	public function can_import_new_videos() {
		return false;
	}

	/**
	 * @see Resource_Interface::has_date_limit()
	 *
	 * Feed can have a date limit when processing imports.
	 * When true, this signals that the feed can be imported
	 * up to a certain given date in past beyond which videos
	 * will be ignored from importing
	 *
	 * @return bool
	 */
	public function has_date_limit(){
		return false;
	}

	/**
	 * Return true in concrete implementation if feed requires authorization to work (ie. folders feed type).
	 *
	 * @return bool
	 */
	public function requires_authorization(){
		return false;
	}

	/**
	 * @return array
	 */
	public function get_default_params() {
		return $this->default_params;
	}

	/**
	 * @see Resource_Interface::get_output_name()
	 *
	 * @return string
	 */
	public function get_output_name(){
		return $this->output_name;
	}

	/**
	 * @see Resource_Interface::get_name()
	 *
	 * Return ID name
	 *
	 * @return string
	 */
	public function get_name(){
		return $this->name;
	}

	/**
	 * @return array
	 */
	public function get_sort_options() {
		return $this->sort_options;
	}

	/**
	 * @return array
	 */
	public function get_filtering_options() {
		return $this->filtering_options;
	}

	/**
	 * @see Resource_Interface::get_api_endpoint()
	 *
	 * Return resource relative API endpoint
	 *
	 * @return string
	 */
	public function get_api_endpoint() {
		_doing_it_wrong( __FUNCTION__, 'Method must be implemented in child class' );
	}

	/**
	 * @see Resource_Interface::requires_user_id()
	 *
	 * Used to retrieve whether feed needs Vimeo user ID to make queries
	 *
	 * @return bool
	 */
	public function requires_user_id() {
		return false;
	}

	/**
	 * @see Resource_Interface::label_user_id()
	 *
	 * Get field label for Vimeo user ID
	 *
	 * @return bool|string
	 */
	public function label_user_id() {
		return false;
	}

	/**
	 * @see Resource_Interface::placeholder_user_id()
	 *
	 * Get placeholder for field Vimeo user ID
	 *
	 * @return bool|string
	 */
	public function placeholder_user_id() {
		return false;
	}

	/**
	 * @see Resource_Interface::can_search_results()
	 *
	 * Most resources allow search within the returned results.
	 * By default, abstract class will assume this is allowed.
	 * Override in child implementation for feeds that do not support results searching
	 *
	 * @return bool
	 */
	public function can_search_results() {
		return true;
	}

	/**
	 * @see Resource_Interface::get_request_method()
	 *
	 * @return string
	 */
	public function get_request_method() {
		return $this->request_method;
	}
}