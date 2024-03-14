<?php
/**
 * Model Class to build up query args.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Models;

use RT\FoodMenu\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Query Args Class
 */
class QueryArgs {

	/**
	 * Query Args.
	 *
	 * @var array
	 */
	private $args = [];

	/**
	 * Meta values.
	 *
	 * @var array
	 */
	private $meta = [];

	/**
	 * Shortcode ID.
	 *
	 * @var array
	 */
	private $scID = [];

	/**
	 * Method to build args
	 *
	 * @param int   $scID Shortcode ID.
	 * @param array $meta Meta values.
	 * @param bool  $isCarousel Layout type.
	 *
	 * @return array
	 */
	public function buildArgs( int $scID, array $meta, bool $isCarousel = false ) {
		$this->meta = $meta;
		$this->scID = $scID;

		// Post Type.
		$this->getPostType();

		// Building Args.
		$this
			->postParams()
			->orderParams()
			->paginationParams( $isCarousel )
			->taxParams();

		return $this->args;
	}

	/**
	 * Post type.
	 *
	 * @return void
	 */
	private function getPostType() {
		$source = get_post_meta( $this->scID, 'fmp_source', true );

		$this->args['post_type']   = ( $source && in_array( $source, array_keys( Options::scProductSource() ), true ) ) ? $source : TLPFoodMenu()->post_type;
		$this->args['post_status'] = 'publish';
	}

	/**
	 * Post parameters.
	 *
	 * @return QueryArgs
	 */
	private function postParams() {
		$post_in     = $this->meta['postIn'];
		$post_not_in = $this->meta['postNotIn'];
		$limit       = $this->meta['limit'];

		if ( $post_in ) {
			$post_in                = explode( ',', $post_in );
			$this->args['post__in'] = $post_in;
		}

		if ( $post_not_in ) {
			$post_not_in                = explode( ',', $post_not_in );
			$this->args['post__not_in'] = $post_not_in;
		}

		$this->args['posts_per_page'] = $limit;

		return $this;
	}

	/**
	 * Order & Orderby parameters.
	 *
	 * @return QueryArgs
	 */
	private function orderParams() {
		$order_by = ( isset( $this->meta['order_by'] ) ? esc_html( $this->meta['order_by'] ) : null );
		$order    = ( isset( $this->meta['order'] ) ? esc_html( $this->meta['order'] ) : null );

		if ( $order ) {
			$this->args['order'] = $order;
		}

		if ( $order_by ) {
			$this->args['orderby'] = $order_by;

			if ( $order_by == 'price' ) {
				$this->args['orderby']  = 'meta_value_num';
				$this->args['meta_key'] = '_regular_price';
				$this->args['meta_type'] = 'NUMERIC';
			} else {
				$this->args['orderby'] = $order_by;
			}
		}

		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$this->args['meta_query'] = [
				'relation' => 'AND',
				[
					'key'     => '_stock_status',
					'value'   => 'outofstock',
					'compare' => 'NOT IN',
				],
			];
		}

		return $this;
	}

	/**
	 * Pagination parameters.
	 *
	 * @param bool $isCarousel Layout type.
	 *
	 * @return QueryArgs
	 */
	private function paginationParams( $isCarousel ) {
		$pagination = ! empty( $this->meta['pagination'] );
		$limit      = ( ( empty( $this->meta['limit'] ) || $this->meta['limit'] === '-1' ) ? 10000000 : absint( $this->meta['limit'] ) );

		if ( $pagination ) {
			$posts_per_page = ( ! empty( $this->meta['postsPerPage'] ) ? absint( $this->meta['postsPerPage'] ) : $limit );

			if ( $posts_per_page > $limit ) {
				$posts_per_page = $limit;
			}

			$this->args['posts_per_page'] = $posts_per_page;

			if ( is_front_page() ) {
				$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
			} else {
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			}

			$offset              = $posts_per_page * ( (int) $paged - 1 );
			$this->args['paged'] = $paged;

			if ( absint( $this->args['posts_per_page'] ) > $limit - $offset ) {
				$this->args['posts_per_page'] = $limit - $offset;
				$this->args['offset']         = $offset;
			}
		}

		if ( $isCarousel ) {
			$this->args['posts_per_page'] = $limit;
		}

		return $this;
	}

	/**
	 * Taxonomy parameters.
	 *
	 * @return QueryArgs
	 */
	private function taxParams() {
		$categoryTaxonomy = ( 'product' === $this->args['post_type'] ) ? 'product_cat' : TLPFoodMenu()->taxonomies['category'];
		$cats             = $this->meta['cats'];
		$taxQ             = [];

		if ( ! empty( $cats ) && apply_filters( 'tlp_fmp_has_multiple_meta_issue', false ) ) {
			$cats = unserialize( $cats[0] );
		}

		if ( is_array( $cats ) && ! empty( $cats ) ) {
			$taxQ[] = [
				'taxonomy' => $categoryTaxonomy,
				'field'    => 'term_id',
				'terms'    => $cats,
			];
		}

		if ( ! empty( $taxQ ) ) {
			$this->args['tax_query'] = $taxQ;
		}

		return $this;
	}
}
