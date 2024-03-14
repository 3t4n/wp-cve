<?php

namespace CTXFeed\V5\Query;

use CTXFeed\V5\Helper\CommonHelper;
use CTXFeed\V5\Utility\Config;
use WC_Product_Query;

class WCQuery implements QueryInterface {
	private $config;
	private $arguments;

	/**
	 * @param Config $config
	 * @param        $args
	 */
	public function __construct( $config, $args = [] ) {
		$this->config    = $config;
		$this->arguments = empty( $args ) ? $this->get_query_arguments() : wp_parse_args( $args, $this->get_query_arguments() );
	}

	public function get_product_types() {
		$productTypes = CommonHelper::supported_product_types();

		// Include Product Variations with db query if configured.
//		if ('variable' !== Settings::get('variation_query_type')) {
//			$productTypes[100] = 'variation';
//		}

		// Exclude Product Variations with db query if configured
		if ( in_array( 'variation', $productTypes ) && ! $this->config->get_variations_to_include() ) {
			$key = array_search( 'variation', $productTypes );
			unset( $productTypes[ $key ] );
		}

		return apply_filters( 'ctx_filter_product_types_for_product_query', $productTypes );
	}

	public function get_query_arguments() {
		$arguments = [
			'limit'            => - 1, // phpcs:ignore
			'status'           => $this->get_product_status(),
			'type'             => $this->get_product_types(),
			'orderby'          => 'date',
			'order'            => 'DESC',
			'return'           => 'ids',
			'suppress_filters' => false,
		];


		// Include Product Ids.
		$include = $this->config->get_products_to_include();
		if ( $include ) {
			$arguments['include'] = $include;
		}

		// Exclude Product Ids
		$exclude = $this->config->get_products_to_exclude();
		if ( $exclude ) {
			$arguments['exclude'] = $exclude;
		}


		// Stock Status
		$stockStatus = [ 'instock', 'onbackorder', 'outofstock' ];
		// Remove Out of Stock Products.
		if ( $this->config->remove_outofstock_product() ) {
			$key = array_search( 'outofstock', $stockStatus );
			unset( $stockStatus[ $key ] );
		}
		// Remove On Backorder Products.
		if ( $this->config->remove_backorder_product() ) {
			$key = array_search( 'onbackorder', $stockStatus );
			unset( $stockStatus[ $key ] );
		}
		$arguments['stock_status'] = $stockStatus;

		// Include Categories
		$categoriesToInclude = $this->config->get_categories_to_include();
		if ( $categoriesToInclude ) {
			$arguments['category'] = $categoriesToInclude;
		}

		// Exclude Categories
		$categoriesToExclude = $this->config->get_categories_to_exclude();
		if ( $categoriesToExclude ) {
			$arguments['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $categoriesToExclude,
				'operator' => 'NOT IN',
			);
		}

		// Include Author
		$author = $this->config->get_vendors_to_include();
		if ( $author ) {
			$arguments['author'] = $author;
		}

		return apply_filters( 'ctx_filter_arguments_for_product_query', $arguments, 'wc' );

	}

	public function get_product_status() {
		$status = $this->config->get_post_status_to_include();

		return ( $status ) ?: "publish";
	}

	public function product_ids() {

		return ( new WC_Product_Query( $this->arguments ) )->get_products();
	}
}
