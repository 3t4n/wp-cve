<?php

namespace CTXFeed\V5\Query;

use WP_Query;

/**
 * Class WPWCQuery
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Query
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   MyCategory
 */
class ProductQuery implements QueryInterface {

	/**
	 * @var \CTXFeed\V5\Utility\Config $config Feed Config.
	 */
	private $config;

	/**
	 * @var array|null $arguments Query Arguments.
	 */
	private $arguments;

	/**
	 * WPWCQuery constructor.
	 *
	 * @param \CTXFeed\V5\Utility\Config $config Feed Config.
	 * @param array                      $args   Query Arguments.
	 */
	public function __construct( $config, $args = array() ) {
		$this->config    = $config;
		$this->arguments = wp_parse_args( $args, $this->get_query_arguments() );
	}

	/**
	 * Get product types.
	 *
	 * @return array
	 */
	public function get_product_types() {
		return array( 'product' );
	}

	/**
	 * Get supported woocommerce product types.
	 *
	 * @return array
	 */
	public function get_wc_product_types() {
		$product_types = array(
			'simple',
			'variable',
			'grouped',
			'external',
			'composite',
			'bundle',
			'bundled',
			'yith_bundle',
			'yith-composite',
			'subscription',
			'variable-subscription',
			'woosb',
		);

		return apply_filters( 'ctx_feed_filter_product_types_for_product_query', $product_types );
	}

	/**
	 * Set query arguments.
	 *
	 * @return array
	 */
	public function get_query_arguments() {
		$arguments = array(
			'posts_per_page'         => '-1',
			'post_type'              => $this->get_product_types(),
			'post_status'            => $this->get_product_status(),
			'order'                  => 'DESC',
			'fields'                 => 'ids',
			'cache_results'          => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'suppress_filters'       => false,
		);
		// Include Product Ids.
		$include = $this->config->get_products_to_include();

		if ( $include ) {
			$arguments['post__in'] = $include;
		}

		// Exclude Product Ids
		$exclude = $this->config->get_products_to_exclude();

		if ( $exclude ) {
			$arguments['post__not_in'] = $exclude;
		}

		// Include Author
		$author = $this->config->get_vendors_to_include();

		if ( $author ) {
			$arguments['author__in'] = $author;
		}

		// Add taxonomy query conditions.
		$arguments['tax_query'] = $this->taxonomy_conditions();// phpcs:ignore
		// Add meta query conditions.
		$arguments['meta_query'] = $this->meta_conditions();// phpcs:ignore

		return $arguments;
	}

	/**
	 * Get taxonomy conditions.
	 *
	 * @return array
	 */
	public function taxonomy_conditions() {
		$tax_conditions = array(
			'relation' => 'AND',
		);

		$product_type = array(
			'taxonomy' => 'product_type',
			'field'    => 'slug',
			'terms'    => $this->get_wc_product_types(),
			'operator' => 'IN',
		);

		$tax_conditions[] = $product_type;


		// Include Categories
		$categories_to_include = $this->config->get_categories_to_include();

		if ( $categories_to_include ) {
			$product_categories = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $categories_to_include,
					'operator' => 'IN', // Possible values are 'IN', 'NOT IN', 'AND'.
				),
			);
			$tax_conditions[]   = $product_categories;
		}

		// Exclude Categories
		$categories_to_exclude = $this->config->get_categories_to_exclude();

		if ( $categories_to_exclude ) {
			$product_categories = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $categories_to_exclude,
					'operator' => 'NOT IN',
				),
			);
			$tax_conditions[]   = $product_categories;
		}

		return $tax_conditions;
	}

	/**
	 * Get meta conditions.
	 *
	 * @return array
	 */
	public function meta_conditions() {
		$meta_conditions = array(
			'relation' => 'AND',
		);
		// Remove out of stock products.
		$remove_out_of_stock = $this->config->remove_outofstock_product();

		if ( $remove_out_of_stock ) {
			$out_of_stock      = array(
				'key'     => '_stock_status',
				'value'   => 'outofstock',
				'compare' => '!=',
			);
			$meta_conditions[] = $out_of_stock;
		}

		// Remove back order products.
		$remove_back_order = $this->config->remove_backorder_product();

		if ( $remove_back_order ) {
			$back_order        = array(
				'key'     => '_stock_status',
				'value'   => 'onbackorder',
				'compare' => '!=',
			);
			$meta_conditions[] = $back_order;
		}

		// Remove hidden products.
		$remove_hidden = $this->config->remove_hidden_products();

		if ( $remove_hidden ) {
			$hidden            = array(
				'key'     => '_visibility',
				'value'   => 'hidden',
				'compare' => '!=',
			);
			$meta_conditions[] = $hidden;
		}

		// Remove empty price products.
		$remove_empty_price = $this->config->remove_empty_price();

		if ( $remove_empty_price ) {
			$empty_price       = array(
				'key'     => '_regular_price',
				'value'   => '',
				'compare' => '!=',
			);
			$meta_conditions[] = $empty_price;
		}

		// Remove empty image products.
		$remove_empty_image = $this->config->remove_empty_image();

		if ( $remove_empty_image ) {
			$empty_image       = array(
				'key'     => '_thumbnail_id',
				'value'   => '',
				'compare' => '!=',
			);
			$meta_conditions[] = $empty_image;
		}

		return $meta_conditions;
	}

	/**
	 * Get product status.
	 *
	 * @return string
	 */
	public function get_product_status() {
		$status = $this->config->get_post_status_to_include();

		if ( $status ) {
			return $status;
		}

		return 'publish';
	}

	/**
	 * Query Product Ids.
	 *
	 * @return array Array of product ids.
	 */
	public function product_ids() {
		// Add custom join and where clause.
		add_filter( 'posts_where', array( $this, 'custom_where_query' ) );
		// Execute the query.
		$query = new WP_Query( $this->arguments );

		// Remove filters after the query is executed.
		remove_filter( 'posts_where', '__return_false' );

		return $query->get_posts();
	}

	/**
	 * Custom where query.
	 *
	 * @param string $where Where query.
     * @return string
	 */
	public function custom_where_query( $where ) {
		global $wpdb;

		$remove_empty_title = $this->config->remove_empty_title();

		if ( $remove_empty_title ) {
			$where .= " AND $wpdb->posts.post_title != ''";
		}

		$remove_empty_description = $this->config->remove_empty_description();

		if ( $remove_empty_description ) {
			$where .= " AND $wpdb->posts.post_content != ''";
		}

		return $where;
	}

}
