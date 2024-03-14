<?php
/**
 * Related Products Manager for WooCommerce - Core Class
 *
 * @version 1.4.3
 * @since   1.0.0
 * @author  ProWCPlugins
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'ProWC_Dummy_Term' ) ) {
	/**
	 * ProWC_Dummy_Term class.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	class ProWC_Dummy_Term {
		public $term_id;
		function __construct() {
			$this->term_id = 0;
		}
	}
}

if ( ! class_exists( 'ProWC_Related_Products_Manager_Core' ) ) :

class ProWC_Related_Products_Manager_Core {

	/**
	 * Constructor.
	 *
	 * @version 1.4.2
	 * @since   1.0.0
	 * @todo    [dev] maybe find more uses of the `woocommerce_related_products` filter
	 * @todo    [feature] `storefront_related_products_args`
	 * @todo    [feature] CSS options
	 * @todo    [feature] custom template (with my own `get_related_products()` function)
	 * @todo    [feature] custom template from option (use `woocommerce_before_template_part` and `woocommerce_after_template_part`)
	 * @todo    [feature] slider
	 * @todo    [feature] customizable image size
	 */
	function __construct() {

		/**
		 * Related Hooks in WC3:
		 *
		 * \woocommerce\includes\data-stores\class-wc-product-data-store-cpt.php::get_related_products_query()
		 *     `woocommerce_product_related_posts_query`
		 * \woocommerce\includes\wc-product-functions.php::wc_get_related_products() (checks transient)
		 *     `woocommerce_product_related_posts_relate_by_category`
		 *     `woocommerce_get_related_product_cat_terms`
		 *     `woocommerce_product_related_posts_relate_by_tag`
		 *     `woocommerce_get_related_product_tag_terms`
		 *     `woocommerce_product_related_posts_force_display`
		 * \woocommerce\includes\wc-template-functions.php::woocommerce_related_products()
		 *     `woocommerce_related_products_columns`
		 * \woocommerce\includes\wc-template-functions.php::woocommerce_output_related_products()
		 *     `woocommerce_output_related_products_args`
		 */

		if ( 'yes' === get_option( 'prowc_related_products_manager_enabled', 'yes' ) ) {

			if ( version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' ) ) {
				// Related Args
				add_filter( 'woocommerce_related_products_args', array( $this, 'related_products_args' ), PHP_INT_MAX );
				add_filter( 'woocommerce_output_related_products_args', array( $this, 'output_related_products_args' ), PHP_INT_MAX );
				// Fix Empty Initial Related Products Issue
				add_filter( 'woocommerce_get_related_product_tag_terms', array( $this, 'fix_empty_initial_related_products' ), PHP_INT_MAX, 2 );
			} else {
				// Related Query
				add_filter( 'woocommerce_product_related_posts_query', array( $this, 'related_products_query_wc3' ), PHP_INT_MAX, 2 );
				add_filter( 'woocommerce_product_related_posts_force_display', '__return_true', PHP_INT_MAX );
				// Related Args
				add_filter( 'woocommerce_output_related_products_args', array( $this, 'output_related_products_args_wc3' ), PHP_INT_MAX );
			}

			// Related Columns
			add_filter( 'woocommerce_related_products_columns', array( $this, 'related_products_columns' ), PHP_INT_MAX );

			// Relate by Category
			if ( 'no' === get_option( 'prowc_related_products_manager_relate_by_category', 'yes' ) ) {
				add_filter( 'woocommerce_product_related_posts_relate_by_category', '__return_false', PHP_INT_MAX );
			} else {
				add_filter( 'woocommerce_product_related_posts_relate_by_category', '__return_true',  PHP_INT_MAX );
			}

			// Relate by Tag
			if ( 'no' === get_option( 'prowc_related_products_manager_relate_by_tag', 'yes' ) ) {
				add_filter( 'woocommerce_product_related_posts_relate_by_tag', '__return_false', PHP_INT_MAX );
			} else {
				add_filter( 'woocommerce_product_related_posts_relate_by_tag', '__return_true',  PHP_INT_MAX );
			}

			// Hide Related (globally)
			if ( 'yes' === get_option( 'prowc_related_products_manager_hide', 'no' ) ) {
				add_action( 'init', array( $this, 'remove_output_related_products_action' ), PHP_INT_MAX );
				add_filter( 'woocommerce_related_products', '__return_empty_array', PHP_INT_MAX );
			}

			// Hide Related (per product)
			if ( 'yes' === apply_filters( 'prowc_related_products_manager', 'no', 'value_relate_per_product' ) ) {
				add_filter( 'woocommerce_related_products', array( $this, 'hide_related_per_product' ), PHP_INT_MAX, 3 );
			}

			// Exclude from Related
			if ( 'yes' === get_option( 'prowc_related_products_manager_exclude_section_enabled', 'no' ) ) {
				add_filter( 'woocommerce_related_products', array( $this, 'exclude_by_taxonomy' ), PHP_INT_MAX, 3 );
			}

			// Init finished
			do_action( 'prowc_related_products_manager_init_finished' );

		}
	}

	/**
	 * exclude_by_taxonomy.
	 *
	 * @version 1.4.2
	 * @since   1.4.2
	 * @todo    [dev] add custom taxonomies
	 */
	function exclude_by_taxonomy( $related_posts, $product_id, $args ) {
		$terms_to_exclude = get_option( 'prowc_related_products_manager_exclude_taxonomy', array() );
		if ( ! empty( $terms_to_exclude ) ) {
			$taxonomies = array( 'product_cat', 'product_tag' );
			foreach ( $taxonomies as $taxonomy ) {
				if ( ! empty( $terms_to_exclude[ $taxonomy ] ) ) {
					foreach ( $related_posts as $i => $related_post ) {
						$terms = get_the_terms( $related_post, $taxonomy );
						if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
							foreach ( $terms as $term ) {
								if ( in_array( $term->term_id, $terms_to_exclude[ $taxonomy ] ) ) {
									unset( $related_posts[ $i ] );
									break;
								}
							}
						}
					}
				}
			}
		}
		return $related_posts;
	}

	/**
	 * hide_related_per_product.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	function hide_related_per_product( $related_posts, $product_id, $args ) {
		return apply_filters( 'prowc_related_products_manager', $related_posts, 'hide_per_product', array( 'product_id' => $product_id ) );
	}

	/**
	 * remove_output_related_products_action.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function remove_output_related_products_action( $args ) {
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	}

	/**
	 * related_products_args_wc3.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 * @todo    [feature] somehow add `meta_key` in WC3 (`wc_products_array_orderby()`)
	 */
	function related_products_args_wc3( $args ) {
		// Related Num
		$args['posts_per_page'] = get_option( 'prowc_related_products_manager_num', 3 );
		// Order By
		$args['orderby'] = get_option( 'prowc_related_products_manager_orderby', 'rand' );
		// Order
		if ( 'rand' != $args['orderby'] ) {
			$args['order'] = get_option( 'prowc_related_products_manager_order', 'desc' );
		}
		return $args;
	}

	/**
	 * output_related_products_args_wc3.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function output_related_products_args_wc3( $args ) {
		$args['columns'] = get_option( 'prowc_related_products_manager_columns', 3 );
		$args = $this->related_products_args_wc3( $args );
		return $args;
	}

	/**
	 * get_product_attribute_query.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function get_product_attribute_query( $args, $product_id ) {
		$attribute_name   = get_option( 'prowc_related_products_manager_relate_by_attribute_name',  '' );
		$attribute_value  = get_option( 'prowc_related_products_manager_relate_by_attribute_value', '' );
		if ( '' === $attribute_value ) {
			$attribute_value = apply_filters( 'prowc_related_products_manager', '', 'relate_by_product_attribute', array( 'product_id' => $product_id, 'attribute_name' => $attribute_name ) );
		}
		if ( 'global' === get_option( 'prowc_related_products_manager_relate_by_attribute_type', 'global' ) ) {
			// Relate by Global Attributes
			// https://wpcodebook.com/snippets/query-for-woocommerce-products-by-global-product-attributes/
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'pa_' . $attribute_name,
					'field'    => 'name',
					'terms'    => $attribute_value,
				),
			);
		} else {
			// Relate by Local Product Attributes
			// https://wpcodebook.com/snippets/query-woocommerce-products-product-specific-custom-attribute/
			$serialized_value = serialize( 'name' ) . serialize( $attribute_name ) . serialize( 'value' ) . serialize( $attribute_value );
			// extended version: $serialized_value = serialize( $attribute_name ) . 'a:6:{' . serialize( 'name' ) . serialize( $attribute_name ) . serialize( 'value' ) . serialize( $attribute_value ) . serialize( 'position' );
			$args['meta_query'] = array(
				array(
					'key'     => '_product_attributes',
					'value'   => $serialized_value,
					'compare' => 'LIKE',
				),
			);
		}
		return $args;
	}

	/**
	 * get_related_products_ids_wc3.
	 *
	 * @version 1.4.0
	 * @since   1.1.0
	 */
	function get_related_products_ids_wc3( $product_id ) {
		$include_ids = array();
		// Change Related Products
		if ( 'yes' === apply_filters( 'prowc_related_products_manager', 'no', 'value_relate_per_product' ) && 'yes' === apply_filters( 'prowc_related_products_manager', 'no', 'value_relate_per_product_enabled', array( 'product_id' => $product_id ) ) ) {
			// Relate per Product (Manual)
			if ( '' != ( $related_per_product = apply_filters( 'prowc_related_products_manager', '', 'ids_relate_per_product', array( 'product_id' => $product_id ) ) ) ) {
				$include_ids = $related_per_product;
			}
		} elseif ( 'yes' === get_option( 'prowc_related_products_manager_relate_by_attribute_enabled', 'no' ) ) {
			$args = array(
				'post_type'      => 'product',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids',
				'post__not_in'   => array( $product_id ),
			);
			$args = $this->get_product_attribute_query( $args, $product_id );
			$loop = new WP_Query( $args );
			$include_ids = $loop->posts;
		}
		return $include_ids;
	}

	/**
	 * related_products_query_wc3.
	 *
	 * @version 1.4.3
	 * @since   1.1.0
	 * @see     WC_Product_Data_Store_CPT::get_related_products_query()
	 * @todo    [dev] "Relate by Product Attribute" - directly to `$query['where']` instead of getting ids via `WP_Query`
	 * @todo    [dev] rethink hide related (for >= WC3)
	 */
	function related_products_query_wc3( $_query, $product_id ) {
		if ( 'yes' === get_option( 'prowc_related_products_manager_hide', 'no' ) ) {
			$include_ids = array( 0 );
		} else {
			$include_ids = $this->get_related_products_ids_wc3( $product_id );
			if ( empty( $include_ids ) ) {
				return $_query;
			}
		}
		$override_cats_and_tags = get_option( 'prowc_related_products_manager_override_cats_and_tags', 'yes' );
		$include_ids = implode( ',', array_map( 'absint', $include_ids ) );
		
		if( 'yes' === $override_cats_and_tags ) {
			$cats_array = array();
		}else{
			$cats_array = apply_filters( 'woocommerce_product_related_posts_relate_by_category', true, $product_id );
			if($cats_array) {
				$cats_array = apply_filters( 'woocommerce_get_related_product_cat_terms', wc_get_product_term_ids( $product_id, 'product_cat' ), $product_id );
			}else {
				$cats_array = array();
			}
		}

		if( 'yes' === $override_cats_and_tags ) {
			$tags_array = array();
		}else{
			$tags_array = apply_filters( 'woocommerce_product_related_posts_relate_by_tag', true, $product_id );
			if($tags_array) {
				$tags_array = apply_filters( 'woocommerce_get_related_product_tag_terms', wc_get_product_term_ids( $product_id, 'product_tag' ), $product_id );
			}else {
				$tags_array = array();
			}
		}

		$_product    = wc_get_product( $product_id );
		$exclude_ids = array_merge( array( 0, $product_id ), $_product->get_upsell_ids() );
		if ( 'yes' === get_option( 'prowc_related_products_manager_hide', 'no' ) ) {
			$limit = 0;
		} else {
			$limit = get_option( 'prowc_related_products_manager_num', 3 );
			$limit = $limit > 0 ? $limit : 5;
			$limit += get_option( 'prowc_related_products_manager_limit', 20 );
		}
		//////////////////////////////////////////////////////////////////////

		global $wpdb;

		// Arrays to string.
		$exclude_ids = implode( ',', array_map( 'absint', $exclude_ids ) );
		$cats_array  = implode( ',', array_map( 'absint', $cats_array ) );
		$tags_array  = implode( ',', array_map( 'absint', $tags_array ) );

		$limit           = absint( $limit );
		$query           = array();
		$query['fields'] = "SELECT DISTINCT ID FROM {$wpdb->posts} p";
		$query['join']   = " INNER JOIN {$wpdb->term_relationships} tr ON (p.ID = tr.object_id)";
		$query['join']  .= " INNER JOIN {$wpdb->term_taxonomy} tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)";
		$query['join']  .= " INNER JOIN {$wpdb->terms} t ON (t.term_id = tt.term_id)";
		$query['where']  = ' WHERE 1=1';
		$query['where'] .= " AND p.post_status = 'publish'";
		$query['where'] .= " AND p.post_type = 'product'";
		$query['where'] .= " AND p.ID NOT IN ( {$exclude_ids} )";
		if ( $include_ids ) {
			$query['where'] .= " AND p.ID IN ( {$include_ids} )";
		}
		
		$product_visibility_term_ids = wc_get_product_visibility_term_ids();

		if ( $product_visibility_term_ids['exclude-from-catalog'] ) {
			$query['where'] .= " AND t.term_id !=" . $product_visibility_term_ids['exclude-from-catalog'];
		}

		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) && $product_visibility_term_ids['outofstock'] ) {
			$query['where'] .= " AND t.term_id !=" . $product_visibility_term_ids['outofstock'];
		}

		if ( $cats_array || $tags_array ) {
			$query['where'] .= ( 'no' === $override_cats_and_tags ? ' AND (' : ' OR (' );

			if ( $cats_array ) {
				$query['where'] .= " ( tt.taxonomy = 'product_cat' AND t.term_id IN ( {$cats_array} ) ) ";
				if ( $tags_array ) {
					$query['where'] .= ' OR ';
				}
			}

			if ( $tags_array ) {
				$query['where'] .= " ( tt.taxonomy = 'product_tag' AND t.term_id IN ( {$tags_array} ) ) ";
			}

			$query['where'] .= ')';
		}

		$query['limits'] = " LIMIT {$limit} ";

		return $query;
	}

	/**
	 * related_products_args.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 * @todo    [dev] save custom results as product transient (for < WC3)
	 */
	function related_products_args( $args ) {
		// Hide Related
		if ( 'yes' === get_option( 'prowc_related_products_manager_hide', 'no' ) ) {
			return array();
		}
		// Related Num
		$args['posts_per_page'] = get_option( 'prowc_related_products_manager_num', 3 );
		// Order By
		$orderby = get_option( 'prowc_related_products_manager_orderby', 'rand' );
		$args['orderby'] = $orderby;
		if ( 'meta_value' === $orderby || 'meta_value_num' === $orderby ) {
			$args['meta_key'] = get_option( 'prowc_related_products_manager_orderby_meta_value_meta_key', '' );
		}
		// Order
		if ( get_option( 'prowc_related_products_manager_orderby', 'rand' ) != 'rand' ) {
			$args['order'] = get_option( 'prowc_related_products_manager_order', 'desc' );
		}
		// Change Related Products
		$product_id = get_the_ID();
		if ( 'yes' === apply_filters( 'prowc_related_products_manager', 'no', 'value_relate_per_product' ) && 'yes' === apply_filters( 'prowc_related_products_manager', 'no', 'value_relate_per_product_enabled', array( 'product_id' => $product_id ) ) ) {
			// Relate per Product (Manual)
			if ( '' != ( $related_per_product = apply_filters( 'prowc_related_products_manager', '', 'ids_relate_per_product', array( 'product_id' => $product_id ) ) ) ) {
				$args['post__in'] = $related_per_product;
			} else {
				return array();
			}
		} elseif ( 'yes' === get_option( 'prowc_related_products_manager_relate_by_attribute_enabled', 'no' ) ) {
			unset( $args['post__in'] );
			$args = $this->get_product_attribute_query( $args, $product_id );
		}
		return $args;
	}

	/**
	 * fix_empty_initial_related_products.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function fix_empty_initial_related_products( $terms, $product_id ) {
		if (
			( 'yes' === get_option( 'prowc_related_products_manager_relate_by_attribute_enabled', 'no' ) ) ||
			(
				'yes' === apply_filters( 'prowc_related_products_manager', 'no', 'value_relate_per_product' ) &&
				'yes' === apply_filters( 'prowc_related_products_manager', 'no', 'value_relate_per_product_enabled', array( 'product_id' => $product_id ) ) &&
				'' != apply_filters( 'prowc_related_products_manager', '', 'ids_relate_per_product', array( 'product_id' => $product_id ) )
			)
		) {
			add_filter( 'woocommerce_product_related_posts_relate_by_category', '__return_false', PHP_INT_MAX );
			add_filter( 'woocommerce_product_related_posts_relate_by_tag',      '__return_false', PHP_INT_MAX );
			if ( empty( $terms ) ) {
				$dummy_term = new ProWC_Dummy_Term();
				$terms[] = $dummy_term;
			}
		}
		return $terms;
	}

	/**
	 * related_products_columns.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function related_products_columns( $columns ) {
		return get_option( 'prowc_related_products_manager_columns', 3 );
	}

	/**
	 * output_related_products_args.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function output_related_products_args( $args ) {
		$args['columns'] = get_option( 'prowc_related_products_manager_columns', 3 );
		$args = $this->related_products_args( $args );
		return $args;
	}

}

endif;

return new ProWC_Related_Products_Manager_Core();
