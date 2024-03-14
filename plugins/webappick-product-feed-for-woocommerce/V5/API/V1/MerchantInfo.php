<?php

namespace CTXFeed\V5\API\V1;

namespace CTXFeed\V5\API\V1;

use CTXFeed\V5\API\RestController;
use CTXFeed\V5\Merchant\TemplateInfo;
use WP_REST_Server;
/**
 * Class MerchantInfo
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\API
 * @author     Azizul Hasan <azizulhasan.cr@gmail.com>
 * @link       https://azizulhasan.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 */
class MerchantInfo extends RestController {

	/**
	 * The single instance of the class
	 *
	 * @var MerchantInfo
	 *
	 */
	protected static $_instance = null;

	private function __construct() {
		parent::__construct();
		$this->rest_base = 'merchant_info';
	}

	/**
	 * Main MerchantInfo Instance.
	 *
	 * Ensures only one instance of MerchantInfo is loaded or can be loaded.
	 *
	 * @return MerchantInfo Main instance
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

		// Single merchant info
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				/**
				 * @endpoint wp-json/ctxfeed/v1/merchant_info/?merchant=google
				 * @description  Get Feed merchant information like : Docs, Supported file types etc.
				 * @param $merchant String merchant name
				 *
				 */
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_merchant_info' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
						'merchant' => [
							'description' => __( 'Merchant name' ),
							'type'        => 'string',
							'required'    => true
						],
					],
				],
			]
		);


		// multiple merchant info
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/get_initial_merchant_infos',
			[
				/**
				 * @endpoint wp-json/ctxfeed/v1/merchant_info/get_initial_merchant_infos
				 * @description  Get Feed merchant informations like : Docs, Supported file types etc.
				 * @param $merchant String merchant name
				 *
				 */
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'get_initial_merchant_infos' ],
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
	public function get_merchant_info( $request ) {
		$merchant = $request->get_param( 'merchant' );
		/**
		 * Get Feed merchant information like : Docs, Supported file types etc.
		 */
		$merchantInfo = TemplateInfo::get( $merchant );
        $this->response['extra'] = [
            'provider' => $merchant,
        ];

		return $this->success( $merchantInfo );

	}


	/**
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function get_initial_merchant_infos( $request ) {
		$merchants = $request->get_body();
		$merchants = json_decode( $merchants );
		/**
		 * Get Feed merchant information like : Docs, Supported file types etc.
		 */
		$merchantInfos = TemplateInfo::getMultiple( $merchants );

		return $this->success( $merchantInfos );

	}
}
