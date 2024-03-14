<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Elex_Manage_Feeds_Ajax_Function {

	public function __construct() {
		add_action( 'wp_ajax_elex_gpf_manage_feed_remove_file', array( $this, 'elex_gpf_manage_feed_remove_file' ) );
		add_action( 'wp_ajax_elex_gpf_manage_feed_refresh_file', array( $this, 'elex_gpf_manage_feed_refresh_file' ) );
		add_action( 'wp_ajax_elex_gpf_get_reports', array( $this, 'elex_gpf_get_reports' ) );
	}

	public function elex_gpf_manage_feed_remove_file() {
		check_ajax_referer( 'ajax-elex-gpf-manage-feed-nonce', '_ajax_elex_gpf_manage_feed_nonce' );
		$file_to_delete = isset( $_POST['file_to_delete'] ) ? sanitize_text_field( $_POST['file_to_delete'] ) : '';
		$manage_feed_data = json_decode( elex_gpf_get_feed_data( $file_to_delete, 'manage_feed_data' )[0]['feed_meta_content'], true );
		$settings_tag_fields = get_option( 'elex_settings_tab_fields_data' );
		
			$upload_dir = wp_upload_dir();
			$base = $upload_dir['basedir'];
			$path = $base . '/elex-product-feed/';
		
		unlink( $path . $manage_feed_data['file'] );
		global $wpdb;
		$table_name = $wpdb->prefix . 'gpf_feeds';
		$id = $file_to_delete;
		$query = "DELETE FROM $table_name WHERE feed_id= $id  ";
		$wpdb->query( ( $wpdb->prepare( '%1s', $query ) ? stripslashes( $wpdb->prepare( '%1s', $query ) ) : $wpdb->prepare( '%s', '' ) ), ARRAY_A );
		die();
	}

	public function elex_gpf_manage_feed_refresh_file() {
		check_ajax_referer( 'ajax-elex-gpf-manage-feed-nonce', '_ajax_elex_gpf_manage_feed_nonce' );
		$saved_data = array();
		$file_to_refresh = isset( $_POST['file_to_refresh'] ) ? sanitize_text_field( $_POST['file_to_refresh'] ) : '';
		$saved_data = array_merge( $saved_data, json_decode( elex_gpf_get_feed_data( $file_to_refresh, 'start_filter' )[0]['feed_meta_content'], true ) );
		$saved_data = array_merge( $saved_data, json_decode( elex_gpf_get_feed_data( $file_to_refresh, 'category_select' )[0]['feed_meta_content'], true ) );
		$saved_data = array_merge( $saved_data, json_decode( elex_gpf_get_feed_data( $file_to_refresh, 'attribute_map' )[0]['feed_meta_content'], true ) );
		$saved_data = array_merge( $saved_data, json_decode( elex_gpf_get_feed_data( $file_to_refresh, 'filtering_options' )[0]['feed_meta_content'], true ) );
		foreach ( $saved_data['categories_choosen'] as $index => $cat_id_or_slug ) {
			if ( ! is_numeric( $cat_id_or_slug ) ) { // if category chosen array contains slug instead of category id
			$category_id = get_term_by( 'slug', $cat_id_or_slug, 'product_cat' ); // get the category id using slug name 
				$saved_data['categories_choosen'][ $index ] = $category_id->term_id; //update the slug name with id
			}       
		}
		$saved_data['default_category_chosen'] = '';
		$value = $saved_data;
		$generate_feed_obj = new Elex_Gpf_Ajax_Call();
		$project_title = trim( $value['name'] );
		$project_desc = trim( $value['description'] );
		$project_title = str_replace( ' ', '_', $project_title );
		$autoset_identifier_exists = isset( $value['autoset_identifier_exists'] ) ? $value['autoset_identifier_exists'] : false;
		$product_ids = array();
		$selected_google_product_cats = ( isset( $value['selected_google_product_cats'] ) ) ? $value['selected_google_product_cats'] : null;
		$selected_products = isset( $value['selected_products'] ) ? $value['selected_products'] : null;
		$product_ids = array();
		if ( $selected_google_product_cats && $selected_products && ( count( $selected_products ) == count( $selected_google_product_cats ) && count( $selected_google_product_cats ) > 0 ) ) {
			foreach ( $selected_google_product_cats as $key => $item ) {
				if ( isset( $product_ids[ $selected_google_product_cats[ $key ] ] ) && is_array( $product_ids[ $selected_google_product_cats[ $key ] ] ) ) {
					$product_ids[ $selected_google_product_cats[ $key ] ] = array_unique( array_merge( $product_ids[ $selected_google_product_cats[ $key ] ], $selected_products[ $key ] ) );
				} else {
					$product_ids[ $selected_google_product_cats[ $key ] ] = $selected_products[ $key ];
				}
			}
		}
		if ( isset( $value['categories_choosen'] ) && ! empty( $value['categories_choosen'] ) ) {
			foreach ( $value['categories_choosen'] as $key => $categories ) {
				$cat_cond = '';
				if ( '' == $cat_cond ) {
					$cat_cond = "'" . $categories . "'";
				} else {
					$cat_cond = $cat_cond . ",'" . $categories . "'";
				}
					$ids_to_update = array();
				if ( isset( $product_ids[ $value['sel_google_cats'][ $key ] ] ) && is_array( $product_ids[ $value['sel_google_cats'][ $key ] ] ) ) {
					$ids_to_update = $generate_feed_obj->elex_gpf_get_product_ids( $cat_cond , $value['include_variation'] );
					if ( $ids_to_update ) {
						$product_ids[ $value['sel_google_cats'][ $key ] ]  = array_unique( array_merge( $product_ids[ $value['sel_google_cats'][ $key ] ], $ids_to_update ) );
					}
				} else {
					 $product_ids[ $value['sel_google_cats'][ $key ] ] = $generate_feed_obj->elex_gpf_get_product_ids( $cat_cond , $value['include_variation'] );
				}
			}
		}
		$condition = array();
		$prepend_attr = array();
		$append_attr = array();
		if ( isset( $value['conditions'] ) ) {
			$condition = $value['conditions'];
		}
		if ( isset( $value['prepend_attr'] ) ) {
			$prepend_attr = $value['prepend_attr'];
		}
		if ( isset( $value['append_attr'] ) ) {
			$append_attr = $value['append_attr'];
		}
		$currency_code = isset( $value['currency_conversion_code'] ) ? $value['currency_conversion_code'] : get_woocommerce_currency();
		$stored_stock_status = isset( $value['stock_status'] ) ? $value['stock_status'] : array();
		$is_edit_project = isset( $_POST['is_edit_project'] ) ? sanitize_text_field( isset( $_POST['is_edit_project'] ) ) : '';
		$feed_report = $generate_feed_obj->elex_gpf_create_project( $currency_code, $project_title, $project_desc, $value['sale_country'], $value['sel_google_cats'], $product_ids, $value['prod_attr'], $value['google_attr'], $value['exclude_ids'], $autoset_identifier_exists, $condition, $prepend_attr, $append_attr, $value['feed_file_type'], $value['currency_conversion'], $value['featured'], $value['stock_check'], $value['stock_quantity'], $value['prod_sold_check'], $value['sold_quantity'], $value['prod_vendor'], $stored_stock_status, false, 0, false, $is_edit_project, $file_to_refresh );
		$feed_reports = array();
		$prev_report_data = json_decode( stripslashes( elex_gpf_get_feed_data( $file_to_refresh, 'report_data' )[0]['feed_meta_content'] ), true );
		$feed_reports = $prev_report_data;
		$feed_reports[ current_time( 'd-m-Y H:i:s' ) ] = $feed_report;
		elex_gpf_update_feed( $file_to_refresh, 'report_data', $feed_reports );
		$manage_feed_data = json_decode( elex_gpf_get_feed_data( $file_to_refresh, 'manage_feed_data' )[0]['feed_meta_content'], true );
		$manage_feed_data['modified_date'] = current_time( 'd-m-Y H:i:s' );
		elex_gpf_update_feed( $file_to_refresh, 'manage_feed_data', $manage_feed_data );
		die();
	}

	public function elex_gpf_get_reports() {
		check_ajax_referer( 'ajax-elex-gpf-manage-feed-nonce', '_ajax_elex_gpf_manage_feed_nonce' );
		$feed_id      = isset( $_POST['feed_id'] ) ? sanitize_text_field( $_POST['feed_id'] ) : '';
		$meta_key     = isset( $_POST['meta_key'] ) ? sanitize_text_field( $_POST['meta_key'] ) : '';
		$reports      = elex_gpf_get_feed_data( $feed_id, $meta_key );
		$meta_content = json_decode( $reports[0]['feed_meta_content'], true );
		$feed_data    = array();
		foreach ( $meta_content as $key => $value ) {
			$feed_data[ $key ] = array();
			$excluded_simple = 0;
			$feed_data[ $key ]['total_simple'] = $meta_content[ $key ]['total_simple'];
			if ( ! empty( $meta_content[ $key ]['simple'] ) ) {
				foreach ( $meta_content[ $key ]['simple'] as $ids ) {
					$excluded_simple += count( $ids );
				}
			}
			$feed_data[ $key ]['excluded_simple']    = $excluded_simple;
		}
		die( json_encode( $feed_data ) );
	}

}

new Elex_Manage_Feeds_Ajax_Function();
