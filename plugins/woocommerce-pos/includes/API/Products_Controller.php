<?php

namespace WCPOS\WooCommercePOS\API;

\defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'WC_REST_Products_Controller' ) ) {
	return;
}

use Exception;
use WC_Data;
use WC_Product;
use WC_REST_Products_Controller;
use WCPOS\WooCommercePOS\Logger;
use WP_Query;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Products controller class.
 *
 * @NOTE: methods not prefixed with wcpos_ will override WC_REST_Products_Controller methods
 */
class Products_Controller extends WC_REST_Products_Controller {
	use Traits\Product_Helpers;
	use Traits\Uuid_Handler;
	use Traits\WCPOS_REST_API;
	use Traits\Query_Helpers;

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wcpos/v1';

	/**
	 * Allow decimal quantities.
	 *
	 * @var bool
	 */
	protected $allow_decimal_quantities = false;

	/**
	 * Store the current request object.
	 *
	 * @var WP_REST_Request
	 */
	private $wcpos_request;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'woocommerce_pos_rest_dispatch_products_request', array( $this, 'wcpos_dispatch_request' ), 10, 4 );

		if ( method_exists( parent::class, '__construct' ) ) {
			parent::__construct();
		}
	}

	/**
	 * Add custom fields to the product schema.
	 */
	public function get_item_schema() {
		$schema = parent::get_item_schema();

		// Add the 'barcode' property if 'properties' exists and is an array
		if ( isset( $schema['properties'] ) && \is_array( $schema['properties'] ) ) {
			$schema['properties']['barcode'] = array(
				'description' => __( 'Barcode', 'woocommerce-pos' ),
				'type'        => 'string',
				'context'     => array( 'view', 'edit' ),
				'readonly'    => false,
			);
		}

		// Check for 'stock_quantity' and allow decimal
		if ( $this->wcpos_allow_decimal_quantities() &&
			isset( $schema['properties']['stock_quantity'] ) &&
			\is_array( $schema['properties']['stock_quantity'] ) ) {
			$schema['properties']['stock_quantity']['type'] = 'string';
		}

		return $schema;
	}


	/**
	 * Modify the collection params.
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		// Check if 'per_page' parameter exists and has a 'minimum' key before modifying
		if ( isset( $params['per_page'] ) && \is_array( $params['per_page'] ) ) {
			$params['per_page']['minimum'] = -1;
		}

		// Ensure 'orderby' is set and is an array before attempting to modify it
		if ( isset( $params['orderby']['enum'] ) && \is_array( $params['orderby']['enum'] ) ) {
			// Define new sorting options
			$new_sort_options = array(
				'sku',
				'barcode',
				'stock_quantity',
				'stock_status',
			);
			// Merge new options, avoiding duplicates
			$params['orderby']['enum'] = array_unique( array_merge( $params['orderby']['enum'], $new_sort_options ) );
		}

		return $params;
	}

	/**
	 * Dispatch request to parent controller, or override if needed.
	 *
	 * @param mixed           $dispatch_result Dispatch result, will be used if not empty.
	 * @param WP_REST_Request $request         Request used to generate the response.
	 * @param string          $route           Route matched for the request.
	 * @param array           $handler         Route handler used for the request.
	 */
	public function wcpos_dispatch_request( $dispatch_result, WP_REST_Request $request, $route, $handler ) {
		$this->wcpos_request = $request;
		$this->wcpos_register_wc_rest_api_hooks();
		$params = $request->get_params();

		// Optimised query for getting all product IDs
		if ( isset( $params['posts_per_page'] ) && -1 == $params['posts_per_page'] && isset( $params['fields'] ) ) {
			$dispatch_result = $this->wcpos_get_all_posts( $params['fields'] );
		}

		return $dispatch_result;
	}

	/**
	 * Register hooks to modify WC REST API response.
	 */
	public function wcpos_register_wc_rest_api_hooks(): void {
		add_filter( 'woocommerce_rest_prepare_product_object', array( $this, 'wcpos_product_response' ), 10, 3 );
		add_action( 'woocommerce_rest_insert_product_object', array( $this, 'wcpos_insert_product_object' ), 10, 3 );
		add_filter( 'woocommerce_rest_product_object_query', array( $this, 'wcpos_product_query' ), 10, 2 );
		add_filter( 'posts_search', array( $this, 'wcpos_posts_search' ), 10, 2 );
		add_filter( 'posts_clauses', array( $this, 'wcpos_posts_clauses' ), 10, 2 );
	}

	/**
	 * Filter the product response.
	 *
	 * @param WP_REST_Response $response The response object.
	 * @param WC_Product       $product  Product data.
	 * @param WP_REST_Request  $request  Request object.
	 *
	 * @return WP_REST_Response $response The response object.
	 */
	public function wcpos_product_response( WP_REST_Response $response, WC_Product $product, WP_REST_Request $request ): WP_REST_Response {
		$data = $response->get_data();

		// Add the UUID to the product response
		$this->maybe_add_post_uuid( $product );

		// Add the barcode to the product response
		$data['barcode'] = $this->wcpos_get_barcode( $product );

		// Check if the response has an image
		if ( isset( $data['images'] ) && ! empty( $data['images'] ) ) {
			foreach ( $data['images'] as $key => $image ) {
				// Replace the full size 'src' with the URL of the medium size image.
				$image_id = $image['id'];
				$medium_image_data = image_downsize( $image_id, 'medium' );

				if ( $medium_image_data && isset( $medium_image_data[0] ) ) {
						$data['images'][ $key ]['src'] = $medium_image_data[0];
				} else {
						$data['images'][ $key ]['src'] = $image['src'];
				}
			}
		}

		/*
		 * If product is variable, add the max and min prices and add them to the meta data
		 * @TODO - only need to update if there is a change
		 */
		if ( $product->is_type( 'variable' ) ) {
			// Initialize price variables
			$price_array = array(
				'price' => array(
					'min' => $product->get_variation_price(),
					'max' => $product->get_variation_price( 'max' ),
				),
				'regular_price' => array(
					'min' => $product->get_variation_regular_price(),
					'max' => $product->get_variation_regular_price( 'max' ),
				),
				'sale_price' => array(
					'min' => $product->get_variation_sale_price(),
					'max' => $product->get_variation_sale_price( 'max' ),
				),
			);

			// Try encoding the array into JSON
			$encoded_price = wp_json_encode( $price_array );

			// Check if the encoding was successful
			if ( false === $encoded_price ) {
				// JSON encode failed, log the original array for debugging
				Logger::log( 'JSON encoding of price array failed: ' . json_last_error_msg(), $price_array );
			} else {
				// Update the meta data with the successfully encoded price data
				$product->update_meta_data( '_woocommerce_pos_variable_prices', $encoded_price );
			}
		}

		// Make sure we parse the meta data before returning the response
		$product->save_meta_data(); // make sure the meta data is saved
		$data['meta_data'] = $this->wcpos_parse_meta_data( $product );

		// Set any changes to the response data
		$response->set_data( $data );
		// $this->log_large_rest_response( $response, $product->get_id() );

		return $response;
	}

	/**
	 * Fires after a single object is created or updated via the REST API.
	 *
	 * @param WC_Data         $object   Inserted object.
	 * @param WP_REST_Request $request  Request object.
	 * @param bool            $creating True when creating object, false when updating.
	 */
	public function wcpos_insert_product_object( WC_Data $object, WP_REST_Request $request, $creating ): void {
		$barcode_field = $this->wcpos_get_barcode_field();
		if ( $request->has_param( 'barcode' ) ) {
			$barcode = $request->get_param( 'barcode' );
			$object->update_meta_data( $barcode_field, $barcode );
			$object->save_meta_data();
		}
	}

	/**
	 * Filter to adjust the WordPress search SQL query
	 * - Search for the product title and SKU and barcode
	 * - Do not search product description.
	 *
	 * @param string   $search
	 * @param WP_Query $wp_query
	 *
	 * @return string
	 */
	public function wcpos_posts_search( string $search, WP_Query $wp_query ) {
		global $wpdb;

		if ( empty( $search ) ) {
			return $search; // skip processing - no search term in query.
		}

		$q = $wp_query->query_vars;
		$n = ! empty( $q['exact'] ) ? '' : '%';
		$search_terms = (array) $q['search_terms'];

		// Fields in the main 'posts' table.
		$post_fields = array( 'post_title' );

		// Meta fields to search.
		$meta_fields = array( '_sku' );
		$barcode_field = $this->wcpos_get_barcode_field();
		if ( '_sku' !== $barcode_field ) {
			$meta_fields[] = $barcode_field;
		}

		$barcode_field = $this->wcpos_get_barcode_field();
		if ( '_sku' !== $barcode_field ) {
			$fields_to_search[] = $barcode_field;
		}

		$search_conditions = array();

		foreach ( $search_terms as $term ) {
			$term = $n . $wpdb->esc_like( $term ) . $n;

			// Search in post fields
			foreach ( $post_fields as $field ) {
				if ( ! empty( $field ) ) {
					$search_conditions[] = $wpdb->prepare( "($wpdb->posts.$field LIKE %s)", $term );
				}
			}

			// Search in meta fields
			foreach ( $meta_fields as $field ) {
				$search_conditions[] = $wpdb->prepare( '(pm1.meta_value LIKE %s AND pm1.meta_key = %s)', $term, $field );
			}
		}

		if ( ! empty( $search_conditions ) ) {
			$search = ' AND (' . implode( ' OR ', $search_conditions ) . ') ';
			if ( ! is_user_logged_in() ) {
				$search .= " AND ($wpdb->posts.post_password = '') ";
			}
		}

		return $search;
	}

	/**
	 * Filters all query clauses at once, for convenience.
	 *
	 * Covers the WHERE, GROUP BY, JOIN, ORDER BY, DISTINCT,
	 * fields (SELECT), and LIMIT clauses.
	 *
	 * @param string[] $clauses {
	 *                          Associative array of the clauses for the query.
	 *
	 * @var string The WHERE clause of the query.
	 * @var string The GROUP BY clause of the query.
	 * @var string The JOIN clause of the query.
	 * @var string The ORDER BY clause of the query.
	 * @var string The DISTINCT clause of the query.
	 * @var string The SELECT clause of the query.
	 * @var string The LIMIT clause of the query.
	 *             }
	 *
	 * @param WP_Query $wp_query The WP_Query instance (passed by reference).
	 */
	public function wcpos_posts_clauses( array $clauses, WP_Query $wp_query ): array {
		global $wpdb;

		/*
		 * @TODO - If we are ordering by stock_quantity ASC, the NULL values will be at the top of the list.
		 * We need to find a way to order the NULL values at the bottom of the list.
		 */

		return $clauses;
	}

	/**
	 * Filter the query arguments for a request.
	 *
	 * @param array           $args    Key value array of query var to query value.
	 * @param WP_REST_Request $request The request used.
	 *
	 * @return array $args Key value array of query var to query value.
	 */
	public function wcpos_product_query( array $args, WP_REST_Request $request ) {
		if ( ! empty( $request['search'] ) ) {
			// We need to set the query up for a postmeta join.
			add_filter( 'posts_join', array( $this, 'wcpos_posts_join_to_products_search' ), 10, 2 );
			add_filter( 'posts_groupby', array( $this, 'wcpos_posts_groupby_product_search' ), 10, 2 );
		}

		// Check for wcpos_include/wcpos_exclude parameter.
		if ( isset( $request['wcpos_include'] ) || isset( $request['wcpos_exclude'] ) ) {
			// Add a custom WHERE clause to the query.
			add_filter( 'posts_where', array( $this, 'wcpos_posts_where_product_include_exclude' ), 10, 2 );
		}

		return $args;
	}

	/**
	 * Filters the JOIN clause of the query.
	 *
	 * @param string   $join  The JOIN clause of the query.
	 * @param WP_Query $query The WP_Query instance (passed by reference).
	 *
	 * @return string
	 */
	public function wcpos_posts_join_to_products_search( string $join, WP_Query $query ) {
		global $wpdb;

		if ( ! empty( $query->query_vars['s'] ) && false === strpos( $join, 'pm1' ) ) {
			$join .= " LEFT JOIN {$wpdb->postmeta} pm1 ON {$wpdb->posts}.ID = pm1.post_id ";
		}

		return $join;
	}

	/**
	 * Filters the GROUP BY clause of the query.
	 *
	 * @param string   $groupby The GROUP BY clause of the query.
	 * @param WP_Query $query   The WP_Query instance (passed by reference).
	 *
	 * @return string
	 */
	public function wcpos_posts_groupby_product_search( string $groupby, WP_Query $query ) {
		global $wpdb;

		if ( ! empty( $query->query_vars['s'] ) ) {
			$groupby = "{$wpdb->posts}.ID";
		}

		return $groupby;
	}

	/**
	 * Filters the WHERE clause of the query.
	 *
	 * @param string   $where The WHERE clause of the query.
	 * @param WP_Query $query The WP_Query instance (passed by reference).
	 *
	 * @return string
	 */
	public function wcpos_posts_where_product_include_exclude( string $where, WP_Query $query ) {
		global $wpdb;

		// Handle 'wcpos_include'
		if ( ! empty( $this->wcpos_request['wcpos_include'] ) ) {
			$include_ids = array_map( 'intval', (array) $this->wcpos_request['wcpos_include'] );
			$ids_format = implode( ',', array_fill( 0, count( $include_ids ), '%d' ) );
			$where .= $wpdb->prepare( " AND {$wpdb->posts}.ID IN ($ids_format) ", $include_ids );
		}

		// Handle 'wcpos_exclude'
		if ( ! empty( $this->wcpos_request['wcpos_exclude'] ) ) {
			$exclude_ids = array_map( 'intval', (array) $this->wcpos_request['wcpos_exclude'] );
			$ids_format = implode( ',', array_fill( 0, count( $exclude_ids ), '%d' ) );
			$where .= $wpdb->prepare( " AND {$wpdb->posts}.ID NOT IN ($ids_format) ", $exclude_ids );
		}

		return $where;
	}


	/**
	 * Returns array of all product ids, name.
	 *
	 * @param array $fields
	 *
	 * @return array|WP_Error
	 */
	public function wcpos_get_all_posts( array $fields = array() ): array {
		$args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		);

		if ( $this->wcpos_pos_only_products_enabled() ) {
			$args['meta_query'] = array(
				'relation' => 'OR',
				array(
					'key'     => '_pos_visibility',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => '_pos_visibility',
					'value'   => 'online_only',
					'compare' => '!=',
				),
			);
		}

		$product_query = new WP_Query( $args );

		try {
			$product_ids = $product_query->posts;

			return array_map( array( $this, 'wcpos_format_id' ), $product_ids );
		} catch ( Exception $e ) {
			Logger::log( 'Error fetching product IDs: ' . $e->getMessage() );

			return new \WP_Error(
				'woocommerce_pos_rest_cannot_fetch',
				'Error fetching product IDs.',
				array( 'status' => 500 )
			);
		}
	}

	/**
	 * Prepare objects query.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return array|WP_Error
	 */
	protected function prepare_objects_query( $request ) {
		$args          = parent::prepare_objects_query( $request );
		$barcode_field = $this->wcpos_get_barcode_field();

		// Add custom 'orderby' options
		if ( isset( $request['orderby'] ) ) {
			switch ( $request['orderby'] ) {
				case 'sku':
					$args['meta_key'] = '_sku';
					$args['orderby']  = 'meta_value';

					break;
				case 'barcode':
					$args['meta_key'] = '_sku' !== $barcode_field ? $barcode_field : '_sku';
					$args['orderby']  = 'meta_value';

					break;
				case 'stock_quantity':
					$args['meta_key'] = '_stock';
					$args['orderby']  = 'meta_value_num';

					break;
				case 'stock_status':
					$args['meta_key'] = '_stock_status';
					$args['orderby']  = 'meta_value';

					break;
			}
		}

		// Add online_only check
		if ( $this->wcpos_pos_only_products_enabled() ) {
			$meta_query = array(
				'relation' => 'OR',
				array(
					'key'     => '_pos_visibility',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => '_pos_visibility',
					'value'   => 'online_only',
					'compare' => '!=',
				),
			);

			$args['meta_query'] = $this->wcpos_combine_meta_queries( $meta_query, $args['meta_query'] );
		};

		return $args;
	}
}
