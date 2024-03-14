<?php
/**
 * Item Request
 *
 * Request item data.
 *
 * @since   1.2.0
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

class ItemRequest extends Request {

	/**
	 * @var string
	 */

	protected $access_token_key = 'wp_data_sync_item_request_access_token';

	/**
	 * @var string
	 */

	protected $private_token_key = 'wp_data_sync_item_request_private_token';

	/**
	 * @var string
	 */

	protected $permissions_key = 'wp_data_sync_allowed';

	/**
	 * @var string
	 */

	private $post_type;

	/**
	 * @var string
	 */

	private $api_id;

	/**
	 * @var integer
	 */

	private $limit;

	/**
	 * @var ItemRequest
	 */

	public static $instance;

	/**
	 * ItemRequest constructor.
	 */

	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * Instance.
	 *
	 * @return ItemRequest
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
			'/' . WPDSYNC_EP_VERSION . '/get-item/(?P<access_token>\S+)/(?P<post_type>\S+)/(?P<limit>\d+)/(?P<api_id>\S+)/',
			[
				'methods' => WP_REST_Server::READABLE,
				'args'    => [
					'access_token' => [
						'sanitize_callback' => 'sanitize_text_field',
						'validate_callback' => [ $this, 'access_token' ]
					],
					'post_type' => [
						'sanitize_callback' => 'sanitize_text_field',
						'validate_callback' => [ $this, 'set_post_type' ]
					],
					'limit' => [
						'sanitize_callback' => 'absint',
						'validate_callback' => [ $this, 'set_limit' ]
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

		$response = $this->get_items();

		Log::write( 'item-request', [
			'api_id'   => $this->api_id,
			'response' => $response
		], 'Response' );

		return rest_ensure_response( $response );

	}

	/**
	 * Check if post type exists.
	 *
	 * @param $post_type
	 *
	 * @return bool
	 */

	public function set_post_type( $post_type ) {

		$this->post_type = sanitize_text_field( $post_type );

		return true;

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

		return true;

	}

	/**
	 * Limit.
	 *
	 * @param $limit
	 *
	 * @return bool
	 */

	public function set_limit( $limit ) {

		$this->limit = intval( $limit );

		return  $this->limit > 0;

	}

	/**
	 * Get items.
	 *
	 * @return mixed
	 */

	public function get_items() {

		if ( $item_ids = $this->item_ids() ) {

			$items = [];

			foreach ( $item_ids as $item_id ) {

				$item = new Item( $item_id );

				$items[] = $item_data = $item->get();

				Log::write( 'item-request', [
					'item_id'   => $item_id,
					'item_data' => $item_data
				], 'Item Data' );

				$this->insert_id( $item_id );

			}

			return apply_filters( 'wp_data_sync_get_items_response', $items );

		}

		return false;

	}

	/**
	 * Get the item IDs.
	 *
	 * @since 1.0.0
	 *        2.0.4 Add filters to SQL statements.
	 *
	 * @return bool|mixed
	 */

	public function item_ids() {

		global $wpdb;

		$table = self::table();

		/**
		 * SELECT statement.
		 */

		$select = "
			SELECT SQL_NO_CACHE SQL_CALC_FOUND_ROWS  p.ID 
			FROM {$wpdb->prefix}posts p
		";

		/**
		 * JOIN statement.
		 */

		$join = apply_filters( 'wp_data_sync_item_request_sql_join', $wpdb->prepare(
			"
			LEFT JOIN $table i
			ON (p.ID = i.item_id AND i.api_id = %s)
			",
			esc_sql( $this->api_id )
		), $this->post_type );

		/**
		 * WHERE statement
		 */

		$status       = get_option( 'wp_data_sync_item_request_status', [ 'publish' ] );
		$count        = count( $status );
		$placeholders = join( ', ', array_fill( 0, $count, '%s' ) );
		$where_args   = array_merge(
			[ esc_sql( $this->post_type ) ],
			array_map( 'esc_sql', $status )
		);

		$where = apply_filters( 'wp_data_sync_item_request_sql_where', $wpdb->prepare(
			" 
			WHERE i.item_id IS NULL 
			AND p.post_type = %s 
			AND p.post_status IN ( $placeholders )
			",
			$where_args
		), $this->post_type );

		/**
		 * ORDER BY statement
		 */

		$order_by = apply_filters( 'wp_data_sync_item_request_sql_order_by', "ORDER BY p.ID DESC", $this->post_type );

		/**
		 * LIMIT statement
		 */

		$limit = apply_filters( 'wp_data_sync_item_request_sql_limit', $wpdb->prepare(
			"LIMIT %d",
			$this->limit
		), $this->limit, $this->post_type );

		/**
		 * Combine parts to make the SQL statement.
		 */

		$sql = "$select $join $where $order_by $limit";

		$item_ids = $wpdb->get_col( $sql );

		Log::write( 'item-request',[
			'sql'      => $sql,
			'item_ids' => $item_ids
		], 'SQL Query' );

		$wpdb->flush();

		if ( empty( $item_ids ) || is_wp_error( $item_ids ) ) {
			return false;
		}

		return array_map( 'intval', $item_ids );

	}

	/**
	 * Insert Item ID.
	 *
	 * @param $item_id
	 */

	public function insert_id( $item_id ) {

		global $wpdb;

		$wpdb->insert(
			self::table(),
			[
				'item_id' => $item_id,
				'api_id'  => $this->api_id
			]
		);

	}

	/**
	 * Delete Item ID.
	 *
	 * @param $item_id
	 */

	public static function delete_id( $item_id ) {

		global $wpdb;

		$wpdb->delete(
			self::table(),
			[ 'item_id' => $item_id ]
		);

	}

	/**
	 * DB Table name.
	 *
	 * @return string
	 */

	private static function table() {

		global $wpdb;

		return $wpdb->prefix . 'data_sync_item_request';

	}

	/**
	 * Has synced.
	 *
	 * @return bool
	 */

	public static function has_synced() {

		global $wpdb;

		$table = self::table();

		$has_synced = $wpdb->get_var( $wpdb->prepare(
			"
			SELECT id 
			FROM $table
			WHERE item_id = %d
			",
			get_the_id()
		) );

		if ( null === $has_synced || is_wp_error( $has_synced ) ) {
			return false;
		}

		return true;

	}

	/**
	 * Create the item request table.
	 */

	public static function create_table() {

		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$charset_collate = $wpdb->get_charset_collate();
        $table           = self::table();

		$sql = "
			CREATE TABLE IF NOT EXISTS $table (
  			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  			item_id bigint(20) NOT NULL,
  			api_id varchar(100) NOT NULL,
  			PRIMARY KEY (id),
			KEY item_id (item_id)
			) $charset_collate;
        ";

		dbDelta( $sql );

	}

}
