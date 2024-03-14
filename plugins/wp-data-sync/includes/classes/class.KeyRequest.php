<?php
/**
 * Key Request
 *
 * Request the registered data keys.
 *
 * @since   1.0.0
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

class KeyRequest extends Request {

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
	 * @var KeyRequest
	 */

	public static $instance;

	/**
	 * KeyRequest constructor.
	 */

	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * Instance.
	 *
	 * @return KeyRequest
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
			'wp-data-sync/' . WPDSYNC_EP_VERSION,
			'/key/(?P<access_token>\S+)/(?P<cache_buster>\S+)/',
			[
				'methods' => WP_REST_Server::READABLE,
				'args'    => [
					'access_token' => [
						'sanitize_callback' => 'sanitize_text_field',
						'validate_callback' => [ $this, 'access_token' ]
					],
					'cache_buster' => [
						'validate_callback' => function( $param ) {
							return is_string( $param );
						}
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

		$response = $this->get_keys();

		Log::write( 'data-key-request-response', $response );

		return rest_ensure_response( $response );

	}

	/**
	 * Get the data keys.
	 *
	 * @return array
	 */

	public function get_keys() {

		global $wpdb;

		$keys =  [
			0 => [
				'heading' => __( 'Post Types', 'wp-data-sync' ),
				'keys'    => $this->get_post_types(),
				'type'    => 'key_value'
			],
			1 => [
				'heading' => __( 'Taxonomies', 'wp-data-sync' ),
				'keys'    => $this->get_taxonomies(),
				'type'    => 'key_value'
			],
			2  => [
				'heading' => __( 'Meta Keys', 'wp-data-sync' ),
				'keys'    => $this->meta_keys( $wpdb->postmeta ),
				'type'    => 'value'
			]
		];

		return apply_filters( 'wp_data_sync_data_keys', $keys );

	}

	/**
	 * Get post types.
	 *
	 * @return array
	 */

	public function get_post_types() {

		$post_types = [];

		foreach ( get_post_types( [], 'object' ) as $post_type ) {

			$post_types[ $post_type->name ] = $post_type->label;

		}

		return $post_types;

	}

	/**
	 * Get taxonomies.
	 *
	 * @return array
	 */

	public function get_taxonomies() {

		$taxonomies = [];

		foreach ( get_taxonomies( [], 'object' ) as $taxonomy ) {

			// Filter WooCommerce product attribute taxonomies.
			if ( class_exists( 'WooCommerce' ) && substr( $taxonomy->name, 0, 3 ) === 'pa_' ) {
				continue;
			}

			$taxonomies[ $taxonomy->name ] = $taxonomy->label;

		}

		return $taxonomies;

	}

	/**
	 * Get all the unique meta keys.
	 *
	 * @param $table
	 *
	 * @return array|object|null
	 */

	public function meta_keys( $table ) {

		global $wpdb;

		$rows = $wpdb->get_results(
			"
			SELECT DISTINCT meta_key 
			FROM $table
			WHERE meta_key NOT IN ( '_edit_last', '_edit_lock' )
			AND meta_key NOT LIKE '_menu_item_%'
			ORDER BY meta_key
			"
		);

		if ( empty( $rows ) || is_wp_error( $rows ) ) {
			return [];
		}

		$meta_keys = [];

		foreach ( $rows as $row ) {

			$meta_keys[] = $row->meta_key;
		}

		return $meta_keys;

	}

}
