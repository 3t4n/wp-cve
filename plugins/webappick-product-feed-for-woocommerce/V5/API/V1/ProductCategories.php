<?php

namespace CTXFeed\V5\API\V1;

use CTXFeed\V5\API\RestController;
use \WP_REST_Server;
/**
 * Class ProductCategories
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\API
 * @author     Azizul Hasan <azizulhasan.cr@gmail.com>
 * @link       https://azizulhasan.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 */
class ProductCategories extends RestController {

	/**
	 * The single instance of the class
	 *
	 * @var ProductCategories
	 *
	 */
	protected static $_instance = null;

	private function __construct() {
		parent::__construct();
		$this->rest_base = 'product_categories';
	}

	/**
	 * Main ProductCategories Instance.
	 *
	 * Ensures only one instance of ProductCategories is loaded or can be loaded.
	 *
	 * @return ProductCategories Main instance
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
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				/**
				 * @endpoint wp-json/ctxfeed/v1/product_categories/?search=hoo
				 * @description  will return all categories based on search string.
				 * @param $search String
				 *
				 */
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'search_categories' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
						'search' => [
							'description' => __( 'Search string.', 'woo-feed' ),
							'type'        => 'string',
							'required'    => false
						],
					],
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
	public function search_categories( $request ) {
		$search_string = $request->get_param( 'search' );

		$args = array(
			'taxonomy'   => array( 'product_cat' ),
			'orderby'    => 'id',
			'order'      => 'DESC',
			'hide_empty' => true,
			'fields'     => 'all',
			'name__like' => $search_string,
		);

		$terms = get_terms( $args );

		$this->success( $terms );

		return rest_ensure_response( $this->response );
	}

}
