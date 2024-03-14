<?php
/**
 * REST Controller
 *
 * This class extend `WC_REST_Controller`
 *
 * It's required to follow "Controller Classes" guide before extending this class:
 * <https://developer.wordpress.org/rest-api/extending-the-rest-api/controller-classes/>
 *
 * @class   WC_REST_ListItemIds_Controller
 * @package NovaModule\RestApi
 * @see     https://developer.wordpress.org/rest-api/extending-the-rest-api/controller-classes/
 */

defined( 'ABSPATH' ) || exit;
if ( ! function_exists( 'wc_rest_check_post_permissions' ) ) {
	require_once ABSPATH . 'wp-content/plugins/woocommerce/includes/wc-rest-functions.php';
}

/**
 * REST API list of Item Ids controller class.
 *
 * @package NovaModule\RestApi
 * @extends WC_REST_Controller
 */
class WC_REST_ListItemIds_Controller extends WC_REST_Controller {


	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wc/v3';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'listitemids';

	/**
	 * Register routes.
	 *
	 * @since 3.5.0
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'list_itemids' ),
				'permission_callback' => array( $this, 'get_item_permissions_check' ),
				'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::READABLE ),
			)
		);
	}

	/**
	 * Check if a given request has access to read an item.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_item_permissions_check( $request ) {
		if ( ! wc_rest_check_post_permissions( 'product', 'read' ) ) {
			return new WP_Error( 'woocommerce_rest_cannot_view', __( 'Sorry, you cannot view this resource.', 'woocommerce' ), array( 'status' => rest_authorization_required_code() ) );
		}
		return true;
	}

	/**
	 * Get the list of published Items.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response $response
	 */
	public function list_itemids( WP_REST_Request $request ) {
		$reqdata  = $request->get_query_params();
		$per_page = 100;
		$page     = 1;
		if ( isset( $reqdata['per_page'] ) && is_integer( intval( $reqdata['per_page'] ) ) ) {
			$per_page = intval( $reqdata['per_page'] );
		}
		if ( isset( $reqdata['page'] ) && is_integer( intval( $reqdata['page'] ) ) ) {
			$page = intval( $reqdata['page'] );
		}
		$data = $this->getListOfIds( $page, $per_page );

		$response = new WP_REST_Response( $data );
		$response->header( 'x-wp-total', intval( $data['totalRecords'] ) );
		$response->header( 'x-wp-totalpages', intval( $data['totalPages'] ) );

		return $response;
	}

	/**
	 * Get the list of Items based on the page.
	 *
	 * @param Integer $page current page.
	 * @param Integer $per_page number of records per page.
	 * @return WP_Error|boolean
	 */
	private function getListOfIds( $page, $per_page ) {
		 global $wpdb;
		$final_results = array();
		$posttable     = $wpdb->prefix . 'posts';
		$postmetatable = $wpdb->prefix . 'postmeta';
		$offset        = ( $page - 1 ) * $per_page;
		$total         = $wpdb->get_var(
			'SELECT
                         COUNT(DISTINCT a.id)
                         from ' . $wpdb->prefix . 'posts a
                         LEFT JOIN ' . $wpdb->prefix . 'postmeta' . " b
                         on a.id=b.post_id AND b.meta_key = '_sku'
                         LEFT JOIN " . $wpdb->prefix . 'postmeta' . " c
                         on a.post_parent=c.post_id AND c.meta_key = '_sku'
                         WHERE a.post_type IN ('product','product_variation')
                         AND a.post_status = 'publish'"
		);

		$max_num_pages = ceil( $total / $per_page );

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT
					 a.id as ID,
					 b.meta_value as sku,
					 a.post_title AS name,
					 c.meta_value as parent_sku,
					 a.post_parent as parent_id,
					 a.post_type as type
					 from ' . $wpdb->prefix . 'posts  a
					 LEFT JOIN ' . $wpdb->prefix . 'postmeta' . " b
					 on a.id=b.post_id AND b.meta_key = '_sku'
					 LEFT JOIN " . $wpdb->prefix . 'postmeta' . " c
					 on a.post_parent=c.post_id AND c.meta_key = '_sku'
					 WHERE a.post_type IN ('product','product_variation')
					 AND a.post_status = 'publish'
					GROUP BY ID LIMIT %d, %d",
				array( $offset, $per_page )
			)
		);

		foreach ( $results as $key => $value ) {
			$final_results[] = $value;
		}

		return array(
			'records'      => $final_results,
			'totalRecords' => intval( $total ),
			'totalPages'   => intval( $max_num_pages ),
			'currentPage'  => intval( $page ),
			'perPage'      => intval( $per_page ),
		);
	}
}
