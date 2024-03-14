<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Elex_Previous_Feeds {

	public function __construct() {
		$this->elex_gpf_get_previous_feeds();
	}

	public function elex_gpf_get_previous_feeds() {
		$previous_feeds = get_option( 'elex_gpf_cron_projects' );
		if ( $previous_feeds && is_array( $previous_feeds ) && ! empty( $previous_feeds ) ) {
			$setting_tab_fields = get_option( 'elex_settings_tab_fields_data' );
			
				$upload_dir = wp_upload_dir();
				$base = $upload_dir['basedir'];
				$path = $base . '/elex-product-feed/';
			$feed_id = 1;
			foreach ( $previous_feeds as $feeds ) {
					$start_filter = array();
					$start_filter['name'] = $feeds['name'];
					$start_filter['description'] = $feeds['description'];
					$start_filter['refresh_schedule'] = $feeds['refresh_schedule'];
					$start_filter['refresh_hour'] = $feeds['refresh_hour'];
					$start_filter['include_variation'] = $feeds['include_variation'];
					$start_filter['featured'] = isset( $feeds['featured'] ) ? $feeds['featured'] : '';
					$start_filter['sale_country'] = $feeds['sale_country'];
					$start_filter['currency_conversion'] = isset( $feeds['currency_conversion'] ) ? $feeds['currency_conversion'] : '';
					$start_filter['autoset_identifier_exists'] = $feeds['autoset_identifier_exists'];
					$start_filter['feed_file_type'] = $feeds['feed_file_type'];
					$start_filter['refresh_days'] = $feeds['refresh_days'];
					$category_select = array();
					$category_select['ids'] = $feeds['ids'];
					$category_select['sel_google_cats'] = $feeds['sel_google_cats'];
					$category_select['categories_choosen'] = $feeds['categories_choosen'];
					$category_select['selected_products'] = $feeds['selected_products'];
					$category_select['selected_google_product_cats'] = $feeds['selected_google_product_cats'];
					$attribute_map = array();
					$attribute_map['google_attr'] = $feeds['google_attr'];
					$attribute_map['prod_attr'] = $feeds['prod_attr'];
				if ( isset( $feeds['conditions'] ) ) {
					 $attribute_map['conditions'] = $feeds['conditions'];
				}
				if ( isset( $feeds['prepend_attr'] ) ) {
					 $attribute_map['prepend_attr'] = $feeds['prepend_attr'];
				}
				if ( isset( $feeds['append_attr'] ) ) {
					 $attribute_map['append_attr'] = $feeds['append_attr'];
				}
					$filtering_options = array();
					$filtering_options['exclude_ids'] = $feeds['exclude_ids'];
					$filtering_options['stock_check'] = isset( $feeds['stock_check'] ) ? $feeds['stock_check'] : '';
					$filtering_options['stock_quantity'] = isset( $feeds['stock_quantity'] ) ? $feeds['stock_quantity'] : '';
					$filtering_options['prod_sold_check'] = isset( $feeds['prod_sold_check'] ) ? $feeds['prod_sold_check'] : '';
					$filtering_options['sold_quantity'] = isset( $feeds['sold_quantity'] ) ? $feeds['sold_quantity'] : '';
					$filtering_options['prod_vendor'] = isset( $feeds['prod_vendor'] ) ? $feeds['prod_vendor'] : '';
					$filtering_options['stock_status'] = isset( $feeds['stock_status'] ) ? $feeds['stock_status'] : array();
					$manage_feed_data = array();
					$manage_feed_data['pause_schedule'] = $feeds['pause_schedule'];
					$manage_feed_data['created_date'] = $feeds['created_date'];
					$manage_feed_data['modified_date'] = $feeds['modified_date'];
					$manage_feed_data['file'] = $feeds['file'];
					$manage_feed_data['name'] = $feeds['name'];
					$manage_feed_data['refresh_schedule'] = $feeds['refresh_schedule'];
					$manage_feed_data['refresh_hour'] = $feeds['refresh_hour'];
					$manage_feed_data['refresh_days'] = $feeds['refresh_days'];
					$update_report_data = array();
					$feed_reports = array();
					$update_report_data['simple']          = array();
					$update_report_data['variation']       = array();
					$update_report_data['total_simple']    = 0;
					$update_report_data['total_variation'] = 0;
					$feed_reports[ $feeds['created_date'] ] = $update_report_data;
					elex_gpf_insert_feed( $feed_id, 'start_filter', $start_filter );
					elex_gpf_insert_feed( $feed_id, 'category_select', $category_select );
					elex_gpf_insert_feed( $feed_id, 'attribute_map', $attribute_map );
					elex_gpf_insert_feed( $feed_id, 'filtering_options', $filtering_options );
					elex_gpf_insert_feed( $feed_id, 'manage_feed_data', $manage_feed_data );
					elex_gpf_insert_feed( $feed_id, 'report_data', $feed_reports );
					$feed_id++;
			}
			delete_option( 'elex_gpf_cron_projects' );
		}
	}
}
new Elex_Previous_Feeds();
