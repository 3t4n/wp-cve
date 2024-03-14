<?php
namespace CTXFeed\V5\API\V1;

use CTXFeed\V5\API\RestConstants;
use CTXFeed\V5\API\RestController;
use CTXFeed\V5\Utility\Docs;
use WP_REST_Server;

/**
 * Class WooFeedDocs
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\API\V1
 * @author     MD RUBEL MIA <rubelcuet10@gmail.com>
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 */
class WooFeedDocs extends RestController {

	/**
	 * The single instance of the class
	 *
	 * @var WooFeedDocs
	 */
	protected static $_instance = null;

	private function __construct() {
		parent::__construct();
		$this->rest_base = RestConstants::WOO_FEED_DOCS_REST_BASE;
	}
	/**
	 * Main WooFeedDocs Instance.
	 *
	 * Ensures only one instance of WPOptions is loaded or can be loaded.
	 *
	 * @return WooFeedDocs Main instance
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
				 * @endpoint wp-json/ctxfeed/v1/wp_options/?name=wf_feed_google_shopping
				 * @method READ
				 * @descripton get woo feed docs
				 */
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'woo_feed_docs' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [],
				],
			],
		);
	}

	/**
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|\WP_REST_Response|\WP_HTTP_Response
	 */
	public function woo_feed_docs(){
		$docs = new Docs();
		$docs_data = $docs->woo_feed_docs();

		return $this->success( $docs_data );
	}

}

