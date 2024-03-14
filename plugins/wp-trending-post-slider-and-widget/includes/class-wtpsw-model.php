<?php
/**
 * Model Class
 *
 * Handles query related functionality of plugin
 *
 * @package WP Trending Post Slider and Widget
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wtpsw_Model {

	function __construct(){
	}

	/**
	 * Function to get post data
	 * 
	 * @since 1.0.0
	 */
	function wtpsw_get_posts( $args = array() ) {

		// Taking defaults
		$result_data = array();

		$postsargs['post_status']		= array( 'publish' );
		$postsargs['post_type']			= ! empty( $args['post_type'] )			? $args['post_type']		: 'post';
		$postsargs['posts_per_page']	= ! empty( $args['posts_per_page'] )	? $args['posts_per_page']	: -1;

		// Order by records
		$postsargs['order']		= ! empty( $args['order'] )		? $args['order']	: 'DESC';
		$postsargs['orderby'] 	= ! empty( $args['orderby'] )	? $args['orderby']	: 'date';

		// Meta key
		if( isset( $args['meta_key'] ) && ! empty( $args['meta_key'] )) {
			$postsargs['meta_key'] = $args['meta_key'];
		}

		// Per page records
		if( isset( $args['paged'] ) && ! empty( $args['paged'] )) {
			$postsargs['paged']	= $args['paged'];
		}

		// Meta query
		if( isset( $args['meta_query'] ) && ! empty( $args['meta_query'] )) {
			$postsargs['meta_query'] = $args['meta_query'];
		}

		// Taxonomy query
		if( isset( $args['tax_query'] ) && ! empty( $args['tax_query'] )) {
			$postsargs['tax_query'] = $args['tax_query'];
		}

		// Date query
		if( isset( $args['date_query'] ) && ! empty( $args['date_query'] )) {
			$postsargs['date_query'] = $args['date_query'];
		}

		// Run WP Query
		$result = new WP_Query( $postsargs );

		// If only want to get count
		if( isset( $args['getcount'] ) && $args['getcount'] == '1' ) {
			$result_data = $result->post_count;
		}  elseif ( isset( $args['list_data'] ) && ! empty( $args['list_data'] )) { // Data with post and count in array format

			// Converting retrived post data to array
			$result_data = wtpsw_object_to_array( $result->posts );

			// Fetch data with count
			if( isset( $args['list_data'] ) && ! empty( $args['list_data'] ) ) {

				$data_res = array();

				$data_res['data']  = $result_data;

				// To get total count of post
				$data_res['total'] = isset( $result->found_posts ) ? $result->found_posts : '';

				// Assigning it in to returned array
				$result_data = $data_res;
			}

		} else { // Simply pass whole query
			$result_data = $result;
		}

		return $result_data;
	}

	/**
	 * Function to modify query where
	 * 
	 * @since 1.0.0
	 */
	function wtpsw_query_where( $where = '' ) {

		global $wpdb, $wtpsw_options, $wtpsw_view_by;

		$current_date	= current_time( 'timestamp' );
		$post_range		= isset( $wtpsw_options['post_range'] )	? $wtpsw_options['post_range'] : '';

		switch ( $post_range ) {
			case 'daily':
				$start_date	= date( 'Y-m-d', $current_date );

				$where_con	= " AND DATE($wpdb->posts.post_date) = '{$start_date}' ";
				break;

			case 'last_day':
				$start_date	= date( 'Y-m-d', strtotime( '-1 days', $current_date ) );

				$where_con	= " AND DATE($wpdb->posts.post_date) = '{$start_date}' ";
				break;

			case 'last_week':
				$start_date	= date( 'Y-m-d', strtotime( '-7 days', $current_date ) );
				$end_date	= date( 'Y-m-d', $current_date );

				$where_con = " AND DATE($wpdb->posts.post_date) > '{$start_date}' AND DATE($wpdb->posts.post_date) <= '{$end_date}'";
				break;

			case 'last_month':
				$start_date	= date( 'Y-m-d', strtotime( '-1 months', $current_date ) );
				$end_date	= date( 'Y-m-d', $current_date );

				$where_con = " AND DATE($wpdb->posts.post_date) >= '{$start_date}' AND DATE($wpdb->posts.post_date) <= '{$end_date}'";
				break;

			default:
				$where_con = '';
				break;
		}

		// Append where condition
		if( isset( $where_con )) {
			$where .= $where_con;
		}

		if( ! empty( $wtpsw_view_by ) && $wtpsw_view_by == 'comment_count' ) {
			$where .= " AND $wpdb->posts.comment_count > 0";
		}

		return $where;
	}
}

$wtpsw_model = new Wtpsw_Model();