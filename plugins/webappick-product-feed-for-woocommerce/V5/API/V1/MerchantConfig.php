<?php

namespace CTXFeed\V5\API\V1;

use CTXFeed\V5\API\RestController;
use CTXFeed\V5\Helper\FeedHelper;
use CTXFeed\V5\Merchant\TemplateConfig;
use CTXFeed\V5\Utility\Config;
use WP_REST_Server;

//TODO: Custom Template 2 React/Js Output Rendering test.
/**
 * Class MerchantConfig
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\API
 * @author     Azizul Hasan <azizulhasan.cr@gmail.com>
 * @link       https://azizulhasan.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 */
class MerchantConfig extends RestController {
	/**
	 * The single instance of the class
	 *
	 * @var MerchantConfig
	 *
	 */
	protected static $_instance = null;

	private function __construct() {
		parent::__construct();
		$this->rest_base = 'merchant_config';
	}

	/**
	 * Main MerchantConfig Instance.
	 *
	 * Ensures only one instance of MerchantConfig is loaded or can be loaded.
	 *
	 * @return MerchantConfig Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Register routes.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				/**
				 * @endpoint: wp-json/ctxfeed/v1/merchant_config/?feed=wf_feed_google_shopping_33&type=edit
				 * @method GET
				 * @description  It used for editing. Will get feed configuration based $type and $feed file name.
				 *
				 * @param $feed String feed file name
				 * @param $type String feed type edit/add
				 *
				 *
				 * @endpoint wp-json/ctxfeed/v1/merchant_config/?merchant=google&type=get
				 * @method GET
				 * @description  It used for adding. Will get feed d configuration based $merchant name and $type.
				 *
				 * @param $type String feed type edit/get
				 * @param $merchant String merchant name
				 *
				 */
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_item' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
						'merchant' => [
							'description'       => __( 'Merchant name', 'woo-feed' ),
							'type'              => 'string',
							'required'          => false,
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						],
						'type'     => [
							'description'       => __( 'Edit or Get', 'woo-feed' ),
							'type'              => 'string',
							'required'          => true,
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						],
						'feed'     => [
							'description'       => __( 'Feed file name', 'woo-feed' ),
							'type'              => 'string',
							'required'          => false,
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						]
					],
				],
				/**
				 * @endpoint wp-json/ctxfeed/v1/merchant_config
				 * @method POST
				 * @body json array config file name as key and config as value.
				 */
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'create_item' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [],
				],
			]
		);
	}

	/**
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function get_item( $request ) {
		$merchant = $request->get_param( 'merchant' );
		/**
		 * Configuration type Example:  get/edit
		 * if add : default merchant config will be return.
		 * else : current feed files config will be returned.
		 */
		$type = $request->get_param( 'type' );
		if ( 'get' === $type ) {
			// Get Feed merchant configuration like : itemWrapper, itemsWrapper, delimiter, extraHeader etc.
			$defaultMerchantConfig             = TemplateConfig::get( $merchant );
			$defaultMerchantConfig['provider'] = $merchant;
			$merchant                          = new Config( $defaultMerchantConfig );
			$merchant_config                   = $merchant->get_config();

			return $this->success( $merchant_config );

		} elseif ( 'edit' === $type ) {
			$feedName = $request->get_param( 'feed' );
			// True if feed name is missing
			if ( ! $feedName ) {
				return $this->error( __( 'Feed file name missing!', 'woo-feed' ) );
			}

			$feedName = FeedHelper::get_feed_option_name( $feedName );
			$feedInfo = get_option( 'wf_config' . $feedName, false );

			if ( $feedInfo ) {
				return $this->success( $feedInfo );
			} else {
				return $this->error( sprintf( __( 'No configuration found with this feed name: %s', 'woo-feed' ), $feedName ) );
			}
		}

		return $this->error( __( 'Type must be either edit/add.', 'woo-feed' ) );
	}

	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function create_item( $request ) {
		$option_names = [];
		$body         = $request->get_body();
		$body         = json_decode( $body );
		// Save option name.
		foreach ( $body as $option_name => $value ) {
			$value = (array) $value;
			update_option( $option_name, maybe_serialize( $value ) );
			array_push( $option_names, $option_name );
		}
		// Get option name.
		foreach ( $option_names as $option_name ) {
			$data[ $option_name ] = maybe_unserialize( get_option( $option_name, false ) );
		}

		return $this->success( $data );
	}
}
