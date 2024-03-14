<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Elex_Cron_Schedule {

	public function __construct() {
		$this->elex_gpf_update_cron_jobs();
	}

	public function elex_gpf_update_cron_jobs() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'gpf_feeds';
		$feed_query = "SELECT DISTINCT(feed_id) FROM $table_name";
		$result = $wpdb->get_results( ( $wpdb->prepare( '%1s', $feed_query ) ? stripslashes( $wpdb->prepare( '%1s', $feed_query ) ) : $wpdb->prepare( '%s', '' ) ), ARRAY_A );
		$feed_ids = wp_list_pluck( $result, 'feed_id' );
		$saved_data = '';
		if ( ! empty( $feed_ids ) ) {
			foreach ( $feed_ids as $key => $id ) {
				$start_filter      = json_decode( elex_gpf_get_feed_data( $id, 'start_filter' )[0]['feed_meta_content'], true );
				$attribute_map     = json_decode( elex_gpf_get_feed_data( $id, 'attribute_map' )[0]['feed_meta_content'], true );
				$category_select   = json_decode( elex_gpf_get_feed_data( $id, 'category_select' )[0]['feed_meta_content'], true );
				$manage_feed_data  = json_decode( elex_gpf_get_feed_data( $id, 'manage_feed_data' )[0]['feed_meta_content'], true );
				$filtering_options = json_decode( elex_gpf_get_feed_data( $id, 'filtering_options' )[0]['feed_meta_content'], true );
				if ( ! empty( $start_filter ) && ! empty( $saved_data ) ) {
					$saved_data = array_merge( $saved_data, $start_filter ); 
				}
				if ( ! empty( $attribute_map ) && ! empty( $saved_data ) ) {
					$saved_data = array_merge( $saved_data, $attribute_map );
				}
				if ( ! empty( $category_select ) && ! empty( $saved_data ) ) {
					$saved_data = array_merge( $saved_data, $category_select );
				}
				if ( ! empty( $manage_feed_data ) && ! empty( $saved_data ) ) {
					$saved_data = array_merge( $saved_data, $manage_feed_data );
				}
				if ( ! empty( $saved_data ) && ! empty( $filtering_options ) ) {
					$saved_data = array_merge( $saved_data, $filtering_options );
				}
				$value      = $saved_data;
				if ( isset( $value['file'] ) && 'ready' == $value['pause_schedule'] ) {
					$run = false;
					$current_time = new DateTime( 'now', wp_timezone() );
					$current_time = $current_time->format( 'H' );
					
					$dateTime = current_time( 'Y-m-d ' . $value['refresh_hour'] . ':00:00' );

					$date = new DateTime( $dateTime, new DateTimeZone( 'Asia/Kolkata' ) );
					$date->setTimezone( new DateTimeZone( wp_timezone_string() ) );
					$converted_date = $date->format( 'H' ); 
					if ( 'weekly' == $value['refresh_schedule'] ) {
						$today = strtolower( current_time( 'l' ) );
						if ( is_array( $value['refresh_days'] ) && in_array( $today, $value['refresh_days'] ) && ( $current_time === $converted_date ) ) {
							$run = true;
							if ( isset( $value['modified_date'] ) && ! ( abs( strtotime( $value['modified_date'] ) - strtotime( current_time( 'd-m-Y' ) ) ) / ( 60 * 60 * 24 ) ) ) {
								$run = false;
							}
						}
					} else if ( 'monthly' == $value['refresh_schedule'] ) {
						 $today = current_time( 'j' );
						if ( is_array( $value['refresh_days'] ) && in_array( $today, $value['refresh_days'] ) && ( $current_time === $converted_date ) ) {
							if ( isset( $value['modified_date'] ) && ! ( abs( strtotime( $value['modified_date'] ) - strtotime( current_time( 'd-m-Y' ) ) ) / ( 60 * 60 * 24 ) ) ) {
								$run = false;
							}
						}
					} else if ( 'daily' == $value['refresh_schedule'] ) {
						if ( $current_time === $converted_date ) {

							$run = true;
							if ( isset( $value['modified_date'] ) && ! ( abs( strtotime( $value['modified_date'] ) - strtotime( current_time( 'd-m-Y' ) ) ) / ( 60 * 60 * 24 ) ) ) {
								$run = false;
							}
						}
					}
					if ( $run ) {
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
									 $product_ids[ $value['sel_google_cats'][ $key ] ] = $generate_feed_obj->elex_gpf_get_product_ids( $cat_cond, $value['include_variation'] );
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
						$currency_code = isset( $value['currency_code'] ) ? $value['currency_code'] : '';
						$stored_stock_status = isset( $value['stock_status'] ) ? $value['stock_status'] : array();

						$feed_report = $generate_feed_obj->elex_gpf_create_project( $currency_code, $project_title, $project_desc, $value['sale_country'], $value['sel_google_cats'], $product_ids, $value['prod_attr'], $value['google_attr'], $value['exclude_ids'], $autoset_identifier_exists, $condition, $prepend_attr, $append_attr, $value['feed_file_type'], $value['currency_conversion'], $value['featured'], $value['stock_check'], $value['stock_quantity'], $value['prod_sold_check'], $value['sold_quantity'], $value['prod_vendor'], $stored_stock_status, false, 0, false, true, $id );
						$feed_reports = array();
						$prev_report_data = json_decode( stripslashes( elex_gpf_get_feed_data( $id, 'report_data' )[0]['feed_meta_content'] ), true );
						$feed_reports = $prev_report_data;
						$feed_reports[ current_time( 'd-m-Y H:i:s' ) ] = $feed_report;
						elex_gpf_update_feed( $id, 'report_data', $feed_reports );
						$manage_feed_data['modified_date'] = current_time( 'd-m-Y H:i:s' );
						elex_gpf_update_feed( $id, 'manage_feed_data', $manage_feed_data );
					}
				}
			}
		}
	}
}

new Elex_Cron_Schedule();
