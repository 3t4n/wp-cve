<?php
/**
 * ItemInfoRequest
 *
 * ItemInfoRequest class.
 *
 * @since   2.6.0
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

class ItemInfoRequest extends Request {

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

	private $key;

	/**
	 * @var string
	 */

	private $value;

	/**
	 * @var ItemInfoRequest
	 */

	public static $instance;

	/**
	 * ItemInfoRequest constructor.
	 */

	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * Instance.
	 *
	 * @return ItemInfoRequest
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
			'wp-data-sync',
			'/' . WPDSYNC_EP_VERSION . '/get-item-info/(?P<access_token>\S+)/(?P<key>\S+)/(?P<value>\s+)/(?P<api_id>\S+)/',
			[
				'methods' => WP_REST_Server::READABLE,
				'args'    => [
					'access_token' => [
						'sanitize_callback' => 'sanitize_text_field',
						'validate_callback' => [ $this, 'access_token' ]
					],
					'key' => [
						'sanitize_callback' => 'sanitize_text_field',
						'validate_callback' => [ $this, 'set_key' ]
					],
					'value' => [
						'sanitize_callback' => 'sanitize_text_field',
						'validate_callback' => [ $this, 'set_value' ]
					],
					'api_id' => [
						'sanitize_callback' => 'sanitize_text_field',
						'validate_callback' => [ $this, 'set_api_id' ]
					]
				],
				'permission_callback' => [ $this, 'access' ],
				'callback'            => [ $this, 'request' ],
			]
		);

	}

	/**
	 * Process the request.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */

	public function request() {

		$response = $this->get_item_info();

		Log::write( 'item-info-request', [
			'api_id'   => $this->api_id,
			'key'      => $this->key,
			'value'    => $this->value,
			'response' => $response
		], 'Response' );

		return rest_ensure_response( $response );

	}

	/**
	 * Set Key
	 *
	 * @param string $key
	 *
	 * @return bool
	 */

	public function set_key( $key ) {

		$this->key = sanitize_text_field( $key );

		return is_string( $this->key );

	}

	/**
	 * Set API ID.
	 *
	 * @param $api_id
	 *
	 * @return bool
	 */

	public function set_api_id( $api_id ) {

		$api_id = sanitize_text_field( $api_id );

		$this->api_id = strstr( $api_id, '~', true );

		return is_string( $this->api_id );

	}

	/**
	 * Set Value
	 *
	 * @param string $value
	 *
	 * @return bool
	 */

	public function set_value( $value ) {

		$this->value = intval( $value );

		return  is_string( $this->value );

	}

	/**
	 * Get Item Info.
	 *
	 * @return string|void|Item
	 */

	public function get_item_info() {

		if ( ! $item_id = $this->fetch_item_id() ) {
			return __( 'Item does not exist!!', 'wp-daya-sync' );
		}

		$item = new Item( $item_id );

		return $item->get();

	}

	/**
	 * Fetch Item ID.
	 *
	 * @return bool|int
	 */

	public function fetch_item_id() {

		global $wpdb;

		$item_id = $wpdb->get_var( $wpdb->prepare(
			"
			SELECT p.ID 
    		FROM $wpdb->posts p
			INNER JOIN $wpdb->postmeta pm
				ON p.ID = pm.post_id
    		WHERE pm.meta_key = %s 
      			AND pm.meta_value = %s 
      		ORDER BY p.ID DESC
			",
			esc_sql( $this->key ),
			esc_sql( $this->value )
		) );

		if ( empty( $item_id ) || is_wp_error( $item_id ) ) {
			return false;
		}

		return (int) $item_id;

	}

}
