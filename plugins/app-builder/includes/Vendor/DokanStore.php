<?php


/**
 * class DokanStore
 *
 * @link       https://appcheap.io
 * @since      1.0.13
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Vendor;

defined( 'ABSPATH' ) || exit;

use WC_Countries;
use WP_Error;
use WP_HTTP_Response;
use WP_REST_Response;

class DokanStore extends BaseStore {
	/**
	 * Register route.
	 */
	public function register_routes() {
		parent::register_routes();
		add_filter( 'woocommerce_rest_prepare_product_object', array(
			$this,
			'woocommerce_rest_prepare_product_object'
		), 11, 3 );
		add_filter( 'woocommerce_rest_product_object_query', array(
			$this,
			'enable_vendor_on_list_product_query'
		), 10, 2 );
	}

	/**
	 * Get stores
	 *
	 * @param $request
	 *
	 * @return object|WP_Error|WP_REST_Response
	 * @since 1.0.12
	 *
	 */
	public function get_stores( $request ) {
		$params = $request->get_params();

		$args = [
			'number' => (int) $params['per_page'],
			'offset' => (int) ( $params['page'] - 1 ) * $params['per_page'],
			'status' => 'all'
		];

		if ( ! empty( $params['search'] ) ) {
			$args['search']         = '*' . sanitize_text_field( ( $params['search'] ) ) . '*';
			$args['search_columns'] = [ 'user_login', 'user_email', 'display_name', 'user_nicename' ];
		}

		if ( ! empty( $params['status'] ) ) {
			if ( is_array( $params['status'] ) ) {
				$args['status'] = array_map( 'sanitize_text_field', $params['status'] );
			} else {
				$args['status'] = sanitize_text_field( $params['status'] );
			}
		}

		if ( ! empty( $params['orderby'] ) ) {
			$args['orderby'] = sanitize_sql_orderby( $params['orderby'] );
		}

		if ( ! empty( $params['order'] ) ) {
			$args['order'] = sanitize_text_field( $params['order'] );
		}

		if ( ! empty( $params['featured'] ) ) {
			$args['featured'] = sanitize_text_field( $params['featured'] );
		}

		$args = apply_filters( 'dokan_rest_get_stores_args', $args, $request );

		$stores = dokan()->vendor->get_vendors( $args );

		$search_text = isset( $params['search'] ) ? sanitize_text_field( $params['search'] ) : '';

		// if no stores found in then we are searching again with meta value. here need to remove search and search_columns, because with this args meta_query is not working
		if ( ! count( $stores ) && ! empty( $search_text ) ) {
			unset( $args['search'] );
			unset( $args['search_columns'] );

			$args['meta_query'] = [
				[
					'key'     => 'dokan_store_name',
					'value'   => $search_text,
					'compare' => 'LIKE',
				],
			];

			$stores = dokan()->vendor->get_vendors( $args );
		}

		$data_objects = [];

		foreach ( $stores as $store ) {
			$stores_data    = $this->prepare_item_for_response( $store, $request );
			$data_objects[] = $this->prepare_response_for_collection( $stores_data );
		}

		$response = rest_ensure_response( $data_objects );
		$response = $this->format_collection_response( $response, $request, dokan()->vendor->get_total() );

		return $response;
	}

	/**
	 * Prepare a single user output for response
	 *
	 * @param $store
	 * @param \WP_REST_Request $request Request object.
	 * @param array $additional_fields (optional)
	 *
	 * @return WP_REST_Response $response Response data.
	 */
	public function prepare_item_for_response( $store, $request, $additional_fields = [] ) {
		$data = $store->to_array();

		$data                   = array_merge( $data, apply_filters( 'dokan_rest_store_additional_fields', $additional_fields, $store, $request ) );
		$data['vendor_address'] = $this->get_address_string( $data );

		$rating = $store->get_rating();

		$data['rating'] = array(
			'rating' => intval( $rating['rating'] ),
			'count'  => intval( $rating['count'] ),
			'avg'    => intval( $rating['rating'] ),
		);

		$response = rest_ensure_response( $data );
		$response->add_links( $this->prepare_links( $data, $request ) );

		return apply_filters( 'dokan_rest_prepare_store_item_for_response', $response );
	}

	/**
	 * Format item's collection for response
	 *
	 *
	 * @param object $response
	 * @param object $request
	 * @param int $total_items
	 *
	 * @return object
	 */
	public function format_collection_response( $response, $request, $total_items ) {
		// Store pagination values for headers then unset for count query.
		$per_page  = (int) ( ! empty( $request['per_page'] ) ? $request['per_page'] : 20 );
		$page      = (int) ( ! empty( $request['page'] ) ? $request['page'] : 1 );
		$max_pages = ceil( $total_items / $per_page );

		if ( function_exists( 'dokan_get_seller_status_count' ) && current_user_can( 'manage_woocommerce' ) ) {
			$counts = dokan_get_seller_status_count();
			$response->header( 'X-Status-Pending', (int) $counts['inactive'] );
			$response->header( 'X-Status-Approved', (int) $counts['active'] );
			$response->header( 'X-Status-All', (int) $counts['total'] );
		}

		$response->header( 'X-WP-Total', (int) $total_items );
		$response->header( 'X-WP-TotalPages', (int) $max_pages );

		if ( $total_items === 0 ) {
			return $response;
		}

		$base = add_query_arg( $request->get_query_params(), rest_url( sprintf( '/%s/%s', $this->namespace, $this->base ) ) );

		if ( $page > 1 ) {
			$prev_page = $page - 1;

			if ( $prev_page > $max_pages ) {
				$prev_page = $max_pages;
			}

			$prev_link = add_query_arg( 'page', $prev_page, $base );
			$response->link_header( 'prev', $prev_link );
		}

		if ( $max_pages > $page ) {
			$next_page = $page + 1;
			$next_link = add_query_arg( 'page', $next_page, $base );
			$response->link_header( 'next', $next_link );
		}

		return $response;
	}

	/**
	 * Prepare links for the request.
	 *
	 * @param \WC_Data $object Object data.
	 * @param \WP_REST_Request $request Request object.
	 *
	 * @return array                   Links for the given post.
	 */
	protected function prepare_links( $object, $request ) {
		$links = [
			'self'       => [
				'href' => rest_url( sprintf( '/%s/%s/%d', $this->namespace, $this->rest_base, $object['id'] ) ),
			],
			'collection' => [
				'href' => rest_url( sprintf( '/%s/%s', $this->namespace, $this->rest_base ) ),
			],
		];

		return $links;
	}

	/**
	 * Get the shop address
	 *
	 * @return array
	 */
	public function get_address_string( $vendor_data ) {
		$address = isset( $vendor_data['address'] ) ? $vendor_data['address'] : '';
		$addr_1  = isset( $vendor_data['address']['street_1'] ) ? $vendor_data['address']['street_1'] : '';
		$addr_2  = isset( $vendor_data['address']['street_2'] ) ? $vendor_data['address']['street_2'] : '';
		$city    = isset( $vendor_data['address']['city'] ) ? $vendor_data['address']['city'] : '';
		$zip     = isset( $vendor_data['address']['zip'] ) ? $vendor_data['address']['zip'] : '';
		$country = isset( $vendor_data['address']['country'] ) ? $vendor_data['address']['country'] : '';
		$state   = isset( $vendor_data['address']['state'] ) ? $vendor_data['address']['state'] : '';

		// Country -> States
		$country_obj  = new WC_Countries();
		$countries    = $country_obj->countries;
		$states       = $country_obj->states;
		$country_name = '';
		$state_name   = '';
		if ( $country ) {
			$country_name = $country;
		}
		if ( $state ) {
			$state_name = $state;
		}
		if ( $country && isset( $countries[ $country ] ) ) {
			$country_name = $countries[ $country ];
		}
		if ( $state && isset( $states[ $country ] ) && is_array( $states[ $country ] ) ) {
			$state_name = isset( $states[ $country ][ $state ] ) ? $states[ $country ][ $state ] : '';
		}

		$store_address = '';
		if ( $addr_1 ) {
			$store_address .= $addr_1 . ", ";
		}
		if ( $addr_2 ) {
			$store_address .= $addr_2 . ", ";
		}
		if ( $city ) {
			$store_address .= $city . ", ";
		}
		if ( $state_name ) {
			$store_address .= $state_name;
		}
		if ( $country_name ) {
			$store_address .= " " . $country_name;
		}
		if ( $zip ) {
			$store_address .= " - " . $zip;
		}

		$store_address = str_replace( '"', '&quot;', $store_address );

		return apply_filters( 'dokan_store_address_string', $store_address, $vendor_data );

	}

	/**
	 * Prepare object for product response
	 *
	 * @return void
	 */
	public function woocommerce_rest_prepare_product_object( $response, $object, $request ) {
		$data      = $response->get_data();
		$author_id = get_post_field( 'post_author', $data['id'] );

		$store = dokan()->vendor->get( $author_id );

		$store_data = $this->prepare_item_for_response( $store, $request );

		$data['store'] = $this->prepare_response_for_collection( $store_data );

		$response->set_data( $data );

		return $response;
	}

	/**
	 *
	 * Enable filter product by vendor
	 *
	 * @param $args
	 * @param $request
	 *
	 * @return mixed
	 */
	function enable_vendor_on_list_product_query( $args, $request ) {
		$args['author']         = isset( $request['vendor'] ) ? $request['vendor'] : '';
		$args['author__in']     = isset( $request['include_vendor'] ) ? $request['include_vendor'] : '';
		$args['author__not_in'] = isset( $request['exclude_vendor'] ) ? $request['exclude_vendor'] : '';

		return $args;
	}

	/**
	 *
	 * Get store categories
	 *
	 * @param $request
	 *
	 * @return object|WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_store_categories( $request ) {

		$store_id = (int) $request->get_param( 'id' );

		$vendor      = dokan()->vendor->get( $store_id );
		$products    = $vendor->get_products();
		$product_ids = [];

		foreach ( $products->posts as $product ) {
			array_push( $product_ids, $product->ID );
		}

		// Hold all the terms
		$all_terms = [];
		foreach ( $product_ids as $product_id ) {
			$terms = get_the_terms( $product_id, 'product_cat' );

			//allow when there is terms and do not have any wp_errors
			if ( $terms && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					array_push( $all_terms, $term );
				}
			}
		}

		// Hold unique categories
		$categories = [];
		foreach ( $all_terms as $term ) {
			$id = (int) $term->term_id;
			if ( ! in_array( $id, $categories ) ) {
				$categories[] = $id;
			}
		}

		return rest_ensure_response( $categories );
	}
}
