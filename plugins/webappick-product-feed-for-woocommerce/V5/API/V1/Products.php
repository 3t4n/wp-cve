<?php

namespace CTXFeed\V5\API\V1;

use CTXFeed\V5\API\RestController;
use CTXFeed\V5\Common\Factory;
use CTXFeed\V5\Filter\FilterInfo;
use CTXFeed\V5\Utility\Config;
use CTXFeed\V5\Query\QueryFactory;
use \WP_REST_Server;
/**
 * Class Products
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\API
 * @author     Azizul Hasan <azizulhasan.cr@gmail.com>
 * @link       https://azizulhasan.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 */
class Products extends RestController {
	/**
	 * The single instance of the class
	 *
	 * @var Products
	 *
	 */
	protected static $_instance = null;

	private function __construct() {
		parent::__construct();
		$this->rest_base = 'products';
	}

	/**
	 * Main ProductTitles Instance.
	 *
	 * Ensures only one instance of ProductTitles is loaded or can be loaded.
	 *
	 * @return Products Main instance
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
		//Search products
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				/**
				 * @endpoint wp-json/ctxfeed/v1/product/?search=hoo
				 * @description  will return all product titles based on search string.
				 *
				 * @param $search String
				 * @method GET
				 *
				 */
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_product_titles' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
						'search' => [
							'description' => __( 'Search string.', 'woo-feed' ),
							'type'        => 'string',
							'required'    => true
						],
					],
				],
			]
		);

		// Get product ids
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/ids',
			[
				/**
				 * @endpoint wp-json/ctxfeed/v1/products/ids
				 * @description  will return all product ids based on query.
				 *
				 * @param $feed String Feed name
				 * @method GET
				 *
				 */
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_product_ids' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [],
				],
			]
		);

		// Out Of Stock products
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/get_filter_data',
			[
				/**
				 * @endpoint wp-json/ctxfeed/v1/products/get_filter_data
				 * @description  will return all out of stock products.
				 *
				 * @param $feed String Feed name
				 * @method GET
				 *
				 */
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_filter_data' ],
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
	public function get_product_titles( $request ) {
		$search_string = $request->get_param( 'search' );

		$data_store     = \WC_Data_Store::load( 'product' );
		$ids            = $data_store->search_products( $search_string, 'product', true, false, 200, [], [] );
		$search_results = [];
		foreach ( $ids as $id ) {
			if ( $id < 1 ) {
				continue;
			}
			$product               = wc_get_product( $id );
			$search_results[ $id ] = strip_tags( $product->get_formatted_name() );
		}

		return $this->success( [ $search_results ] );
	}

	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response|null\
	 */
	public function get_product_ids( $request ) {
		$config = new Config( [] );
		$ids    = QueryFactory::get_ids( $config );
		$ids    = $this->prepare_item_for_response( $ids, $request );

		return $this->success( $ids );
	}

	/**
	 * @param $ids
	 * @param $request
	 *
	 * @return mixed|void|\WP_Error|\WP_REST_Response
	 */
	public function prepare_item_for_response( $ids, $request ) {
		return array_filter( $ids, function ( $id ) {
			return $id > 0;
		} );
	}

	/**
	 * @param $request
	 *
	 * @return mixed|void|\WP_Error|\WP_REST_Response
	 */
	public function get_filter_data( $request ) {

		// TODO these previous methods and current FilterInfo class output is different
		$results = array(
			'product_visibility'     =>  woo_feed_hidden_products_count(),
			'is_emptyPrice'    => woo_feed_no_price_products_count(),
			'is_emptyImage'      => woo_feed_no_image_products_count(),
			'is_emptyDescription'     => woo_feed_no_description_products_count(),
			'is_outOfStock' => woo_feed_out_of_stock_products_count(),
			'is_backorder'  => woo_feed_backorder_products_count(),
		);

//		$results = array(
//			'product_visibility'     =>  FilterInfo::getHiddenProducts() ,
//			'is_emptyPrice'    => FilterInfo::getEmptyPriceProducts(),
//			'is_emptyImage'      => FilterInfo::getEmptyImageProducts(),
//			'is_emptyDescription'     => FilterInfo::getDescriptionProducts(),
//			'is_outOfStock' => FilterInfo::getOutOfStockProducts(),
//			'is_backorder'  => FilterInfo::getBackOrderProducts(),
//		);

		return $this->success( $results );
	}

}
