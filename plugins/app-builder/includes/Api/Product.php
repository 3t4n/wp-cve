<?php

/**
 * class Product
 *
 * @link       https://appcheap.io
 * @since      1.0.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 *
 * @package    AppBuilder
 * @subpackage App_Builder/Api
 */

namespace AppBuilder\Api;

use WC_Product;
use WP_Error;
use WP_HTTP_Response;
use WP_REST_Response;
use AppBuilder\Utils;

defined( 'ABSPATH' ) || exit;

class Product {

	/**
	 * The namespace of this controller's route.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $namespace;

	public function __construct() {
		$this->namespace = APP_BUILDER_REST_BASE . '/v1';
	}

	/**
	 * Registers a REST API route
	 *
	 * @since 1.0.0
	 */
	public function register_routes() {

		/**
		 * Get Min - Max price in category
		 *
		 * @author Ngoc Dang
		 * @since 1.0.0
		 */
		if ( class_exists( '\WC_REST_Products_Controller' ) ) {
			$product = new \WC_REST_Products_Controller();
			register_rest_route( 'wc/v3', 'min-max-prices', array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_min_max_prices' ),
				'permission_callback' => array( $product, 'get_items_permissions_check' ),
			) );
		}

		/**
		 * Get recursion category
		 *
		 * @author Ngoc Dang
		 * @since 1.0.0
		 */
		register_rest_route( $this->namespace, 'categories', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( $this, 'categories' ),
			'permission_callback' => '__return_true',
		) );

		/**
		 * Get info for product variation
		 * @author Ngoc Dang
		 * @since 1.0.0
		 */
		register_rest_route( $this->namespace, 'product-variations', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( $this, 'product_variations' ),
			'permission_callback' => '__return_true',
		) );

		/**
		 * Get rating for WC product
		 * @author Ngoc Dang
		 * @since 1.0.0
		 */
		register_rest_route( $this->namespace, 'rating-count', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( $this, 'rating_count' ),
			'permission_callback' => '__return_true',
		) );

		add_filter( 'woocommerce_rest_product_object_query', array(
			$this,
			'woocommerce_rest_product_object_query',
		), 10, 2 );

		add_filter( 'woocommerce_rest_prepare_product_attribute', array(
			$this,
			'custom_woocommerce_rest_prepare_product_attribute'
		), 10, 3 );
		add_filter( 'woocommerce_rest_prepare_pa_color', array( $this, 'add_value_pa_color' ) );
		add_filter( 'woocommerce_rest_prepare_pa_image', array( $this, 'add_value_pa_image' ) );
	}

	/**
	 * Get product variation
	 *
	 * @param $request
	 *
	 * @return array|\WP_Error
	 */
	public function product_variations( $request ) {
		$product_id = $request->get_param( 'product_id' );
		$currency   = $request->get_param( 'currency' );

		if ( ! $product_id ) {
			return new \WP_Error(
				'get_product_variations',
				__( 'Product Id not provider', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		try {
			$product_variation           = new \WC_Product_Variable( $product_id );
			$variation_attributes        = $product_variation->get_variation_attributes();
			$variation_attributes_result = array();
			$variation_attributes_label  = array();
			$labels                      = array();
			$attribute_ids               = array();
			$ids                         = array();
			$attribute_terms_labels      = array();
			$attribute_terms_values      = array();
			$attribute_taxonomies        = wc_get_attribute_taxonomies();

			foreach ( wc_get_attribute_taxonomy_ids() as $key => $value ) {
				$ids[ sanitize_title( $key ) ] = $value;
			}

			foreach ( wc_get_attribute_taxonomy_labels() as $key => $value ) {
				$labels[ sanitize_title( $key ) ] = $value;
			}

			foreach ( $variation_attributes as $key => $attribute ) {
				$k    = sanitize_title( $key );
				$name = str_replace( 'pa_', '', $k );

				$label = $labels[ $name ] ?? $key;
				$id    = $ids[ $name ] ?? false;

				$labelTranslate = apply_filters( 'wpml_translate_single_string', $label, 'WordPress', 'taxonomy singular name: ' . $label );

				$variation_attributes_label[ $k ]  = $labelTranslate;
				$variation_attributes_result[ $k ] = array_values( $attribute );
				$attribute_ids[ $k ]               = $id ?: 0;

				foreach ( $attribute as $value ) {
					$term                                        = get_term_by( 'slug', $value, $key );
					$attribute_terms_labels[ $k . '_' . $value ] = apply_filters( 'trp_prepare_product_attribute_text', $term ? $term->name : $value);

					if ( $id ) {
						$attribute_taxonomy = $attribute_taxonomies[ 'id:' . $id ];
						if ( $attribute_taxonomy->attribute_type == 'color' ) {
							$attribute_terms_values[ $k . '_' . $value ] = array(
								'type'  => 'color',
								'value' => sanitize_hex_color( get_term_meta( $term->term_id, 'product_attribute_color',
									true ) ),
							);
						}
						if ( $attribute_taxonomy->attribute_type == 'image' ) {
							$attachment_id                               = absint( get_term_meta( $term->term_id, 'product_attribute_image', true ) );
							$image_size                                  = function_exists( 'woo_variation_swatches' ) ? woo_variation_swatches()->get_option( 'attribute_image_size' ) : 'thumbnail';
							$img                                         = wp_get_attachment_image_url( $attachment_id, apply_filters( 'wvs_product_attribute_image_size', $image_size ) );
							$attribute_terms_values[ $k . '_' . $value ] = array(
								'type'  => 'image',
								'value' => ! $img ? '' : $img,
							);
						}
					}
				}
			}

			return array(
				'attribute_ids'          => $attribute_ids,
				'attribute_labels'       => $variation_attributes_label,
				'attribute_terms'        => $variation_attributes_result,
				'attribute_terms_labels' => $attribute_terms_labels,
				'attribute_terms_values' => count( $attribute_terms_values ) > 0 ? $attribute_terms_values : new \stdClass(),
				'variations'             => $this->prepare_variations_for_response( $product_variation->get_available_variations( 'objects' ), $currency ),
			);

		} catch ( \Exception $e ) {
			return new \WP_Error(
				'get_product_variations',
				$e->getMessage(),
				array(
					'status' => 403,
				)
			);
		}
	}

	/**
	 *
	 * Prepare list variation output.
	 *
	 * @param $variations
	 * @param $currency
	 *
	 * @return array
	 * @since 1.1.0
	 */
	public function prepare_variations_for_response( $variations, $currency ): array {

		$product_variations = [];
		foreach ( $variations as $value ) {
			$object               = wc_get_product( $value->get_id() );
			$product_variations[] = array(
				'id'                    => $object->get_id(),
				'date_created'          => wc_rest_prepare_date_response( $object->get_date_created(), false ),
				'date_created_gmt'      => wc_rest_prepare_date_response( $object->get_date_created() ),
				'date_modified'         => wc_rest_prepare_date_response( $object->get_date_modified(), false ),
				'date_modified_gmt'     => wc_rest_prepare_date_response( $object->get_date_modified() ),
				'description'           => wc_format_content( $object->get_description() ),
				'permalink'             => $object->get_permalink(),
				'sku'                   => $object->get_sku(),
				'price'                 => Utils::convert_currency( $object->get_price(), $currency ),
				'regular_price'         => Utils::convert_currency( $object->get_regular_price(), $currency ),
				'sale_price'            => Utils::convert_currency( $object->get_sale_price(), $currency ),
				'price_html'            => $object->get_price_html(),
				'date_on_sale_from'     => wc_rest_prepare_date_response( $object->get_date_on_sale_from(), false ),
				'date_on_sale_from_gmt' => wc_rest_prepare_date_response( $object->get_date_on_sale_from() ),
				'date_on_sale_to'       => wc_rest_prepare_date_response( $object->get_date_on_sale_to(), false ),
				'date_on_sale_to_gmt'   => wc_rest_prepare_date_response( $object->get_date_on_sale_to() ),
				'on_sale'               => $object->is_on_sale(),
				'status'                => $object->get_status(),
				'purchasable'           => $object->is_purchasable(),
				'virtual'               => $object->is_virtual(),
				'downloadable'          => $object->is_downloadable(),
				'downloads'             => $this->get_downloads( $object ),
				'download_limit'        => '' !== $object->get_download_limit() ? (int) $object->get_download_limit() : - 1,
				'download_expiry'       => '' !== $object->get_download_expiry() ? (int) $object->get_download_expiry() : - 1,
				'tax_status'            => $object->get_tax_status(),
				'tax_class'             => $object->get_tax_class(),
				'manage_stock'          => $object->managing_stock(),
				'stock_quantity'        => $object->get_stock_quantity(),
				'stock_status'          => $object->get_stock_status(),
				'backorders'            => $object->get_backorders(),
				'backorders_allowed'    => $object->backorders_allowed(),
				'backordered'           => $object->is_on_backorder(),
				'low_stock_amount'      => '' === $object->get_low_stock_amount() ? null : $object->get_low_stock_amount(),
				'weight'                => $object->get_weight(),
				'dimensions'            => array(
					'length' => $object->get_length(),
					'width'  => $object->get_width(),
					'height' => $object->get_height(),
				),
				'shipping_class'        => $object->get_shipping_class(),
				'shipping_class_id'     => $object->get_shipping_class_id(),
				'images'                => $this->get_images( $object ),
				'attributes'            => $object->get_attributes(),
				'menu_order'            => $object->get_menu_order(),
				'meta_data'             => $object->get_meta_data(),
			);
		}

		return $product_variations;
	}

	/**
	 *
	 * Get list image for variations
	 *
	 * @param $variation
	 *
	 * @return array
	 * @since 1.1.0
	 */
	protected function get_images( $variation ): array {
		$images = [];

		if ( ! $variation->get_image_id() ) {
			return $images;
		}

		$attachment_id   = $variation->get_image_id();
		$attachment_post = get_post( $attachment_id );
		if ( is_null( $attachment_post ) ) {
			return $images;
		}

		$attachment = wp_get_attachment_image_src( $attachment_id, 'full' );
		if ( ! is_array( $attachment ) ) {
			return $images;
		}

		if ( ! isset( $image ) ) {
			$sizes = wp_get_registered_image_subsizes();

			$image = array(
				'id'                => (int) $attachment_id,
				'date_created'      => wc_rest_prepare_date_response( $attachment_post->post_date, false ),
				'date_created_gmt'  => wc_rest_prepare_date_response( strtotime( $attachment_post->post_date_gmt ) ),
				'date_modified'     => wc_rest_prepare_date_response( $attachment_post->post_modified, false ),
				'date_modified_gmt' => wc_rest_prepare_date_response( strtotime( $attachment_post->post_modified_gmt ) ),
				'src'               => current( $attachment ),
				'name'              => get_the_title( $attachment_id ),
				'alt'               => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
			);

			// Get all size images
			foreach ( $sizes as $size => $value ) {
				$image_info     = wp_get_attachment_image_src( (int) $attachment_id, $size );
				$image[ $size ] = $image_info[0];
			}

			$images[] = $image;
		}

		return $images;
	}

	/**
	 * Get the downloads for a product or product variation.
	 *
	 * @param WC_Product|WC_Product_Variation $product Product instance.
	 *
	 * @return array
	 * @since 1.1.0
	 */
	protected function get_downloads( $product ): array {
		$downloads = array();

		if ( $product->is_downloadable() ) {
			foreach ( $product->get_downloads() as $file_id => $file ) {
				$downloads[] = array(
					'id'   => $file_id, // MD5 hash.
					'name' => $file['name'],
					'file' => $file['file'],
				);
			}
		}

		return $downloads;
	}

	/**
	 *
	 * Get min max price
	 *
	 * @param $request
	 *
	 * @return array|object|void|null
	 */
	public function get_min_max_prices( $request ) {
		global $wpdb;

		$tax_query = array();

		if ( isset( $request['category'] ) && $request['category'] ) {
			$tax_query[] = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'cat_id',
					'terms'    => array( $request['category'] ),
				),
			);
		}

		$meta_query = array();

		$meta_query = new \WP_Meta_Query( $meta_query );
		$tax_query  = new \WP_Tax_Query( $tax_query );

		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		$sql = "SELECT min( min_price ) as min_price, MAX( max_price ) as max_price
			FROM {$wpdb->wc_product_meta_lookup}
			WHERE product_id IN (
				SELECT ID FROM {$wpdb->posts}
				" . $tax_query_sql['join'] . $meta_query_sql['join'] . "
				WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
				AND {$wpdb->posts}.post_status = 'publish'
				" . $tax_query_sql['where'] . $meta_query_sql['where'] . '
			)';

		$sql = apply_filters( 'woocommerce_price_filter_sql', $sql, $meta_query_sql, $tax_query_sql );

		return $wpdb->get_row( $sql ); // WPCS: unprepared SQL ok.
	}

	public function woocommerce_rest_product_object_query( $args, $request ) {
		$tax_query = array();

		if ( isset( $request['attrs'] ) && $request['attrs'] ) {
			$attrs = json_decode( $request['attrs'], true );
			foreach ( $attrs as $attr ) {
				$tax_query[] = array(
					'taxonomy' => $attr['taxonomy'],
					'field'    => $attr['field'],
					'terms'    => $attr['terms'],
				);
			}
			$args['tax_query'] = $tax_query;
		}

		return $args;
	}

	/**
	 * Pre product attribute
	 *
	 * @param $response
	 * @param $item
	 * @param $request
	 *
	 * @return mixed
	 * @since    1.0.0
	 */
	public function custom_woocommerce_rest_prepare_product_attribute( $response, $item, $request ) {

		$taxonomy = wc_attribute_taxonomy_name( $item->attribute_name );

		$options = get_terms( array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		) );

		$terms = $this->term_counts( $request, $taxonomy );

		foreach ( $options as $key => $term ) {
			if ( $item->attribute_type == 'color' ) {
				$term->value = sanitize_hex_color( get_term_meta( $term->term_id, 'product_attribute_color',
					true ) );
			}

			if ( $item->attribute_type == 'image' ) {
				$attachment_id = absint( get_term_meta( $term->term_id, 'product_attribute_image', true ) );
				$image_size    = function_exists( 'woo_variation_swatches' ) ? woo_variation_swatches()->get_option( 'attribute_image_size' ) : 'thumbnail';

				$term->value = wp_get_attachment_image_url( $attachment_id,
					apply_filters( 'wvs_product_attribute_image_size', $image_size ) );
			}

			$options[ $key ] = apply_filters( 'app_builder_prepare_product_option_object', $term );
		}

		$_terms = array();
		foreach ( $terms as $key => $term ) {
			$i = array_search( $term['term_count_id'], array_column( $options, 'term_id' ) );
			if ( $i >= 0 ) {
				$option        = $options[ $i ];
				$option->count = intval( $term['term_count'] );
				$_terms[]      = $option;
			}
		}
		$response->data['options'] = $options;
		$response->data['terms']   = $_terms;

		return apply_filters( 'app_builder_prepare_product_attribute_object', $response );
	}

	/**
	 *
	 * Get term counts
	 *
	 * @param $request
	 * @param $taxonomy
	 *
	 * @return array|object|null
	 */
	public function term_counts( $request, $taxonomy ) {
		global $wpdb;

		$term_ids = wp_list_pluck( get_terms( array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
		) ), 'term_id' );

		$tax_query  = array();
		$meta_query = array();

		if ( isset( $request['attrs'] ) && $request['attrs'] ) {
			$attrs = json_decode( $request['attrs'], true );
			foreach ( $attrs as $attr ) {
				$tax_query[] = array(
					'taxonomy' => $attr['taxonomy'],
					'field'    => $attr['field'],
					'terms'    => $attr['terms'],
				);
			}
		}

		$meta_query     = new \WP_Meta_Query( $meta_query );
		$tax_query      = new \WP_Tax_Query( $tax_query );
		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		// Generate query.
		$query           = array();
		$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
		$query['from']   = "FROM {$wpdb->posts}";
		$query['join']   = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'];

		$query['where'] = "
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'"
		                  . $tax_query_sql['where'] . $meta_query_sql['where'] .
		                  'AND terms.term_id IN (' . implode( ',', array_map( 'absint', $term_ids ) ) . ')';

		$query['group_by'] = 'GROUP BY terms.term_id';
		$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
		$query             = implode( ' ', $query );

		return $wpdb->get_results( $query, ARRAY_A );
	}

	/**
	 * @param $response
	 * @param $post
	 * @param $request
	 *
	 * @return mixed
	 * @since    1.0.0
	 */
	public function prepare_product_variation_images( $response, $post, $request ) {
		global $_wp_additional_image_sizes;

		if ( empty( $response->data ) || empty( $response->data['image'] ) ) {
			return $response;
		}

		foreach ( $_wp_additional_image_sizes as $size => $value ) {
			$image_info = wp_get_attachment_image_src( $response->data['image']['id'], $size );
			if ( $image_info ) {
				$response->data['image'][ $size ] = $image_info[0];
			}
		}

		return $response;

	}

	public function add_value_pa_color( $response ) {

		$term_id                 = $response->data['id'];
		$response->data['value'] = sanitize_hex_color( get_term_meta( $term_id, 'product_attribute_color', true ) );

		return $response;
	}

	public function add_value_pa_image( $response ) {

		$term_id       = $response->data['id'];
		$attachment_id = absint( get_term_meta( $term_id, 'product_attribute_image', true ) );
		$image_size    = woo_variation_swatches()->get_option( 'attribute_image_size' );

		$response->data['value'] = wp_get_attachment_image_url( $attachment_id,
			apply_filters( 'wvs_product_attribute_image_size', $image_size ) );

		return $response;
	}

	/**
	 * Get all categories
	 *
	 * @param $request
	 *
	 * @return WP_HTTP_Response|WP_REST_Response
	 * @since 1.0.0
	 * @author ngocdt
	 */
	function categories( $request ) {
		$parent = $request->get_param( 'parent' );
		$lang   = $request->get_param( 'lang' );

		/**
		 * Create key for save categories
		 */
		$key = "app-builder-categories-$parent-$lang";
		wp_cache_set( "app-builder-category-key", $key, 'app-builder' );

		/**
		 * Get categories in cache
		 */
		$result = wp_cache_get( $key, 'app-builder' );

		if ( false === $result ) {
			$result = $this->get_category_by_parent_id( $parent );
			/**
			 * Update cached
			 */
			wp_cache_set( $key, $result, 'app-builder' );
		}

		/**
		 * Return data
		 */
		$response = new WP_REST_Response( $result, 200 );
		$response->set_headers( array( 'Cache-Control' => 'max-age=3600' ) );

		return $response;
	}

	/**
	 * Get categories by parent
	 *
	 * @param $parent
	 *
	 * @return array
	 * @since 1.0.0
	 * @author ngocdt
	 */
	function get_category_by_parent_id( $parent ): array {
		$sizes = wp_get_registered_image_subsizes();
		$args  = array(
			'hierarchical'     => 1,
			'show_option_none' => '',
			'hide_empty'       => 0,
			'parent'           => $parent ?? 0,
			'taxonomy'         => 'product_cat',
		);

		$categories = get_categories( $args );

		if ( count( $categories ) ) {
			$with_subs = [];
			foreach ( $categories as $category ) {

				$image = null;

				// Get category image.
				$image_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
				if ( $image_id ) {
					$attachment = get_post( $image_id );

					$image = array(
						'id'   => (int) $image_id,
						'src'  => wp_get_attachment_url( $image_id ),
						'name' => get_the_title( $attachment ),
						'alt'  => get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
					);

					if ( $attachment ) {
						foreach ( $sizes as $size => $value ) {
							$image_info = wp_get_attachment_image_src( $image_id, $size );
							if ( $image_info ) {
								$image[ $size ] = $image_info[0];
							}
						}
					}

				}

				$with_subs[] = array(
					'id'         => (int) $category->term_id,
					'name'       => htmlspecialchars_decode( apply_filters( 'app_builder_prepare_product_category_name', $category->name ) ),
					'slug'       => $category->slug,
					'parent'     => $category->parent,
					'categories' => $this->get_category_by_parent_id( (int) $category->term_id ),
					'image'      => $image,
					'count'      => (int) $category->count
				);
			}

			return $with_subs;

		} else {
			return [];
		}
	}

	/**
	 *
	 * Get product rating info
	 *
	 * @param $request
	 *
	 * @return array|WP_Error
	 * @since    1.0.0
	 */
	public function rating_count( $request ) {
		$product_id = $request->get_param( 'product_id' );

		if ( $product_id ) {
			$product = new WC_Product( $product_id );

			return array(
				"5" => $product->get_rating_count( 5 ),
				"4" => $product->get_rating_count( 4 ),
				"3" => $product->get_rating_count( 3 ),
				"2" => $product->get_rating_count( 2 ),
				"1" => $product->get_rating_count( 1 ),
			);

		}

		return new \WP_Error(
			"product_id",
			__( "Product ID not provider.", "app-builder" ),
			array(
				'status' => 403,
			)
		);

	}

	public function get_items_permissions_check(): bool {
		return false;
	}
}