<?php
/**
 * Data class
 *
 * @author  YITH
 * @package YITH/Search/DataIndex
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Recover the data from database
 *
 * @since 2.0.0
 */
class YITH_WCAS_Data_Index_Query {
	/**
	 * SELECT part of main query
	 *
	 * @var string
	 */
	protected $select = '';

	/**
	 * JOIN part of main query
	 *
	 * @var string
	 */
	protected $join = '';

	/**
	 * WHERE part of main query
	 *
	 * @var string
	 */
	protected $where = '';

	/**
	 * Data recovered by query
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * Constructor
	 *
	 * @param   array $args  Arguments.
	 *
	 * @since 2.0.0
	 */
	public function __construct( $data_type = array('product') ) {
		if( in_array( 'product', $data_type )){
			$this->get_products();
		}

		if( in_array( 'post', $data_type )){
			$this->get_posts_and_pages();
		}

	}

	/**
	 * Create the main query
	 *
	 * @retun void
	 */
	protected function get_products() {

		$limit              = 500;
		$offset             = 0;
		$product_categories = ywcas()->settings->get_search_field_by_type( 'product_categories' );
		$product_tags       = ywcas()->settings->get_search_field_by_type( 'product_tags' );

		$visibility = wc_get_product_visibility_term_ids();
		$tax_query  = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'product_visibility',
				'fields'   => 'term_id',
				'terms'    => $visibility['exclude-from-search'],
				'operator' => 'NOT IN',
			),
		);

		if ( $product_categories && 'all' !== $product_categories['product_category_condition'] ) {
			$tax_query[] = array(
				'taxonomy' => 'product_cat',
				'fields'   => 'term_id',
				'terms'    => $product_categories['category-list'],
				'operator' => 'include' === $product_categories['product_category_condition'] ? 'IN' : 'NOT IN',
			);
		}

		if ( $product_tags && 'all' !== $product_tags['product_tag_condition'] ) {
			$tax_query[] = array(
				'taxonomy' => 'product_tag',
				'fields'   => 'term_id',
				'terms'    => $product_tags['tag-list'],
				'operator' => 'include' === $product_tags['product_tag_condition'] ? 'IN' : 'NOT IN',
			);
		}

		$tax_query = apply_filters( 'ywcas_product_data_index_tax_query', $tax_query );

		$args = array(
			'post_type'        => array( 'product' ),
			'fields'           => 'ids',
			'tax_query'        => $tax_query,
			'posts_per_page'   => $limit,
			'post_status'      => 'publish',
			'suppress_filters' => true,
			'offset'           => $offset,
		);

		$query = new WP_Query( $args );

		while ( $query->have_posts() ) {
			$this->data     = array_merge( $this->data, $query->get_posts() );
			$args['offset'] = $args['offset'] + $limit;
			wp_reset_postdata();
			$query = new WP_Query( $args );
		}

		if ( $this->data ) {

			$variations = array();
			$args       = array(
				'post_type'        => array( 'product_variation' ),
				'fields'           => 'ids',
				'posts_per_page'   => $limit,
				'suppress_filters' => true,
				'post_parent__in'  => $this->data,
				'post_status'      => 'publish',
				'offset'           => 0,
			);

			$query = new WP_Query( $args );

			while ( $query->have_posts() ) {
				$variations     = array_merge( $variations, $query->get_posts() );
				$args['offset'] = $args['offset'] + $limit;
				wp_reset_postdata();
				$query = new WP_Query( $args );
			}

			$this->data = array_merge( $this->data, $variations );

		}

	}

	/**
	 * Create the main query
	 *
	 * @retun void
	 */
	protected function get_posts_and_pages() {
		$limit  = 500;
		$offset = 0;

		$args = array(
			'post_type'        => array( 'post', 'page' ),
			'fields'           => 'ids',
			'posts_per_page'   => $limit,
			'suppress_filters' => true,
			'offset'           => $offset,
		);

		$query = new WP_Query( $args );

		while ( $query->have_posts() ) {
			$this->data     = array_merge( $this->data, $query->get_posts() );
			$args['offset'] = $args['offset'] + $limit;
			wp_reset_postdata();
			$query = new WP_Query( $args );
		}

	}


	/**
	 * Filter for status
	 *
	 * @param   array $status  List of status.
	 *
	 * @return void
	 */
	protected function set_status( $status = array( 'publish' ) ) {
		$status = (array) apply_filters( 'yith_wcas_data_index_post_status_filter', $status );
		if ( $status ) {
			$this->where .= ' AND wp_posts.post_status IN (' . $this->escape_array( $status ) . ')';
		}
	}

	/**
	 * Build the where part
	 *
	 * @return void
	 */
	protected function set_visibility() {
		global $wpdb;

		$visibility = wc_get_product_visibility_term_ids();
		if ( isset( $visibility['exclude-from-search'] ) ) {
			$this->where .= $wpdb->prepare( " AND wp_posts.ID NOT IN ( SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id IN (%d) )", $visibility['exclude-from-search'] );
		}
	}


	/**
	 * Return the content of data
	 *
	 * @return mixed
	 */
	public function get_data() {
		return apply_filters( 'yith_wcas_data_index_data', $this->data );
	}

	/**
	 * Escape the array
	 *
	 * @param   array $arr  Array.
	 *
	 * @return string
	 */
	public function escape_array( $arr ) {
		global $wpdb;
		$escaped = array();
		foreach ( $arr as $k => $v ) {
			if ( is_numeric( $v ) ) {
				$escaped[] = $wpdb->prepare( '%d', $v );
			} else {
				$escaped[] = $wpdb->prepare( '%s', $v );
			}
		}

		return implode( ',', $escaped );
	}
}
