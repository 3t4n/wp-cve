<?php

namespace CTXFeed\V5\Query;

use WP_Query;

class WPQuery implements QueryInterface {
	private $config;
	private $arguments;

	public function __construct( $config, $args = [] ) {
		$this->config    = $config;
		$this->arguments = empty( $args ) ? $this->get_query_arguments() : wp_parse_args( $args, $this->get_query_arguments() );
	}

	public function get_product_types() {

		$post_type = [ 'product', 'product_variation' ];
		// Include Product Variations with db query if configured
//		if ('variable' !== woo_feed_get_options('variation_query_type')) {
//			$post_type = ['product', 'product_variation'];
//		}

		// Exclude Product Variations with db query if configured
		if ( in_array( 'product_variation', $post_type ) && ! $this->config->get_variations_to_include() ) {
			$key = array_search( 'product_variation', $post_type );
			unset( $post_type[ $key ] );
		}

		return apply_filters( 'ctx_filter_product_types_for_product_query', $post_type );
	}

	public function get_query_arguments() {
		$arguments = [
			'posts_per_page'         => - 1,
			'post_type'              => $this->get_product_types(),
			'post_status'            => $this->get_product_status(),
			'order'                  => 'DESC',
			'fields'                 => 'ids',
			'cache_results'          => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'suppress_filters'       => false,
		];

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

		// Remove Out of Stock Products.
		if ( $this->config->remove_outofstock_product() ) {
			$arguments['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => '_stock_status',
					'value'   => 'outofstock',
					'compare' => '!=',
				),
			);
		}
		// Remove On Backorder Products.
		if ( $this->config->remove_backorder_product() ) {
			$arguments['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => '_stock_status',
					'value'   => 'onbackorder',
					'compare' => '!=',
				),
			);
		}

		// Remove both outofstock and onbackorder products.
		if ( $this->config->remove_outofstock_product() && $this->config->remove_backorder_product() ) {
			$arguments['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'relation' => 'AND',
				array(
					'key'     => '_stock_status',
					'value'   => 'onbackorder',
					'compare' => '!=',
				),
				array(
					'key'     => '_stock_status',
					'value'   => 'outofstock',
					'compare' => '!=',
				)
			);
		}
		// Include Categories
		$categoriesToInclude = $this->config->get_categories_to_include();
		if ( $categoriesToInclude ) {
			$arguments['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					// This is optional, as it defaults to 'term_id'
					'terms'    => $categoriesToInclude,
					'operator' => 'IN', // Possible values are 'IN', 'NOT IN', 'AND'.
				),
			);
		}

		// Exclude Categories
		$categoriesToExclude = $this->config->get_categories_to_exclude();
		if ( $categoriesToExclude ) {
			$arguments['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					// This is optional, as it defaults to 'term_id'
					'terms'    => $categoriesToExclude,
					'operator' => 'NOT IN', // Possible values are 'IN', 'NOT IN', 'AND'.
				),
			);
		}

		// Include Author
		$author = $this->config->get_vendors_to_include();
		if ( $author ) {
			$arguments['author__in'] = $author;
		}


		return apply_filters( 'ctx_filter_arguments_for_product_query', $arguments, 'wp' );
	}

	public function get_product_status() {
		$status = $this->config->get_post_status_to_include();

		return ( $status ) ?: "publish";
	}

	public function product_ids() {

		return ( new WP_Query( $this->arguments ) )->get_posts();
	}
}
