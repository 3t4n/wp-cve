<?php

namespace CTXFeed\V5\API\V1;

use CTXFeed\V5\API\RestConstants;
use CTXFeed\V5\API\RestController;
use CTXFeed\V5\Helper\CustomFieldHelper;
use CTXFeed\V5\Utility\Cache;
use CTXFeed\V5\Utility\Logs;
use CTXFeed\V5\Utility\Settings as SettingsBase;
use \WP_REST_Server;
use WooFeedWebAppickAPI;
/**
 * Class Settings
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\API
 * @author     Azizul Hasan <azizulhasan.cr@gmail.com>
 * @link       https://azizulhasan.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 */
class Settings extends RestController {
	private static $settings_lists = [];
	/**
	 * @var string
	 */
	private static $option_name = 'woo_feed_settings';
	/**
	 * The single instance of the class
	 *
	 * @var Settings
	 *
	 */
	protected static $_instance = null;

	private function __construct() {
		parent::__construct();
		$this->rest_base = RestConstants::SETTINGS_REST_BASE;
	}

	/**
	 * Main Settings Instance.
	 *
	 * Ensures only one instance of Settings is loaded or can be loaded.
	 *
	 * @return Settings Main instance
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
		// Default settings
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				/**
				 *
				 * @endpoint wp-json/ctxfeed/v1/settings
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
					'callback'            => [ $this, 'get_item' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [],
				],
				[
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'update_item' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [],
				],
				'schema' => [ $this, 'get_item_schema' ],
			],

		);
		// Custom Fields
		register_rest_route(
			$this->namespace, '/' . $this->rest_base . '/custom_settings',
			/**
			 * @endpoint wp-json/ctxfeed/v1/settings/custom_settings
			 * @method GET
			 * @descripton get custom settings
			 *
			 * @param $name String
			 */
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_custom_fields' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [],
				]
			],
		);

		// What we collect
		register_rest_route(
			$this->namespace, '/' . $this->rest_base . '/what_we_collect',
			/**
			 * @endpoint wp-json/ctxfeed/v1/settings/what_we_collect
			 * @method GET
			 * @descripton get data about data collection.
			 *
			 * @param $name String
			 */
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'what_we_collect' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [],
				]
			],
		);
	}

	/**
	 * @param $request \WP_REST_Request request body will be  []
	 *
	 *
	 * @return \WP_Error|\WP_REST_Response|\WP_HTTP_Response
	 */
	public function get_custom_fields( $request ) {
		$custom_fields = CustomFieldHelper::get_fields();

		return $this->success( $custom_fields );
	}

	/**
	 * @param $request
	 *
	 * @return \WP_REST_Response|null
	 */
	public function what_we_collect( $request ) {
		$data = [
			'opt_in'  => WooFeedWebAppickAPI::getInstance()->is_tracking_allowed(),
			'collect' => WooFeedWebAppickAPI::getInstance()->get_data_collection_description(),
		];
		return $this->success( $data );
	}

	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function update_item( $request ) {
		$body = $request->get_body();
		$body = self::json_decode( $body );

		if ( isset( $body['opt_in'] ) && $body['opt_in'] ) {
			WooFeedWebAppickAPI::getInstance()->trackerOptIn();
		} else {
			WooFeedWebAppickAPI::getInstance()->trackerOptOut();
		}


		if ( isset( $body['clear_all_logs'] ) && $body['clear_all_logs'] ) {
			Logs::delete_all_logs();
		}
		if ( isset( $body['purge_feed_cache'] ) && $body['purge_feed_cache'] ) {
			Cache::flush();
		}

		SettingsBase::save( $body );

		self::$settings_lists = SettingsBase::get( 'all' );

		return $this->success( self::$settings_lists );
	}

	private static function json_decode( $body ) {
		$arr = (array) json_decode( $body );
		foreach ( $arr as &$value ) {
			if ( is_object( $value ) ) {
				$value = (array) $value;
			} elseif ( is_string( $value ) ) {
				$value = (string) $value;
			} elseif ( is_array( $value ) ) {
				$value = (array) $value;
			} elseif ( is_int( $value ) ) {
				$value = (int) $value;
			}
		}

		return $arr;
	}

	/**
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function get_item( $request ) {
		self::$settings_lists = SettingsBase::get( 'all' );

		return $this->success( self::$settings_lists );
	}


	/**
	 * Get our sample schema for comments.
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			return $this->add_additional_fields_schema( $this->schema );
		}
		$schema = array(
			// This tells the spec of JSON Schema we are using which is draft 4.
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			// The title property marks the identity of the resource.
			'title'      => 'comment',
			'type'       => 'object',
			// In JSON Schema you can specify object properties in the properties attribute.
			'properties' => array(
				'id'      => array(
					'description' => esc_html__( 'Unique identifier for the object.', 'my-textdomain' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit', 'embed' ),
					'readonly'    => true,
				),
				'author'  => array(
					'description' => esc_html__( 'The id of the user object, if author was a user.', 'my-textdomain' ),
					'type'        => 'integer',
				),
				'content' => array(
					'description' => esc_html__( 'The content for the object.', 'my-textdomain' ),
					'type'        => 'string',
				),
			),
		);

		$this->schema = $schema;

		return $this->add_additional_fields_schema( $this->schema );
	}

}
