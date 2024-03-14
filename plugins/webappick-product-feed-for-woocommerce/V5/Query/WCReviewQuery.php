<?php
namespace CTXFeed\V5\Query;
use WC_Product_Query;
use WP_Query;

class WCReviewQuery implements QueryInterface {
	private $config;
	private $wc_arguments;
	private $wp_arguments;

	public function __construct( $config, $args = [] ) {
		$this->config    = $config;
		$this->wc_arguments = empty($args) ? $this->get_wc_query_arguments() : wp_parse_args( $args, $this->get_wc_query_arguments());
		$this->wp_arguments = empty($args) ? $this->get_wp_query_arguments() : wp_parse_args( $args, $this->get_wp_query_arguments());
	}

	public function get_query_arguments() {
		return [];
	}

	/**
	 * @return string
	 */
	public function get_product_types(){
		return 'product';
	}

	public function get_wc_product_types()
	{
		$productTypes = [
			'simple',
			'variable',
			'variation',
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
		];

		// Include Product Variations with db query if configured.
//		if ('variable' !== Settings::get('variation_query_type')) {
//			$productTypes[100] = 'variation';
//		}

		// Exclude Product Variations with db query if configured
		if (in_array('variation', $productTypes) && !$this->config->get_variations_to_include()) {
			$key = array_search('variation', $productTypes);
			unset($productTypes[$key]);
		}

		return apply_filters('ctx_filter_product_types_for_product_query', $productTypes);
	}

	public function get_wp_product_types()
	{

		$post_type = ['product', 'product_variation'];
		// Include Product Variations with db query if configured
//		if ('variable' !== woo_feed_get_options('variation_query_type')) {
//			$post_type = ['product', 'product_variation'];
//		}

		// Exclude Product Variations with db query if configured
		if (in_array('product_variation', $post_type) && !$this->config->get_variations_to_include()) {
			$key = array_search('product_variation', $post_type);
			unset($post_type[$key]);
		}

		return apply_filters('ctx_filter_product_types_for_product_query', $post_type);
	}

	public function get_wp_query_arguments() {
		$arguments = [
			'posts_per_page' => -1,
			'post_type' => $this->get_wp_product_types(),
			'post_status' => $this->get_product_status(),
			'order' => 'DESC',
			'fields' => 'ids',
			'cache_results' => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'suppress_filters' => false,
		];

		// Include Product Ids.
		$include = $this->config->get_products_to_include();
		if ($include) {
			$arguments['post__in'] = $include;
		}

		// Exclude Product Ids
		$exclude = $this->config->get_products_to_exclude();
		if ($exclude) {
			$arguments['post__not_in'] = $exclude;
		}

		// Ignore below database filter if variation to include.
		if (!$this->config->get_variations_to_include()) {
			// Remove Out of Stock Products.
			if ($this->config->remove_outofstock_product()) {
				$arguments['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key' => '_stock_status',
						'value' => 'outofstock',
						'compare' => '!=',
					),
				);
			}
			// Remove On Backorder Products.
			if ($this->config->remove_backorder_product()) {
				$arguments['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key' => '_stock_status',
						'value' => 'onbackorder',
						'compare' => '!=',
					),
				);
			}

			// Remove both outofstock and onbackorder products.
			if ($this->config->remove_outofstock_product() && $this->config->remove_backorder_product()) {
				$arguments['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					'relation' => 'AND',
					array(
						'key' => '_stock_status',
						'value' => 'onbackorder',
						'compare' => '!=',
					),
					array(
						'key' => '_stock_status',
						'value' => 'outofstock',
						'compare' => '!=',
					)
				);
			}

			// Include Categories
			$categoriesToInclude = $this->config->get_categories_to_include();
			if ($categoriesToInclude) {
				$arguments['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => 'product_cat',
						'field' => 'slug',
						// This is optional, as it defaults to 'term_id'
						'terms' => $categoriesToInclude,
						'operator' => 'IN', // Possible values are 'IN', 'NOT IN', 'AND'.
					),
				);
			}

			// Exclude Categories
			$categoriesToExclude = $this->config->get_categories_to_exclude();
			if ($categoriesToExclude) {
				$arguments['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => 'product_cat',
						'field' => 'slug',
						// This is optional, as it defaults to 'term_id'
						'terms' => $categoriesToExclude,
						'operator' => 'NOT IN', // Possible values are 'IN', 'NOT IN', 'AND'.
					),
				);
			}

			// Include Author
			$author = $this->config->get_vendors_to_include();
			if ($author) {
				$arguments['author__in'] = $author;
			}

		}


		return apply_filters( 'ctx_filter_arguments_for_product_with_review_query', $arguments, 'review' );
	}

	public function get_wc_query_arguments() {
		$arguments = [
			'limit' => -1, // phpcs:ignore
			'status' => $this->get_product_status(),
			'type' => $this->get_wc_product_types(),
			'orderby' => 'date',
			'order' => 'DESC',
			'return' => 'ids',
			'suppress_filters' => false,
		];


		// Include Product Ids.
		$include = $this->config->get_products_to_include();
		if ($include) {
			$arguments['include'] = $include;
		}

		// Exclude Product Ids
		$exclude = $this->config->get_products_to_exclude();
		if ($exclude) {
			$arguments['exclude'] = $exclude;
		}

		// Ignore below database filter if variation to include.
		if (!$this->config->get_variations_to_include()) {
			// Stock Status
			$stockStatus = ['instock', 'onbackorder', 'outofstock'];
			// Remove Out of Stock Products.
			if ($this->config->remove_outofstock_product()) {
				$key = array_search('outofstock', $stockStatus);
				unset($stockStatus[$key]);
			}
			// Remove On Backorder Products.
			if ($this->config->remove_backorder_product()) {
				$key = array_search('onbackorder', $stockStatus);
				unset($stockStatus[$key]);
			}
			$arguments['stock_status'] = $stockStatus;


			// Include Categories
			$categoriesToInclude = $this->config->get_categories_to_include();
			if ($categoriesToInclude) {
				$arguments['category'] = $categoriesToInclude;
			}

			// Exclude Categories
			$categoriesToExclude = $this->config->get_categories_to_exclude();
			if ($categoriesToExclude) {
				$arguments['tax_query'][] = array(
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => $categoriesToExclude,
					'operator' => 'NOT IN',
				);
			}

			// Include Author
			$author = $this->config->get_vendors_to_include();
			if ($author) {
				$arguments['author'] = $author;
			}
		}


		return apply_filters( 'ctx_filter_arguments_for_product_with_review_query', $arguments, 'review' );
	}

	public function get_product_status() {
		$status = $this->config->get_post_status_to_include();

		return ( $status ) ?: "publish";
	}

	public function product_ids() {

		$wp = (new WP_Query($this->wp_arguments))->get_posts();
		$wc = (new WC_Product_Query($this->wc_arguments))->get_products();

		return array_unique( array_merge( $wc, $wp ) );
	}
}
