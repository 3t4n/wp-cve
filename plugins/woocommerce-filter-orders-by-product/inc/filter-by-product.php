<?php
/**
 * @author  FlyoutApps
 * @since   1.0
 * @version 1.0
 */

namespace flyoutapps\wfobpp;

class Filter_By_Product extends Filter_By {

	public function __construct() {
		$this->id = 'wfobpp_by_product';
		parent::__construct();

		if ( Helper::is_HPOS_active()) {
			add_filter( 'woocommerce_orders_table_query_clauses', array( $this, 'filter_hpos_query' ), 10, 2 );
		} else {
			add_filter( 'posts_where', array( $this, 'filter_where' ), 10, 2 );
		}
	}

	public function dropdown_fields(){
		global $wpdb;

		$status = apply_filters( 'wfobp_product_status', 'publish' );
		$sql    = "SELECT ID,post_title FROM $wpdb->posts WHERE post_type = 'product'";
		$sql   .= ( $status == 'any' ) ? '' : " AND post_status = '$status'";
		$all_posts = $wpdb->get_results( $sql, ARRAY_A );

		$fields    = array();
		$fields[0] = esc_html__( 'All Products', 'woocommerce-filter-orders-by-product' );
		foreach ( $all_posts as $all_post ) {
			$fields[$all_post['ID']] = $all_post['post_title'];
		}

		return $fields;
	}

	public function filter_hpos_query( $pieces, $args ) {
		if ( isset( $_GET[$this->id] ) && !empty( $_GET[$this->id] ) ) {
			$product = intval($_GET[$this->id]);

			// Check if selected product is inside order query
			$pieces['where'] .= " AND $product IN (";
			$pieces['where'] .= Helper::query_by_product_hpos();
			$pieces['where'] .= ")";
		}

		return $pieces;
	}

	// Modify where clause in query
	public function filter_where( $where, $query ) {
		if( $query->is_search() ) {
			if ( isset( $_GET[$this->id] ) && !empty( $_GET[$this->id] ) ) {
				$product = intval($_GET[$this->id]);

				// Check if selected product is inside order query
				$where .= " AND $product IN (";
				$where .= $this->query_by_product();
				$where .= ")";
			}
		}
		return $where;
	}
}