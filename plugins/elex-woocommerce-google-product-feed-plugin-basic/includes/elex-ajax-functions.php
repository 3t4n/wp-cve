<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Elex_Gpf_Ajax_Call {
	private $setting_tab_fields;
	public function __construct() {
		add_action( 'wp_ajax_elex_gpf_show_mapping_fields', array( $this, 'elex_gpf_mapping_settings_field' ) );
		add_action( 'wp_ajax_elex_gpf_generate_feed', array( $this, 'elex_gpf_generate_feed' ) );
		add_action( 'wp_ajax_elex_gpf_manage_feed_edit_file', array( $this, 'elex_gpf_manage_feed_edit_file' ) );
		add_action( 'wp_ajax_elex_gpf_get_exclude_prod_option', array( $this, 'elex_gpf_get_exclude_prod_option' ) );
		add_action( 'wp_ajax_elex_gpf_pause_schedule', array( $this, 'elex_gpf_pause_schedule' ) );
		add_action( 'wp_ajax_check_if_the_feed_exists_gf', array( $this, 'check_if_the_feed_exists' ) );
		$this->setting_tab_fields = get_option( 'elex_settings_tab_fields_data' );

	}
	//check duplicate feed
	public function check_if_the_feed_exists() {
		check_ajax_referer( 'ajax-elex-gpf-nonce', '_ajax_elex_gpf_nonce' );
		if ( isset( $_POST['is_edit_project'] ) ) {
			if ( 'false' === sanitize_text_field( $_POST['is_edit_project'] ) ) {
				$is_edit_project = false;
			} elseif ( 'true' === sanitize_text_field( $_POST['is_edit_project'] ) ) {
				$is_edit_project = true;
			} else {
				$is_edit_project = true;
			}       
		}
		$project_title = isset( $_POST['project_title'] ) ? sanitize_text_field( $_POST['project_title'] ) : '';

		$response = $this->check_for_duplicate_feed( $project_title, $is_edit_project );

	}
	public function check_for_duplicate_feed( $project_title, $is_edit_project ) {
		if ( 'false' === $is_edit_project ) {
			$is_edit_project = false;
		}

		if ( false !== $is_edit_project ) {
			return;
		}
			global $wpdb;

			$result = $wpdb->get_results( 
				$wpdb->prepare( 
					"SELECT feed_meta_content FROM {$wpdb->prefix}gpf_feeds WHERE feed_meta_content LIKE %s AND feed_meta_key = 'manage_feed_data'",
					'%' . $wpdb->esc_like( $project_title ) . '%'
				) 
			);         
		if ( empty( $result ) ) {
			return;
		}  

		foreach ( $result as $row ) {
			$row = json_decode( $row->feed_meta_content, true );

			if ( $project_title === $row['name'] ) {
				wp_send_json( 'same_name' );
				die();
			}
		}
	}
	public function elex_gpf_pause_schedule() {
		check_ajax_referer( 'ajax-elex-gpf-manage-feed-nonce', '_ajax_elex_gpf_manage_feed_nonce' );
		if ( isset( $_POST['file'] ) ) {
			$file = sanitize_text_field( $_POST['file'] );
			$manage_feed_data = json_decode( elex_gpf_get_feed_data( $file, 'manage_feed_data' )[0]['feed_meta_content'], true );
			if ( isset( $_POST['feed_action'] ) && sanitize_text_field( $_POST['feed_action'] ) == 'pause' ) {
				$manage_feed_data['pause_schedule'] = 'paused';
			} else {
				$manage_feed_data['pause_schedule'] = 'ready';
			}
			elex_gpf_update_feed( $file, 'manage_feed_data', $manage_feed_data );
		}
		die();
	}

	public function elex_gpf_get_exclude_prod_option() {
		check_ajax_referer( 'ajax-elex-gpf-manage-feed-nonce', '_ajax_elex_gpf_manage_feed_nonce' );
		$options = '';
		$exclude_prod_ids = isset( $_POST['exclude_prod_ids'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['exclude_prod_ids'] ) ) : array();
		foreach ( $exclude_prod_ids as $product_id ) {
			$product = wc_get_product( $product_id );
			if ( is_object( $product ) ) {
				$options .= '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
			}
		}
		die( json_encode( $options ) );
	}


	public function elex_gpf_generate_feed() {
		check_ajax_referer( 'ajax-elex-gpf-nonce', '_ajax_elex_gpf_nonce' );
		$selected_google_product_cats = ( isset( $_POST['selected_google_product_cats'] ) ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['selected_google_product_cats'] ) ) : null;
		$selected_products = array();
		if ( isset( $_POST['selected_products'] ) ) {
			foreach ( $_POST as $array_key => $array_value ) {
				if ( 'selected_products' == $array_key ) {
					foreach ( $array_value as $key => $value ) {
						$selected_products[ $key ] = array_map( 'sanitize_text_field', wp_unslash( $value ) );
					}
				}
			}       
		}
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
		$choosen_cat = '';
		$variation = isset( $_POST['include_variation'] ) ? sanitize_text_field( $_POST['include_variation'] ) : false;

		if ( isset( $_POST['categories_choosen'] ) && ! empty( $_POST['categories_choosen'] ) && isset( $_POST['sel_google_cats'] ) ) {
			foreach ( $_POST as $categories_choosen_key => $categories_choosen_value ) {
				if ( 'categories_choosen' == $categories_choosen_key ) {
					
					foreach ( $categories_choosen_value as $key => $val ) {
						$cat_cond = '';
						if ( isset( $_POST['sel_google_cats'][ $key ] ) ) {
							$cat_cond = ''; 
							$cat_cond = "'" . sanitize_text_field( $val ) . "'";
						} else {
							$cat_cond = $cat_cond . ",'" . sanitize_text_field( $val ) . "'";
						}
						$ids_to_update = array();
						$cats_index = sanitize_text_field( $_POST['sel_google_cats'][ $key ] );

						if ( isset( $product_ids[ $cats_index ] ) && is_array( $product_ids[ $cats_index ] ) ) {
							$ids_to_update = $this->elex_gpf_get_product_ids( $cat_cond , $variation );
							if ( $ids_to_update ) {
								$product_ids[ $cats_index ]  = array_unique( array_merge( $product_ids[ $cats_index ], $ids_to_update ) );
							}
						} else {
							 $product_ids[ $cats_index ] = $this->elex_gpf_get_product_ids( $cat_cond , $variation );
						}
						$choosen_cat = $cats_index;
					}
				}
			}       
		} else if ( isset( $_POST['categories_choosen'] ) && ! empty( $_POST['categories_choosen'] ) && ! isset( $_POST['sel_google_cats'] ) ) {
			$choosen_cat = 'no_category_selected';

			$cat_cond = '';
			foreach ( $_POST as $categories_choosen_key => $categories_choosen_value ) {
				if ( 'categories_choosen' == $categories_choosen_key ) {
					foreach ( $categories_choosen_value as $key => $categories ) {
						if ( '' == $cat_cond ) {
							$cat_cond = "'" . sanitize_text_field( $categories ) . "'";
						} else {
							$cat_cond = $cat_cond . ",'" . sanitize_text_field( $categories ) . "'";
						}
					}   
				}
			}
			$ids_to_update = $this->elex_gpf_get_product_ids( $cat_cond , $variation );

			if ( $ids_to_update ) {
				$product_ids[ $choosen_cat ]  = array_unique( $ids_to_update );
			}
		}

		if ( empty( $product_ids ) ) {
			return;
		}
		$more_than_hundred = false;
		$last_chunk = false;
		if ( isset( $_POST['categories_choosen'] ) ) {
			if ( isset( $_POST['chunk'] ) ) {
				$chunk = sanitize_text_field( $_POST['chunk'] );
				if ( count( $product_ids[ $choosen_cat ] ) > ( 100 + ( $chunk * 100 ) ) ) {
					$product_ids[ $choosen_cat ] = array_slice( $product_ids[ $choosen_cat ], ( $chunk * 100 ), 100 );
					$more_than_hundred = true;
				} elseif ( $chunk > 0 ) {
					$product_ids[ $choosen_cat ] = array_slice( $product_ids[ $choosen_cat ], ( $chunk * 100 ) );
					$more_than_hundred = true;
					$last_chunk = true;
				}
			}
		}

		$project_title = isset( $_POST['project_title'] ) ? trim( sanitize_text_field( $_POST['project_title'] ) ) : '';
		$project_desc  = isset( $_POST['description'] ) ? sanitize_text_field( $_POST['description'] ) : '';
		$file_title    = str_replace( ' ', '_', $project_title );
		$default_category_chosen = ( ! empty( $_POST['default_category_chosen'] ) ) ? sanitize_text_field( $_POST['default_category_chosen'] ) : '';
		$condition = array();
		$prepend_attr = array();
		$append_attr = array();

		if ( isset( $_POST['conditions'] ) ) {
			foreach ( $_POST as $array_key => $array_value ) {
				if ( 'conditions' == $array_key ) {
					if ( is_array( $array_value ) ) {
						foreach ( $array_value as $key => $value ) {
							$condition[ $key ] = $value;
							if ( is_array( $value ) ) {
								foreach ( $value as $key_2 => $value_2 ) {
									$condition[ $key ][ $key_2 ] = $value_2;
									if ( is_array( $value_2 ) ) {
										foreach ( $value_2 as $key_3 => $value_3 ) {
											$condition[ $key ][ $key_2 ][ $key_3 ] = $value_3;
											if ( is_array( $value_3 ) ) {
												foreach ( $value_3 as $key_4 => $value_4 ) {
													$condition[ $key ][ $key_2 ][ $key_3 ][ $key_4 ] = $value_4;
													if ( is_array( $value_4 ) ) {
														foreach ( $value_4 as $key_5 => $value_5 ) {
															$condition[ $key ][ $key_2 ][ $key_3 ][ $key_4 ][ $key_5 ] = sanitize_text_field( $value_5 );
														}
													} else {
														$condition[ $key ][ $key_2 ][ $key_3 ][ $key_4 ] = sanitize_text_field( $value_4 );

													}
												}
											} else {
												$condition[ $key ][ $key_2 ][ $key_3 ] = sanitize_text_field( $value_3 );
											}
										}
									}
								}
							}                       
						}
					}               
				}           
			}       
		}
 
		if ( isset( $_POST['prepend_attr'] ) ) {
			foreach ( $_POST as $array_key => $array_value ) {
				if ( 'prepend_attr' == $array_key ) {
					foreach ( $array_value as $key => $value ) {
						$prepend_attr[ $key ] = $value;
						foreach ( $value as $key_2 => $value_2 ) {
							$prepend_attr[ $key ][ $key_2 ] = array_map( 'sanitize_text_field', wp_unslash( $value_2 ) );
						}
					}
				}
			}       
		}
	

		if ( isset( $_POST['append_attr'] ) ) {
			foreach ( $_POST as $array_key => $array_value ) {
				if ( 'append_attr' == $array_key ) {
					foreach ( $array_value as $key => $value ) {
						$append_attr[ $key ] = $value;
						foreach ( $value as $key_2 => $value_2 ) {
							$append_attr[ $key ][ $key_2 ] = array_map( 'sanitize_text_field', wp_unslash( $value_2 ) );
						}
					}
				}
			}
		}
	

		$exclude_ids = '';
		$prod_vendors = '';
		if ( isset( $_POST['exclude_ids'] ) ) {
			$exclude_ids = array_map( 'sanitize_text_field', wp_unslash( $_POST['exclude_ids'] ) );
		}
		if ( isset( $_POST['prod_vendor'] ) ) {
			$prod_vendors = array_map( 'sanitize_text_field', wp_unslash( $_POST['prod_vendor'] ) );
		}
		if ( isset( $_POST['currency_conversion_code'] ) && ! empty( $_POST['currency_conversion_code'] ) ) {
			$currency_code = sanitize_text_field( $_POST['currency_conversion_code'] );
		} else {
			$currency_code = get_woocommerce_currency();
		}
		$currency_conversion = isset( $_POST['currency_conversion'] ) ? sanitize_text_field( $_POST['currency_conversion'] ) : '';
		global $wpdb;
		$table_name = $wpdb->prefix . 'gpf_feeds';
		$feed_id_query = "SELECT MAX(feed_id) FROM $table_name";

		$result = $wpdb->get_results( ( $wpdb->prepare( '%1s', $feed_id_query ) ? stripslashes( $wpdb->prepare( '%1s', $feed_id_query ) ) : $wpdb->prepare( '%s', '' ) ), ARRAY_A );
		$feed_id = wp_list_pluck( $result, 'MAX(feed_id)' );

		if ( ! $feed_id ) {
			$feed_id = 1;
		} elseif ( ! sanitize_text_field( $_POST['chunk'] ) && isset( $_POST['is_edit_project'] ) && 'true' != $_POST['is_edit_project'] ) {
			$feed_id = $feed_id[0] + 1;
		} elseif ( isset( $_POST['is_edit_project'] ) && 'true' == $_POST['is_edit_project'] ) {
			$feed_id = isset( $_POST['file_to_edit'] ) ? sanitize_text_field( $_POST['file_to_edit'] ) : '';

		} else {
			$feed_id = $feed_id[0];

		}
		$sale_country              = isset( $_POST['sale_country'] ) ? sanitize_text_field( $_POST['sale_country'] ) : '';
		$sel_google_cats           = isset( $_POST['sel_google_cats'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['sel_google_cats'] ) ) : array();
		$prod_attr                 = isset( $_POST['prod_attr'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['prod_attr'] ) ) : '';
		$google_attr               = isset( $_POST['google_attr'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['google_attr'] ) ) : '';
		$autoset_identifier_exists = isset( $_POST['autoset_identifier_exists'] ) ? sanitize_text_field( $_POST['autoset_identifier_exists'] ) : '';
		$feed_file_type            = isset( $_POST['feed_file_type'] ) ? sanitize_text_field( $_POST['feed_file_type'] ) : '';
		$currency_conversion       = isset( $_POST['currency_conversion'] ) ? sanitize_text_field( $_POST['currency_conversion'] ) : '';
		$featured                  = isset( $_POST['featured'] ) ? sanitize_text_field( $_POST['featured'] ) : '';
		$stock_check               = isset( $_POST['stock_check'] ) ? sanitize_text_field( $_POST['stock_check'] ) : '';
		$stock_quantity            = isset( $_POST['stock_quantity'] ) ? sanitize_text_field( $_POST['stock_quantity'] ) : '';
		$prod_sold_check           = isset( $_POST['prod_sold_check'] ) ? sanitize_text_field( $_POST['prod_sold_check'] ) : '';
		$sold_quantity             = isset( $_POST['sold_quantity'] ) ? sanitize_text_field( $_POST['sold_quantity'] ) : '';
		$chunk                     = isset( $_POST['chunk'] ) ? sanitize_text_field( $_POST['chunk'] ) : '';
		$is_edit_project           = isset( $_POST['is_edit_project'] ) ? sanitize_text_field( $_POST['is_edit_project'] ) : '';
		$file_to_edit              = isset( $_POST['file_to_edit'] ) ? sanitize_text_field( $_POST['file_to_edit'] ) : '';
		$stock_status = isset( $_POST['stock_status'] ) ? map_deep( $_POST['stock_status'], 'sanitize_text_field' ) : array();
		$feed_report = $this->elex_gpf_create_project( $currency_code, $file_title, $project_desc, $sale_country, $sel_google_cats, $product_ids, $prod_attr, $google_attr, $exclude_ids, $autoset_identifier_exists, $condition, $prepend_attr, $append_attr, $feed_file_type, $currency_conversion, $featured, $stock_check, $stock_quantity, $prod_sold_check, $sold_quantity, $prod_vendors, $stock_status, $last_chunk, $chunk, $more_than_hundred, $is_edit_project, $file_to_edit );
		$start_filter = array();
		$start_filter['name']                      = $project_title;
		$start_filter['description']               = $project_desc;
		$start_filter['default_category_chosen']   = $default_category_chosen;
		$start_filter['refresh_schedule']          = isset( $_POST['refresh_schedule'] ) ? sanitize_text_field( $_POST['refresh_schedule'] ) : '';
		$start_filter['refresh_hour']              = isset( $_POST['refresh_hour'] ) ? sanitize_text_field( $_POST['refresh_hour'] ) : '';
		$start_filter['include_variation']         = isset( $_POST['include_variation'] ) ? sanitize_text_field( $_POST['include_variation'] ) : '';
		$start_filter['featured']                  = $featured;
		$start_filter['sale_country']              = $sale_country;
		$start_filter['currency_conversion']       = $currency_conversion;
		$start_filter['currency_conversion_code']  = $currency_code;
		$start_filter['autoset_identifier_exists'] = $autoset_identifier_exists;
		$start_filter['feed_file_type']            = $feed_file_type;
		$start_filter['refresh_days']              = ( isset( $_POST['refresh_days'] ) && is_array( $_POST['refresh_days'] ) ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['refresh_days'] ) ) : array( strtolower( current_time( 'l' ) ) );
		$category_select = array();
		$category_select['ids'] = $product_ids;
		$category_select['sel_google_cats'] = $sel_google_cats;
		$category_select['categories_choosen'] = ( isset( $_POST['categories_choosen'] ) && is_array( $_POST['categories_choosen'] ) ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['categories_choosen'] ) ) : '';
		$category_select['selected_products'] = $selected_products;
		$category_select['selected_google_product_cats'] = $selected_google_product_cats;
		$attribute_map = array();
		$attribute_map['google_attr'] = $google_attr;
		$attribute_map['prod_attr'] = $prod_attr;
		if ( ! empty( $condition ) ) {
			 $attribute_map['conditions'] = $condition;
		}
		if ( ! empty( $prepend_attr ) ) {
			 $attribute_map['prepend_attr'] = $prepend_attr;
		}
		if ( ! empty( $append_attr ) ) {
			 $attribute_map['append_attr'] = $append_attr;
		}
		$filtering_options = array();
		$filtering_options['exclude_ids'] = $exclude_ids;
		$filtering_options['stock_check'] = $stock_check;
		$filtering_options['stock_quantity'] = $stock_quantity;
		$filtering_options['prod_sold_check'] = $prod_sold_check;
		$filtering_options['sold_quantity'] = $sold_quantity;
		$filtering_options['prod_vendor'] = $prod_vendors;
		$filtering_options['stock_status'] = $stock_status;
		$manage_feed_data = array();
		$manage_feed_data['pause_schedule'] = 'ready';
		if ( isset( $_POST['is_edit_project'] ) && 'true' != $_POST['is_edit_project'] ) {
			$manage_feed_data['created_date'] = current_time( 'd-m-Y H:i:s' );
		} else {
			$prev_manage_feed_data = json_decode( stripslashes( elex_gpf_get_feed_data( $feed_id, 'manage_feed_data' )[0]['feed_meta_content'] ), true );
			$manage_feed_data['created_date'] = $prev_manage_feed_data['created_date'];
		}
		$manage_feed_data['modified_date']    = current_time( 'd-m-Y H:i:s' );
		$manage_feed_data['file']             = $file_title . '.' . $feed_file_type;
		$manage_feed_data['name']             = $project_title;
		$manage_feed_data['refresh_schedule'] = $start_filter['refresh_schedule'];
		$manage_feed_data['refresh_hour']     = $start_filter['refresh_hour'];
		$manage_feed_data['refresh_days']     = $start_filter['refresh_days'];
		if ( $more_than_hundred && ! $last_chunk ) {
			$temp_report_data = elex_gpf_get_feed_data( $feed_id, 'temp_report_data' );
			if ( ! empty( $temp_report_data ) ) {
				$temp_report_data = json_decode( stripslashes( $temp_report_data[0]['feed_meta_content'] ), true );
				$update_report_data = array();
				$update_report_data['simple']          = array_merge_recursive( $temp_report_data['simple'], $feed_report['simple'] );
				$update_report_data['total_simple']    = $temp_report_data['total_simple'] + $feed_report['total_simple'];
				elex_gpf_update_feed( $feed_id, 'temp_report_data', $update_report_data );
			} else {
				elex_gpf_insert_feed( $feed_id, 'temp_report_data', $feed_report );
			}
			die( 'need_to_generate_feed' );
		}
		$temp_report_data = elex_gpf_get_feed_data( $feed_id, 'temp_report_data' );
		$update_report_data = array();
		if ( ! empty( $temp_report_data ) ) {
			$temp_report_data = json_decode( stripslashes( $temp_report_data[0]['feed_meta_content'] ), true );
			$update_report_data['simple']          = array_merge_recursive( $temp_report_data['simple'], $feed_report['simple'] );
			$update_report_data['total_simple']    = $temp_report_data['total_simple'] + $feed_report['total_simple'];

			global $wpdb;
			$table_name = $wpdb->prefix . 'gpf_feeds';
			$id = $feed_id;
			$meta_key = 'temp_report_data';
			$query = "DELETE FROM $table_name WHERE (feed_id= $id AND feed_meta_key = '$meta_key') ";
			$wpdb->query( ( $wpdb->prepare( '%1s', $query ) ? stripslashes( $wpdb->prepare( '%1s', $query ) ) : $wpdb->prepare( '%s', '' ) ), ARRAY_A );
		} else {
			$update_report_data = $feed_report;
		}

		$feed_reports = array();
		$current_time = current_time( 'd-m-Y H:i:s' );
		if ( isset( $_POST['is_edit_project'] ) && 'true' == $_POST['is_edit_project'] ) {
			$prev_report_data = json_decode( stripslashes( elex_gpf_get_feed_data( $feed_id, 'report_data' )[0]['feed_meta_content'] ), true );
			$feed_reports = $prev_report_data;
			$feed_reports[ $current_time ] = $update_report_data;
			elex_gpf_update_feed( $feed_id, 'start_filter', $start_filter );
			elex_gpf_update_feed( $feed_id, 'category_select', $category_select );
			elex_gpf_update_feed( $feed_id, 'attribute_map', $attribute_map );
			elex_gpf_update_feed( $feed_id, 'filtering_options', $filtering_options );
			elex_gpf_update_feed( $feed_id, 'manage_feed_data', $manage_feed_data );
			elex_gpf_update_feed( $feed_id, 'report_data', $feed_reports );
		} else {
			$feed_reports[ $current_time ] = $update_report_data;
			elex_gpf_insert_feed( $feed_id, 'start_filter', $start_filter );
			elex_gpf_insert_feed( $feed_id, 'category_select', $category_select );
			elex_gpf_insert_feed( $feed_id, 'attribute_map', $attribute_map );
			elex_gpf_insert_feed( $feed_id, 'filtering_options', $filtering_options );
			elex_gpf_insert_feed( $feed_id, 'manage_feed_data', $manage_feed_data );
			elex_gpf_insert_feed( $feed_id, 'report_data', $feed_reports );
		}
		$simple_excluded    = 'no';
		if ( isset( $feed_reports[ $current_time ] ) ) {
			if ( ! empty( $feed_reports[ $current_time ]['simple'] ) ) {
				$simple_excluded    = 'yes';
			}
		}
		$feed_success = array(
			'status' => 'done',
			'feed_id' => $feed_id,
			'current_time' => $current_time,
			'simple_excluded' => $simple_excluded,
		);
		die( json_encode( $feed_success ) );
	}

	public function elex_gpf_required_attr_to_exclude_prod() {
		return array(
			'id',
			'title',
			'description',
			'link',
			'image_link',
			'availability',
			'price',
		);
	}

	public function elex_gpf_create_project( $currency_code, $project_title, $project_desc, $sale_country, $sel_google_cats, $product_ids, $prod_attr, $google_attr, $exclude_ids, $autoset_identifier_exists, $condition, $prepend_attr, $append_attr, $mime_type, $currency_conversion, $featured, $stock_check, $stock_quantity, $prod_sold_check, $sold_quantity, $prod_vendor, $stock_status, $last_chunk = false, $chunk = 0, $more_than_hundred = false, $is_edit_project = '', $file_to_edit_or_refresh = '' ) {
		$seperated_value = ',';
		$file_mime_type  = '.csv';

		if ( 'tsv' == $mime_type ) {
			$seperated_value = "\t";
			$file_mime_type = '.tsv';
		}

		$required_attr_to_exclude_prod = $this->elex_gpf_required_attr_to_exclude_prod();
		$feed_report_products       = array( 'simple' => array() );
		$total_simple               = 0;
		
		$upload_dir = wp_upload_dir();
		$base = $upload_dir['basedir'];
		$path = $base . '/elex-product-feed/';
		if ( 'false' === $is_edit_project ) {
			$is_edit_project = false;
		}
		
		if ( false === $is_edit_project ) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'gpf_feeds';
			$feed_id_query = "SELECT * FROM $table_name Where feed_meta_key = 'manage_feed_data' ";
			$result = $wpdb->get_results( ( $wpdb->prepare( '%1s', $feed_id_query ) ? stripslashes( $wpdb->prepare( '%1s', $feed_id_query ) ) : $wpdb->prepare( '%s', '' ) ), ARRAY_A );
			foreach ( $result as $index => $value ) {
				$saved_feed_data = $value['feed_meta_content'];
				$saved_feed_data = json_decode( $saved_feed_data, true );
				if ( str_replace( ' ', '_', $saved_feed_data['name'] ) == $project_title ) {
					die( 'same_name' );
				}
			}
		} else {
			$manage_feed_data = json_decode( elex_gpf_get_feed_data( $file_to_edit_or_refresh, 'manage_feed_data' )[0]['feed_meta_content'], true );
			if ( file_exists( $path . $manage_feed_data['file'] ) ) {
				unlink( $path . $manage_feed_data['file'] );
			}
		}
		$original_project_title = $project_title;
		if ( $more_than_hundred ) {
			$project_title = $project_title . '_chunk' . $chunk;
		}
		$file = $path . '/' . $project_title . $file_mime_type;
		if ( ! file_exists( $path ) ) {
			wp_mkdir_p( $path );
		}
		$csv = '';
		$ship_tax_details = array();
		$ship_or_tax = array();
		foreach ( $google_attr as $key => $value ) {
			if ( substr( $value, 0, 9 ) == 'shipping-' ) {
				array_push( $ship_tax_details, $value );
				if ( ! in_array( 'shipping', $ship_or_tax ) ) {
					array_push( $ship_or_tax, 'shipping' );
				}
			} elseif ( substr( $value, 0, 3 ) == 'tax' ) {
				array_push( $ship_tax_details, $value );
				if ( ! in_array( 'tax', $ship_or_tax ) ) {
					array_push( $ship_or_tax, 'tax' );
				}
			} elseif ( substr( $value, 0, 14 ) == 'product_detail' ) {
				array_push( $ship_tax_details, $value );
				if ( ! in_array( 'product_detail', $ship_or_tax ) ) {
					array_push( $ship_or_tax, 'product_detail' );
				}
			} elseif ( substr( $value, 0, 14 ) == 'product_detail' ) {
				array_push( $ship_tax_details, $value );
				if ( ! in_array( 'product_detail', $ship_or_tax ) ) {
					array_push( $ship_or_tax, 'product_detail' );
				}
			} elseif ( substr( $value, 0, 17 ) == 'subscription_cost' ) {
				array_push( $ship_tax_details, $value );
				if ( ! in_array( 'subscription_cost', $ship_or_tax ) ) {
					array_push( $ship_or_tax, 'subscription_cost' );
				}
			} else {
				 $csv .= $value . $seperated_value;
			}
		}
		if ( ! empty( $ship_or_tax ) ) {
			if ( in_array( 'shipping', $ship_or_tax ) ) {
				$csv .= 'shipping' . $seperated_value;
			}
			if ( in_array( 'tax', $ship_or_tax ) ) {
				$csv .= 'tax' . $seperated_value;
			}
			if ( in_array( 'product_detail', $ship_or_tax ) ) {
				$csv .= 'product_detail' . $seperated_value;
			}
			if ( in_array( 'subscription_cost', $ship_or_tax ) ) {
				$csv .= 'subscription_cost' . $seperated_value;
			}
		}
		$identifier_set = false;
		if ( ! in_array( 'identifier_exists', $google_attr ) && 'true' == $autoset_identifier_exists ) {
			$csv .= 'identifier_exists,';
			 $identifier_set = true;
		}
		$csv = substr( $csv, 0, -1 );
		$csv .= "\n";
		$product_attributes = $this->elex_gpf_get_product_attributes();
		$updated_ids = array();
		$add_items = '';
		$prefix = '';
		$temp_csv = '';
		foreach ( $product_ids as $key => $val ) {
			$temp_cat = explode( '-', $key );
			$google_cat = trim( $temp_cat[0] );
			foreach ( $val as $ids ) {
				if ( ! in_array( $ids, $updated_ids ) ) {
					array_push( $updated_ids, $ids );
				} else {
					continue;
				}
				$vendor_check = false;
				if ( ( ! is_array( $prod_vendor ) || empty( $prod_vendor ) ) || in_array( get_post_field( 'post_author', $ids ), $prod_vendor ) ) {
					$vendor_check = true;
				}
				if ( ( ! is_array( $exclude_ids ) || ! in_array( $ids, $exclude_ids ) ) && $vendor_check ) {
					$product = wc_get_product( $ids );
					$product_details = $product->get_data();
					$is_featured = true;
					if ( 'true' == $featured ) {
						$is_featured = $product->is_featured();
					}
					if ( $product->is_type( 'simple' ) && $this->elex_gpf_check_stock_and_sold_quantity( $product, $stock_check, $stock_quantity, $prod_sold_check, $sold_quantity, $stock_status ) && $is_featured ) {
						$identifiers_exists = 'no';
						$shipping_data = array();
						$tax_data = array();
						$product_detail_data = array();
						$subscription_cost_data = array();
						$is_gtin_empty = false;
						$google_attr_count = count( $google_attr );
						for ( $i = 0; $i < $google_attr_count; $i++ ) {
							$map_prod_attr_val = '';
							$value_from_condition = '';

							if ( is_array( $condition ) && isset( $condition[ $i ] ) ) {
								$value_from_condition = $this->elex_gpf_get_value_from_condition_simple( $condition[ $i ], $google_attr[ $i ], $ids, $product, $product_details, $prefix, $google_cat, $product_attributes, '', $add_items, $currency_conversion, $sale_country );
							}

							if ( $value_from_condition ) {
								$map_prod_attr_val = $value_from_condition;
							} else {
								$map_prod_attr_val = $this->elex_gpf_get_simple_product_attr_values( $prod_attr[ $i ], $google_attr[ $i ], $ids, $product, $product_details, '', $google_cat, $product_attributes, '', '', $currency_conversion, $sale_country );

								$prepend_text = '';
								if ( isset( $prepend_attr[ $i ] ) ) {
									foreach ( $prepend_attr[ $i ] as $prepend_key => $prepend_value ) {
										$prep_val = $this->elex_gpf_get_simple_product_attr_values( $prepend_value[0], $google_attr[ $i ], $ids, $product, $product_details, '', $google_cat, $product_attributes, '', '', $currency_conversion, $sale_country );

										$prepend_result = $this->elex_gpf_prepend_append_value( $prep_val, $prepend_value[1], 'prepend' );
										$prepend_text .= $prepend_result;
									}
										$map_prod_attr_val = $prepend_text . $map_prod_attr_val;
								}
								$append_text = '';
								if ( isset( $append_attr[ $i ] ) ) {
									foreach ( $append_attr[ $i ] as $append_key => $append_value ) {
										$app_val = $this->elex_gpf_get_simple_product_attr_values( $append_value[0], $google_attr[ $i ], $ids, $product, $product_details, '', $google_cat, $product_attributes, '', '', $currency_conversion, $sale_country );

										$append_result = $this->elex_gpf_prepend_append_value( $app_val, $append_value[1], 'append' );
										$append_text .= $append_result;
									}
										$map_prod_attr_val = $map_prod_attr_val . $append_text;
								}
							}
							if ( 'price' == $google_attr[ $i ] && $map_prod_attr_val ) {
								$map_prod_attr_val = $map_prod_attr_val . ' ' . $currency_code;
							}
							if ( 'sale_price' == $google_attr[ $i ] && $map_prod_attr_val ) {
								$map_prod_attr_val = $map_prod_attr_val . ' ' . $currency_code;
							}
							if ( 'shipping-price' == $google_attr[ $i ] ) {
								$shipping_data['price'] = $map_prod_attr_val;
								continue;
							}
							if ( 'shipping-country' == $google_attr[ $i ] ) {
								$shipping_data['country'] = $map_prod_attr_val;
								continue;
							}
							if ( 'shipping-region' == $google_attr[ $i ] ) {
								$shipping_data['region'] = $map_prod_attr_val;
								continue;
							}
							if ( 'shipping-service' == $google_attr[ $i ] ) {
								$shipping_data['service'] = $map_prod_attr_val;
								continue;
							}
							if ( 'tax-rate' == $google_attr[ $i ] ) {
								$tax_data['rate'] = $map_prod_attr_val;
								continue;
							}
							if ( 'tax-country' == $google_attr[ $i ] ) {
								$tax_data['country'] = $map_prod_attr_val;
								continue;
							}
							if ( 'tax-region' == $google_attr[ $i ] ) {
								$tax_data['region'] = $map_prod_attr_val;
								continue;
							}
							if ( 'tax-tax_ship' == $google_attr[ $i ] ) {
								$tax_data['tax_ship'] = $map_prod_attr_val;
								continue;
							}
							if ( 'product_detail-section_name' == $google_attr[ $i ] ) {
								$product_detail_data['section_name'] = $map_prod_attr_val;
								continue;
							}
							if ( 'product_detail-attribute_name' == $google_attr[ $i ] ) {
								$product_detail_data['attribute_name'] = $map_prod_attr_val;
								continue;
							}
							if ( 'product_detail-attribute_value' == $google_attr[ $i ] ) {
								$product_detail_data['attribute_value'] = $map_prod_attr_val;
								continue;
							}
							if ( 'subscription_cost-period' == $google_attr[ $i ] ) {
								$subscription_cost_data['period'] = $map_prod_attr_val;
								continue;
							}
							if ( 'subscription_cost-period_length' == $google_attr[ $i ] ) {
								$subscription_cost_data['period_length'] = $map_prod_attr_val;
								continue;
							}
							if ( 'subscription_cost-amount' == $google_attr[ $i ] ) {
								$subscription_cost_data['amount'] = $map_prod_attr_val;
								continue;
							}
							if ( ! $map_prod_attr_val && in_array( $google_attr[ $i ], $required_attr_to_exclude_prod ) && 'item_group_id' != $google_attr[ $i ] ) {
										$feed_report_products['simple'][ $google_attr[ $i ] ][] = $ids . '-' . htmlspecialchars( $product->get_title() );
										$temp_csv = '';
										break;
							}
							if ( 'item_group_id' != $google_attr[ $i ] ) {
								$map_prod_attr_val = str_replace( ',', '-', $map_prod_attr_val );
								$map_prod_attr_val = trim( preg_replace( '/\s+/', ' ', $map_prod_attr_val ) );
								$temp_csv .= '"' . htmlspecialchars( $map_prod_attr_val ) . '"' . $seperated_value;
							} else {
								 $temp_csv .= $seperated_value;
							}
							if ( 'gtin' == $google_attr[ $i ] && '' == $map_prod_attr_val ) {
								$is_gtin_empty = true;
							}
						}
						if ( $temp_csv ) {
							$csv .= $temp_csv;
							$csv = $this->elex_get_tax_and_ship_details_csv( $shipping_data, $tax_data, $product_detail_data, $subscription_cost_data, $csv, $seperated_value );
							if ( $identifier_set && $is_gtin_empty ) {
								$csv .= $identifiers_exists . $seperated_value;
							}
							$csv = substr( $csv, 0, -1 );
							$csv .= "\n";
							$temp_csv = '';
							$total_simple++;
						}
					}
				}
			}
		}
		$csv_handler = fopen( $file, 'w' );
		fwrite( $csv_handler, $csv );
		fclose( $csv_handler );
		$files = '';
		if ( $more_than_hundred && $last_chunk ) {
			$files = array();
			for ( $i = 0;$i <= $chunk;$i++ ) {
				$files[] = $path . '/' . $original_project_title . '_chunk' . $i . $file_mime_type;
			}
		}
		if ( is_array( $files ) ) {
			$file_path = $path . '/' . $original_project_title . $file_mime_type;
			$this->elex_gpf_join_files( $files, $file_path, $file_mime_type );
			for ( $i = 0;$i <= $chunk;$i++ ) {
				unlink( $path . '/' . $original_project_title . '_chunk' . $i . $file_mime_type );
			}
		}

		if ( ( is_array( $files ) || ! $more_than_hundred ) && 'xml' == $mime_type ) {
			$this->elex_gpf_convert_to_xml( $path . '/' . $original_project_title, $project_title, $project_desc );
			unlink( $path . '/' . $original_project_title . '.csv' );
		}

		$feed_report_products['total_simple'] = $total_simple;

		return $feed_report_products;
	}

	public function elex_gpf_convert_to_xml( $path, $project_title, $project_desc ) {
		// Map CSV file to array
		$rows = array_map( 'str_getcsv', file( $path . '.csv' ) );
		$header = array_shift( $rows );
		$data = array();
		foreach ( $rows as $row ) {
			if ( count( $header ) > count( $row ) ) {
				$row[] = '';
			}
			$data[] = array_combine( $header, $row );
		}
		//Creates XML string and XML document using the DOM
		$xml = new DomDocument( '1.0', 'UTF-8' );
		$rss = $xml->createElement( 'rss' );
		$rss->setAttribute( 'xmlns:g', 'http://base.google.com/ns/1.0' );
		$rss->setAttribute( 'version', '2.0' );
		//Add root node
		$xml->appendChild( $rss );
		$root = $xml->createElement( 'channel' );
		$rss->appendChild( $root );
		//Add title node
		$title_node  = $xml->createElement( 'title' );
		$root->appendChild( $title_node );
		$title_node->appendChild( $xml->createCDATASection( $project_title ) );
		//Add Link node
		$link_node  = $xml->createElement( 'link' );
		$root->appendChild( $link_node );
		$link_node->appendChild( $xml->createCDATASection( site_url() ) );
		//Add description node
		$title_node  = $xml->createElement( 'description' );
		$root->appendChild( $title_node );
		$title_node->appendChild( $xml->createCDATASection( $project_desc ) );
		// Add child nodes
		$multiple_attr_keys = array( 'shipping', 'tax', 'subscription_cost', 'product_detail' );
		foreach ( $data as $key => $val ) {
			$entry = $xml->createElement( 'item' );
			$root->appendChild( $entry );
			foreach ( $val as $field_name => $field_value ) {
				$field_name = $field_name;//preg_replace("/[^A-Za-z0-9]/", '', $field_name); // preg_replace has the allowed characters
				$name = $entry->appendChild( $xml->createElement( 'g:' . $field_name ) );
				if ( in_array( $field_name, $multiple_attr_keys ) ) {
					$multi_attr_values = explode( ':', $field_value );
					if ( count( $multi_attr_values ) ) {
						$multilevel_attributes = $this->elex_gpf_get_multilevel_attributes();
						foreach ( $multi_attr_values as $index => $value_to_be_mapped ) {
							if ( $value_to_be_mapped ) {
								$multi_level = $name->appendChild( $xml->createElement( 'g:' . $multilevel_attributes[ $field_name ][ $index ] ) );
								$multi_level->appendChild( $xml->createCDATASection( $value_to_be_mapped ) );
							}
						}
					}
				} else {
					$name->appendChild( $xml->createCDATASection( $field_value ) );
				}
			}
		}
		// Set the formatOutput attribute of xml to true
		$xml->formatOutput = true;
		// Save as file
		$xml->save( $path . '.xml' ); // save as fil
	}

	public function elex_gpf_get_multilevel_attributes() {
		return array(
			'shipping' => array(
				'country',
				'region',
				'service',
				'price',
			),
			'tax' => array(
				'country',
				'region',
				'rate',
				'tax_ship',
			),
			'subscription_cost' => array(
				'period',
				'period_length',
				'amount',
			),
			'product_detail' => array(
				'section_name',
				'attribute_name',
				'attribute_value',
			),
		);
	}


	public function elex_gpf_join_files( array $files, $result, $file_mime_type ) {
		$delimeter = ',';
		if ( '.tsv' == $file_mime_type ) {
			$delimeter = "\t";
		}
		$wh = fopen( $result, 'w+' );
		$i = 0;
		foreach ( $files as $file_path ) {
			$file = fopen( $file_path, 'r' );
			if ( 0 != $i ) {
				$data = array();
				while ( ( $line = fgetcsv( $file, 0, $delimeter ) ) !== false ) {
					// Store every line in an array
					$data[] = $line;
				}
				fclose( $file );

				// Remove the first element from the stored array / first line of file being read
				array_shift( $data );

				// Open file for writing
				$file = fopen( $file_path, 'w' );

				// Write remaining lines to file
				foreach ( $data as $fields ) {
					fputcsv( $file, $fields, $delimeter );
				}
				fclose( $file );
			}
			$i++;
			$fh = fopen( $file_path, 'r' );
			while ( ! feof( $fh ) ) {
				fwrite( $wh, fgets( $fh ) );
			}
			fclose( $fh );
			unset( $fh );
			// fwrite($wh, "\n"); //usually last line doesn't have a newline
		}
		fclose( $wh );
		unset( $wh );
	}

	public function elex_get_tax_and_ship_details_csv( $shipping_data, $tax_data, $product_detail_data, $subscription_cost_data, $csv, $seperated_value ) {
		if ( ! empty( $shipping_data ) ) {
				$ship_keys = array_keys( $shipping_data );
			if ( in_array( 'country', $ship_keys ) ) {
				$csv .= $shipping_data['country'] . ':';
			} else {
				$csv .= ':';
			}
			if ( in_array( 'region', $ship_keys ) ) {
				$csv .= $shipping_data['region'] . ':';
			} else {
				$csv .= ':';
			}
			if ( in_array( 'service', $ship_keys ) ) {
				$csv .= $shipping_data['service'] . ':';
			} else {
				$csv .= ':';
			}
			if ( in_array( 'price', $ship_keys ) ) {
				$csv .= $shipping_data['price'] . $seperated_value;
			} else {
				$csv .= $seperated_value;
			}
		}
		if ( ! empty( $tax_data ) ) {
			$tax_keys = array_keys( $tax_data );
			if ( in_array( 'country', $tax_keys ) ) {
				$csv .= $tax_data['country'] . ':';
			} else {
				$csv .= ':';
			}
			if ( in_array( 'region', $tax_keys ) ) {
				$csv .= $tax_data['region'] . ':';
			} else {
				$csv .= ':';
			}
			if ( in_array( 'rate', $tax_keys ) ) {
				$csv .= $tax_data['rate'] . ':';
			} else {
				$csv .= ':';
			}
			if ( in_array( 'tax_ship', $tax_keys ) ) {
				$csv .= $tax_data['tax_ship'] . $seperated_value;
			} else {
				$csv .= $seperated_value;
			}
		}
		if ( ! empty( $product_detail_data ) ) {
			$product_detail_keys = array_keys( $product_detail_data );
			if ( in_array( 'section_name', $product_detail_keys ) ) {
				$csv .= $product_detail_data['section_name'] . ':';
			} else {
				$csv .= ':';
			}
			if ( in_array( 'attribute_name', $product_detail_keys ) ) {
				$csv .= $product_detail_data['attribute_name'] . ':';
			} else {
				$csv .= ':';
			}
			if ( in_array( 'attribute_value', $product_detail_keys ) ) {
				$csv .= $product_detail_data['attribute_value'] . $seperated_value;
			} else {
				$csv .= $seperated_value;
			}
		}
		if ( ! empty( $subscription_cost_data ) ) {
			$subscription_cost_keys = array_keys( $subscription_cost_data );
			if ( in_array( 'period', $subscription_cost_keys ) ) {
				$csv .= $subscription_cost_data['period'] . ':';
			} else {
				$csv .= ':';
			}
			if ( in_array( 'period_length', $subscription_cost_keys ) ) {
				$csv .= $subscription_cost_data['period_length'] . ':';
			} else {
				$csv .= ':';
			}
			if ( in_array( 'amount', $subscription_cost_keys ) ) {
				$csv .= $subscription_cost_data['amount'] . $seperated_value;
			} else {
				$csv .= $seperated_value;
			}
		}
		return $csv;
	}

	public function elex_gpf_get_value_from_condition_simple( $conditions, $google_attr, $ids, $product, $product_details, $prefix, $google_cat, $product_attributes, $autoset_identifier_exists, $add_items, $currency_conversion, $sale_country ) {
		$success = false;
		$map_attr_val = '';
		foreach ( $conditions as  $condition ) {
			foreach ( $condition[0] as $value ) {
				if ( $value[0] && $value[2] ) {
					$attr_val = $this->elex_gpf_get_simple_product_attr_values( $value[0], $google_attr, $ids, $product, $product_details, $prefix, $google_cat, $product_attributes, $autoset_identifier_exists, $add_items, $currency_conversion, $sale_country );
					if ( $attr_val ) {
						$check_cond = $this->elex_gpf_check_condition( $attr_val, $value[1], $value[2] );
						$success = $check_cond;
						if ( $check_cond && 'OR' == $condition[1] ) {
							$success = true;
							break;
						} else if ( ! $check_cond && 'AND' == $condition[1] ) {
							$success = false;
							break;
						}
					}
				}
			}
			if ( $success ) {
				if ( $condition[2] ) {
					if ( ( strpos( $condition[2], 'elex_text_val', 0 ) === 0 ) ) {
						$map_attr_val = str_replace( 'elex_text_val', '', $condition[2] );
					} else {
						$map_attr_val = $this->elex_gpf_get_simple_product_attr_values( $condition[2], $google_attr, $ids, $product, $product_details, $prefix, $google_cat, $product_attributes, $autoset_identifier_exists, $add_items, $currency_conversion, $sale_country );
					}
				}
				if ( isset( $condition[3] ) && is_array( $condition[3] ) ) {
					$prepend_value = '';
					foreach ( $condition[3] as $value ) {
						if ( $value[0] ) {
							$value_to_prepend = $this->elex_gpf_get_simple_product_attr_values( $value[0], $google_attr, $ids, $product, $product_details, $prefix, $google_cat, $product_attributes, $autoset_identifier_exists, $add_items, $currency_conversion, $sale_country );

							if ( $value_to_prepend ) {
								$prepend_value .= $this->elex_gpf_prepend_append_value( $value_to_prepend, $value[1], 'prepend' );
							}
						}
					}
					$map_attr_val = $prepend_value . ' ' . $map_attr_val;
				}
				if ( isset( $condition[4] ) && is_array( $condition[4] ) ) {
					$append_value = '';
					foreach ( $condition[4] as $value ) {
						if ( $value[0] ) {
							$value_to_append = $this->elex_gpf_get_simple_product_attr_values( $value[0], $google_attr, $ids, $product, $product_details, $prefix, $google_cat, $product_attributes, $autoset_identifier_exists, $add_items, $currency_conversion, $sale_country );

							if ( $value_to_append ) {
								$append_value .= $this->elex_gpf_prepend_append_value( $value_to_append, $value[1], 'append' );
							}
						}
					}
					$map_attr_val .= ' ' . $append_value;
				}

				break;
			}
		}
		return $map_attr_val;
	}

	public function elex_gpf_check_condition( $attr_val, $condition_param, $compare_with ) {
		$check_cond = false;
		switch ( $condition_param ) {
			case 'contains':
				if ( strpos( $attr_val, $compare_with ) !== false ) {
					$check_cond = true;
				}
				break;
			case 'string_equals':
				if ( $attr_val == $compare_with ) {
					$check_cond = true;
				}
				break;
			case 'starts_with':
				if ( substr( $attr_val, 0, strlen( $compare_with ) ) === $compare_with ) {
					$check_cond = true;
				}
				break;
			case 'ends_with':
				if ( substr( $attr_val, -strlen( $compare_with ) ) === $compare_with ) {
					$check_cond = true;
				}
				break;
			case 'less_than':
				if ( $attr_val < $compare_with ) {
					$check_cond = true;
				}
				break;
			case 'less_than_equal':
				if ( $attr_val <= $compare_with ) {
					$check_cond = true;
				}
				break;
			case 'greater_than':
				if ( $attr_val > $compare_with ) {
					$check_cond = true;
				}
				break;
			case 'greater_than_equal':
				if ( $attr_val >= $compare_with ) {
					$check_cond = true;
				}
				break;
			case 'arith_equals':
				if ( $attr_val == $compare_with ) {
					$check_cond = true;
				}
				break;
			default:
				$check_cond = false;
				break;
		}
		return $check_cond;
	}

	public function elex_gpf_prepend_append_value( $attr_val, $delimeter, $action ) {
		$add_delimeter = '';
		switch ( $delimeter ) {
			case 'space':
				$add_delimeter .= ' ';
				break;
			case 'comma':
				$add_delimeter .= ',';
				break;
			case 'dot':
				$add_delimeter .= '.';
				break;
			case 'less_than':
				$add_delimeter .= '<';
				break;
			case 'greater_than':
				$add_delimeter .= '>';
				break;
			case 'equals':
				$add_delimeter .= '=';
				break;
			case 'double_equals':
				$add_delimeter .= '==';
				break;
			case 'semicolon':
				$add_delimeter .= ';';
				break;
			case 'pipe':
				$add_delimeter .= '|';
				break;
			case 'backslash':
				$add_delimeter .= "\'";
				break;
			case 'forward_slash':
				$add_delimeter .= '/';
				break;
			default:
				$add_delimeter .= '';
				break;
		}
		if ( 'prepend' == $action ) {
			$attr_val .= $add_delimeter;
		} else {
			$attr_val = $add_delimeter . $attr_val;
		}
		return $attr_val;
	}
	//check stock quantity.
	public function check_stock_quantity( $product, $stock_check, $stock_quantity ) {
		if ( empty( $stock_check ) || '' === $stock_check ) {
			return true;
		}
		if ( empty( $product->get_manage_stock() && 'instock' === $product->get_stock_status() ) ) {
			if ( 'less_than' === $stock_check && intval( $stock_quantity ) <= 0 ) {
				return false;
			}
			return true;
		}
		if ( ! empty( $product->get_manage_stock() ) ) {
			$product_quantity = $product->get_stock_quantity();

		}

		switch ( $stock_check ) {
			case 'equals':
				if ( $stock_quantity == $product_quantity ) {
					return true;
				}
				break;
			case 'greater_than':
				if ( $product_quantity >= $stock_quantity ) {
					return true;
				}
				break;
			case 'less_than':
				if ( $product_quantity <= $stock_quantity ) {
					return true;
				}
				break;
		}
		return false;


	}
	public function check_sold_quantity( $product, $prod_sold_check, $sold_quantity ) {
		
		if ( empty( $prod_sold_check ) || '' === $sold_quantity ) {
			return true;
		}
		$product_quantity = $product->get_total_sales();
		switch ( $prod_sold_check ) {
			case 'equals':
				if ( $sold_quantity == $product_quantity ) {
					return true;
				}
				break;
			case 'greater_than':
				if ( $product_quantity >= $sold_quantity ) {
					return true;
				}
				break;
			case 'less_than':
				if ( $product_quantity <= $sold_quantity ) {
					return true;
				}
				break;
		}
		return false;
	}
	public function check_stock_status( $product, $stocks ) {
		if ( empty( $stocks ) ) {
			return true;
		}

		if ( ! in_array( $product->get_stock_status(), $stocks ) ) {
			return false;
		}

		return true;
	}
	public function elex_gpf_check_stock_and_sold_quantity( $product, $stock_check, $stock_quantity, $prod_sold_check, $sold_quantity, $stock_status ) {
		if ( ( ! $stock_check && ! $prod_sold_check && ! $stock_status ) || ( '' == $stock_quantity && '' == $sold_quantity && empty( $stock_status ) ) ) {
			return true;
		}
		$stock_quatity = $this->check_stock_quantity( $product, $stock_check, $stock_quantity );
		$sold_quatity = $this->check_sold_quantity( $product, $prod_sold_check, $sold_quantity );
		$stock_status = $this->check_stock_status( $product, $stock_status );
		return ( $stock_quatity && $sold_quatity ) && $stock_status ;
	
	}

	public function elex_gpf_get_simple_product_attr_values( $prod_attr, $google_attr, $ids, $product, $product_details, $prefix, $google_cat, $product_attributes, $autoset_identifier_exists, $add_items, $currency_conversion, $sale_country ) {
		/**
		 * Compatibility with WPML.
		 */
		
		$wpml_product = '';

		if ( is_array( $this->setting_tab_fields ) && isset( $this->setting_tab_fields['wpml_language'] ) && has_filter( 'wpml_object_id' ) ) {
			$wpml_product_id = apply_filters( 'wpml_object_id', $product->get_id(), 'product', true, $this->setting_tab_fields['wpml_language'] );
			$wpml_product = wc_get_product( $wpml_product_id );
			$wpml_product_details = $wpml_product->get_data();
		}
		$map_prod_attr_val = '';
		 $prod_attr_key = $prod_attr;
		$recom_values = explode( '_', $prod_attr );
		if ( 'rec' == $recom_values[0] ) {
			$map_prod_attr_val = $recom_values[1];
		} else if ( ( strpos( $prod_attr, 'elex_text_val', 0 ) === 0 ) ) {
			$map_prod_attr_val = str_replace( 'elex_text_val', '', $prod_attr );
		} else if ( isset( $product_attributes[ $prod_attr_key ] ) && 'meta' == $product_attributes[ $prod_attr_key ]['type'] ) {
			$map_prod_attr_val = $product->get_meta( $prod_attr );
			if ( '_tax_class' == $prod_attr && '' == $map_prod_attr_val ) {
				$map_prod_attr_val = 'standard';
			}
			if ( 'gtin' == $google_attr && '_elex_gpf_gtin' == $prod_attr && '' == $map_prod_attr_val && 'true' == $autoset_identifier_exists ) {
				$add_items->addChild( 'identifier_exists', 'no', $prefix['g'] );
			}
		} else if ( 'ID' == $prod_attr ) {
			$map_prod_attr_val = $ids;
		} elseif ( 'prod_category' == $prod_attr ) {
			$term_list = wp_get_post_terms( $ids, 'product_cat', array( 'fields' => 'ids' ) );
			 $cid = end( $term_list );
			 $term = get_term_by( 'id', $cid, 'product_cat' );
			if ( $term ) {
				$map_prod_attr_val = $term->name;
			}
		} else if ( '_stock_status' == $prod_attr ) {
			$map_prod_attr_val = $product->get_meta( $prod_attr );
			if ( 'instock' == $map_prod_attr_val ) {
				$map_prod_attr_val = 'in stock';
			}
			if ( 'outofstock' == $map_prod_attr_val ) {
				$map_prod_attr_val = 'out of stock';
			}
			if ( 'onbackorder' == $map_prod_attr_val ) {
				$map_prod_attr_val = 'preorder';
			}
		} else if ( 'post_title' == $prod_attr ) {
			if ( $wpml_product ) {
				$map_prod_attr_val = $wpml_product->get_name();
			} else {
				$map_prod_attr_val = $product->get_name();
			}
		} else if ( 'post_content' == $prod_attr ) {
			if ( $wpml_product ) {
				$map_prod_attr_val = $wpml_product_details['description'] ? $wpml_product_details['description'] : '';
			} else {
				$map_prod_attr_val = $product_details['description'] ? $product_details['description'] : '';
			}
		} else if ( 'post_excerpt' == $prod_attr ) {
			if ( $wpml_product ) {
				$map_prod_attr_val = $wpml_product_details['short_description'] ? $wpml_product_details['short_description'] : '';
			} else {
				$map_prod_attr_val = $product_details['short_description'] ? $product_details['short_description'] : '';
			}
		} else if ( 'price' == $prod_attr ) {
			$map_prod_attr_val = $product->get_price();
			if ( is_numeric( $currency_conversion ) && is_numeric( $map_prod_attr_val ) ) {
				$map_prod_attr_val *= $currency_conversion;
			}
		} else if ( 'price_incl_tax' == $prod_attr ) {
			$map_prod_attr_val = elex_gpf_get_tax_rate_for_country( $sale_country, $product->get_price() );
			if ( is_numeric( $currency_conversion ) && is_numeric( $map_prod_attr_val ) ) {
				$map_prod_attr_val *= $currency_conversion;
			}
		} else if ( 'attachment_url' == $prod_attr ) {
			$map_prod_attr_val = wp_get_attachment_url( get_post_thumbnail_id( $ids ) );
		} else if ( 'menu_order' == $prod_attr ) {
			$map_prod_attr_val = get_post_field( 'menu_order', $ids );
		} else if ( 'post_author' == $prod_attr ) {
			$author_id = get_post_field( 'post_author', $ids );
			$map_prod_attr_val = get_the_author_meta( 'user_nicename', $author_id );
		} else if ( 'post_date' == $prod_attr ) {
			$time = get_the_time( '', $ids );
			$map_prod_attr_val = get_the_date( '', $ids ) . ' ' . $time;
		} else if ( 'post_date_gmt' == $prod_attr ) {
			$time = get_post_time( '', $ids, true );
			$map_prod_attr_val = get_the_date( '', $ids );
		} else if ( 'post_modified' == $prod_attr ) {
			$time = get_the_modified_time( '', $ids );
			$map_prod_attr_val = get_the_modified_date( '', $ids ) . ' ' . $time;
		} else if ( 'post_modified_gmt' == $prod_attr ) {
			$map_prod_attr_val = get_the_modified_date( '', $ids );
		} else if ( 'permalink' == $prod_attr ) {
			$map_prod_attr_val = get_permalink( $ids );
		} else if ( 'google_category' == $prod_attr ) {
			$map_prod_attr_val = $google_cat;
		} elseif ( 'main_image' == $prod_attr ) {
			$image_details = wp_get_attachment_image_src( get_post_thumbnail_id( $ids ), 'single-post-thumbnail' );
			if ( $image_details ) {
				$map_prod_attr_val = $image_details[0];
			}
		} elseif ( 'wc_currency' == $prod_attr ) {
			$map_prod_attr_val = get_woocommerce_currency();
		} else if ( 'product_type' == $prod_attr ) {
			$map_prod_attr_val = 'simple';
		} elseif ( 'product_tags' == $prod_attr ) {
			$terms = get_the_terms( $ids, 'product_tag' );
			if ( $terms ) {
				$map_prod_attr_val = $terms[0]->name;
			}
		} elseif ( 'review_comment' == $prod_attr ) {
			$args = array(
				'post_type' => 'product',
				'post_id' => $ids,
			);
			$comments = get_comments( $args );
			if ( $comments ) {
				$map_prod_attr_val = $comments[0]->comment_content;
			}
		} elseif ( 'review_count' == $prod_attr ) {
			$map_prod_attr_val = $product->get_meta( 'review_count' );
		}
		if ( '_stock_status' == $prod_attr ) {
			$map_prod_attr_val = $product->get_stock_status();
			if ( 'instock' == $map_prod_attr_val ) {
				$map_prod_attr_val = 'in_stock';
			}
			if ( 'outofstock' == $map_prod_attr_val ) {
				$map_prod_attr_val = 'out_of_stock';
			}
			if ( 'onbackorder' == $map_prod_attr_val ) {
				$map_prod_attr_val = 'backorder';
			}
		} 
		return $map_prod_attr_val;
	}

	public function elex_gpf_mapping_settings_field() {
		check_ajax_referer( 'ajax-elex-gpf-nonce', '_ajax_elex_gpf_nonce' );
		$google_cats = ( isset( $_POST['google_cats'] ) && ! empty( $_POST['google_cats'] ) ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['google_cats'] ) ) : '';
		$country_sale = isset( $_POST['country_of_sale'] ) ? sanitize_text_field( $_POST['country_of_sale'] ) : '';
		$mapping_attr = $this->elex_gpf_get_mapping_attr( $google_cats, $country_sale );
		die( json_encode( $mapping_attr ) );
	}

	public function elex_gpf_get_mapping_attr( $google_cats, $country, $saved_google_attr = array() ) {
		$google_attr = $this->elex_gpf_get_google_attributes();
		$required_attr = array();
		$required_attr['id'] = $google_attr['Basic product data']['id'];
		$required_attr['title'] = $google_attr['Basic product data']['title'];
		$required_attr['description'] = $google_attr['Basic product data']['description'];
		$required_attr['link'] = $google_attr['Basic product data']['link'];
		$required_attr['image_link'] = $google_attr['Basic product data']['image_link'];
		$required_attr['availability'] = $google_attr['Price & availability']['availability'];
		$required_attr['price'] = $google_attr['Price & availability']['price'];
		$required_attr['gtin'] = $google_attr['Product identifiers']['gtin'];
		$required_attr['mpn'] = $google_attr['Product identifiers']['mpn'];
		$required_attr['condition'] = $google_attr['Detailed product description']['condition'];
		$required_attr['adult'] = $google_attr['Detailed product description']['adult'];
		unset( $google_attr['Basic product data']['id'] );
		unset( $google_attr['Basic product data']['title'] );
		unset( $google_attr['Basic product data']['description'] );
		unset( $google_attr['Basic product data']['link'] );
		unset( $google_attr['Basic product data']['image_link'] );
		unset( $google_attr['Price & availability']['availability'] );
		unset( $google_attr['Price & availability']['price'] );
		unset( $google_attr['Product identifiers']['gtin'] );
		unset( $google_attr['Product identifiers']['mpn'] );
		unset( $google_attr['Detailed product description']['condition'] );
		if ( 'australia' == $country || 'czechia' == $country || 'france' == $country || 'germany' == $country || 'israel' == $country || 'italy' == $country || 'netherlands' == $country || 'spain' == $country || 'switzerland' == $country || 'united_kingdom' == $country || 'united_states' == $country ) {
			$required_attr['shipping-price'] = $google_attr['Shipping']['shipping-price'];
			unset( $google_attr['Shipping']['shipping-price'] );
			if ( 'united_states' == $country ) {
				$required_attr['tax-rate'] = $google_attr['Tax']['tax-rate'];
				unset( $google_attr['Tax']['tax-rate'] );
			}
		}
		if ( 'australia' == $country || 'brazil' == $country || 'czechia' == $country || 'france' == $country || 'germany' == $country || 'italy' == $country || 'japan' == $country || 'netherlands' == $country || 'spain' == $country || 'switzerland' == $country || 'united_kingdom' == $country || 'united_states' == $country ) {
			$required_attr['is_bundle'] = $google_attr['Detailed product description']['is_bundle'];
			unset( $google_attr['Detailed product description']['is_bundle'] );
		}
		if ( $google_cats ) {
			$required_attr['google_product_category'] = $google_attr['Product category']['google_product_category'];
			unset( $google_attr['Product category']['google_product_category'] );
			foreach ( $google_cats as $google_category ) {
				$check = explode( '-', $google_category );
				$cat = trim( $check[1] );
				if ( ( strpos( $cat, 'Media', 0 ) !== 0 ) ) {
					if ( isset( $google_attr['Product identifiers']['brand'] ) ) {
						$required_attr['brand'] = $google_attr['Product identifiers']['brand'];
						unset( $google_attr['Product identifiers']['brand'] );
					}
				}
				if ( 0 === ( strpos( $cat, 'Apparel', 0 ) ) || ( 0 === strpos( $cat, 'Media', 0 ) ) || ( 0 === strpos( $cat, 'Software', 0 ) ) ) {

					if ( 0 === strpos( $cat, 'Apparel', 0 ) ) {
						if ( 'germany' == $country || 'brazil' == $country || 'japan' == $country || 'france' == $country || 'united_kingdom' == $country || 'united_states' == $country ) {
							if ( isset( $google_attr['Detailed product description']['age_group'] ) ) {
								$required_attr['age_group'] = $google_attr['Detailed product description']['age_group'];
								unset( $google_attr['Detailed product description']['age_group'] );
							}
						}
					}
				}

				if ( ( 0 === strpos( $cat, 'Apparel & Accessories > Clothing', 0 ) ) || ( 0 === strpos( $cat, 'Apparel & Accessories > Shoe', 0 ) ) ) {
					if ( 'germany' == $country || 'brazil' == $country || 'france' == $country || 'japan' == $country || 'united_kingdom' == $country || 'united_states' == $country ) {
						if ( isset( $google_attr['Detailed product description']['size'] ) ) {
							$required_attr['size'] = $google_attr['Detailed product description']['size'];
							unset( $google_attr['Detailed product description']['size'] );
						}
					}
				}
			}
		}
		$product_attr = $this->elex_gpf_get_product_attributes();
		$required_google_attr = array();
		if ( ! empty( $saved_google_attr ) ) {
			foreach ( $saved_google_attr as $attr ) {
				if ( in_array( $attr, array_keys( $required_attr ) ) ) {
					$required_google_attr[ $attr ] = $required_attr[ $attr ];
					unset( $required_attr[ $attr ] );
				}
			}
			if ( ! empty( $required_attr ) ) {
				$required_google_attr = array_merge( $required_google_attr, $required_attr );
			}
		} else {
			$required_google_attr = $required_attr;
		}
		//Sorting of meta value 
		function sort_attr_lists( $product_attr, $type ) {
			foreach ( $product_attr as $key => $value ) {
				$attr[ $key ] = strtolower( $value[ $type ] );
			}
			asort( $attr );
			foreach ( $attr as $key => $val ) {
				$sorted_product_attr[ $key ] = $product_attr[ $key ];
			}
			return $sorted_product_attr;
		}
		$sorted_product_attr = sort_attr_lists( $product_attr, 'grp_type' );

		$mapping_attr = array(
			'required_attr' => $required_google_attr,
			'optional' => $google_attr,
			'product_attr' => $sorted_product_attr,
		);
		return $mapping_attr;
	}

	public function elex_gpf_manage_feed_edit_file() {
		check_ajax_referer( 'ajax-elex-gpf-manage-feed-nonce', '_ajax_elex_gpf_manage_feed_nonce' );
		$prefill_values = array();
		$mapping_attr = '';
		$file_to_edit = isset( $_POST['file_to_edit'] ) ? sanitize_text_field( $_POST['file_to_edit'] ) : '';
		$prefill_values = array_merge( $prefill_values, json_decode( elex_gpf_get_feed_data( $file_to_edit, 'start_filter' )[0]['feed_meta_content'], true ) );
		$prefill_values = array_merge( $prefill_values, json_decode( elex_gpf_get_feed_data( $file_to_edit, 'category_select' )[0]['feed_meta_content'], true ) );
		$prefill_values = array_merge( $prefill_values, json_decode( elex_gpf_get_feed_data( $file_to_edit, 'attribute_map' )[0]['feed_meta_content'], true ) );
		$prefill_values = array_merge( $prefill_values, json_decode( elex_gpf_get_feed_data( $file_to_edit, 'filtering_options' )[0]['feed_meta_content'], true ) );
		$prefill_values = array_merge( $prefill_values, json_decode( elex_gpf_get_feed_data( $file_to_edit, 'manage_feed_data' )[0]['feed_meta_content'], true ) );
		foreach ( $prefill_values['categories_choosen'] as $index => $cat_id_or_slug ) {
			if ( ! is_numeric( $cat_id_or_slug ) ) { // if category chosen array contains slug instead of category id
			$category_id = get_term_by( 'slug', $cat_id_or_slug, 'product_cat' ); // get the category id using slug name 
				$prefill_values['categories_choosen'][ $index ] = $category_id->term_id; //update the slug name with cat id
			}       
		}
		
		
		if ( ! empty( $prefill_values['selected_google_product_cats'] ) ) {
			$selected_google_cats = $prefill_values['selected_google_product_cats'];
		} else {
			$selected_google_cats = $prefill_values['sel_google_cats'];
		}
		$mapping_attr = $this->elex_gpf_get_mapping_attr( $selected_google_cats, $prefill_values['sale_country'], $prefill_values['google_attr'] );
		 $mapping_attr['prefill_val'] = $prefill_values;
		die( json_encode( $mapping_attr ) );
	}

	public function elex_gpf_get_product_attributes() {
		$prod_meta = array(
			'' => array(
				'label' => '-- Choose --',
				'type' => '',
				'grp_type' => '',
			),
			'ID' => array(
				'label' => 'Product Id',
				'type' => '',
				'grp_type' => 'General',
			),
			'price' => array(
				'label' => 'Price',
				'type' => '',
				'grp_type' => 'General',
			),
			'price_incl_tax' => array(
				'label' => 'Price Including Tax',
				'type' => '',
				'grp_type' => 'General',
			),
			'_regular_price' => array(
				'label' => 'Regular Price',
				'type' => 'meta',
				'grp_type' => 'General',
			),
			'_sale_price' => array(
				'label' => 'Sale Price',
				'type' => 'meta',
				'grp_type' => 'General',
			),
			'_tax_class' => array(
				'label' => 'Tax Class',
				'type' => 'meta',
				'grp_type' => 'General',
			),
			'_tax_status' => array(
				'label' => 'Tax Status',
				'type' => 'meta',
				'grp_type' => 'General',
			),
			'post_title' => array(
				'label' => 'Product Title',
				'type' => '',
				'grp_type' => 'General',
			),
			'_elex_gpf_brand' => array(
				'label' => 'Brand',
				'type' => 'meta',
				'grp_type' => 'General',
			),
			'_elex_gpf_gtin' => array(
				'label' => 'GTIN',
				'type' => 'meta',
				'grp_type' => 'General',
			),
			'_elex_gpf_mpn' => array(
				'label' => 'MPN',
				'type' => 'meta',
				'grp_type' => 'General',
			),
			'post_content' => array(
				'label' => 'Product Description',
				'type' => '',
				'grp_type' => 'General',
			),
			'post_excerpt' => array(
				'label' => 'Product Short Description',
				'type' => '',
				'grp_type' => 'General',
			),
			'post_author' => array(
				'label' => 'Post Author',
				'type' => '',
				'grp_type' => 'General',
			),
			'prod_category' => array(
				'label' => 'Product Category',
				'type' => '',
				'grp_type' => 'General',
			),
			'product_tags' => array(
				'label' => 'Product Tags',
				'type' => '',
				'grp_type' => 'General',
			),
			'product_type' => array(
				'label' => 'Product Type',
				'type' => '',
				'grp_type' => 'General',
			),
			'permalink' => array(
				'label' => 'Permalink',
				'type' => '',
				'grp_type' => 'General',
			),
			'main_image' => array(
				'label' => 'Main Image',
				'type' => '',
				'grp_type' => 'General',
			),
			'wc_currency' => array(
				'label' => 'Woocommerce Shop Currency',
				'type' => '',
				'grp_type' => 'General',
			),
			'_virtual' => array(
				'label' => 'Virtual',
				'type' => 'meta',
				'grp_type' => 'General',
			),
			'review_comment' => array(
				'label' => 'Review Comment',
				'type' => '',
				'grp_type' => 'General',
			),
			'review_count' => array(
				'label' => 'Average Review Count',
				'type' => '',
				'grp_type' => 'General',
			),
			'_sku' => array(
				'label' => 'SKU',
				'type' => 'meta',
				'grp_type' => 'Inventory',
			),
			'_manage_stock' => array(
				'label' => 'Manage Stock',
				'type' => 'meta',
				'grp_type' => 'Inventory',
			),
			'_stock' => array(
				'label' => 'Stock Quantity',
				'type' => 'meta',
				'grp_type' => 'Inventory',
			),
			'_stock_status' => array(
				'label' => 'Stock Status',
				'type' => '',
				'grp_type' => 'Inventory',
			),
			'_sold_individually' => array(
				'label' => 'Sold Individually',
				'type' => 'meta',
				'grp_type' => 'Inventory',
			),
			'_backorders' => array(
				'label' => 'Allow Backorders',
				'type' => 'meta',
				'grp_type' => 'Inventory',
			),
			'_height' => array(
				'label' => 'Height',
				'type' => 'meta',
				'grp_type' => 'Shipping',
			),
			'_width' => array(
				'label' => 'Width',
				'type' => 'meta',
				'grp_type' => 'Shipping',
			),
			'_length' => array(
				'label' => 'Length',
				'type' => 'meta',
				'grp_type' => 'Shipping',
			),
			'_weight' => array(
				'label' => 'Weight',
				'type' => 'meta',
				'grp_type' => 'Shipping',
			),
			'menu_order' => array(
				'label' => 'Menu Order',
				'type' => '',
				'grp_type' => 'Advanced',
			),
			'item_group_id' => array(
				'label' => 'Item group ID',
				'type' => '',
				'grp_type' => 'Advanced',
			),
			'google_category' => array(
				'label' => 'Google Category',
				'type' => '',
				'grp_type' => 'Advanced',
			),
		);
		$custom_meta = $this->elex_gpf_get_custom_meta_keys();
		$product_metas = array_merge( $prod_meta, $custom_meta );
		return $product_metas;
	}

	public function elex_gpf_get_custom_meta_keys() {
		global $wpdb;
		$data         = $wpdb->get_results( 
			'SELECT  meta.meta_key  FROM ' . $wpdb->prefix . 'postmeta AS meta, ' . $wpdb->prefix . 'posts' . " AS posts WHERE meta.post_id = posts.id AND posts.post_type LIKE '%product%' 
					GROUP BY meta.meta_key ORDER BY meta.meta_key ASC" 
		);
		$temp_arr = array();
		$custom_metas = array();
		foreach ( $data as $key ) {
			$temp_arr = array();
			$temp_arr['label'] = $key->meta_key;
			$temp_arr['type'] = 'meta';
			$temp_arr['grp_type'] = 'Meta Values';
			$custom_metas[ $key->meta_key ] = $temp_arr;
		}
		return $custom_metas;
	}

	public function elex_gpf_get_google_attributes() {
		return array(
			'Basic product data' => array(
				'id' => array(
					'label' => 'Product ID',
					'feed_name' => 'g:id',
				),
				'title' => array(
					'label' => 'Product title',
					'feed_name' => 'g:title',
				),
				'description' => array(
					'label' => 'Product description',
					'feed_name' => 'g:description',
				),
				'link' => array(
					'label' => 'Product link',
					'feed_name' => 'g:link',
				),
				'image_link' => array(
					'label' => 'Main image link',
					'feed_name' => 'g:image_link',
				),
				'additional_image_link' => array(
					'label' => 'Additional image link',
					'feed_name' => 'g:additional_image_link',
				),
				'mobile_link' => array(
					'label' => 'Product mobile link',
					'feed_name' => 'g:mobile_link',
				),
			),
			'Price & availability' => array(
				'availability' => array(
					'label' => 'Stock status',
					'feed_name' => 'g:availability',
				),
				'availability_date' => array(
					'label' => 'Availability date',
					'feed_name' => 'g:availability_date',
				),
				'cost_of_goods_sold' => array(
					'label' => 'Cost of goods sold',
					'feed_name' => 'g:cost_of_goods_sold',
				),
				'expiration_date' => array(
					'label' => 'Expiration date',
					'feed_name' => 'g:expiration_date',
				),
				'price' => array(
					'label' => 'Price',
					'feed_name' => 'g:price',
				),
				'sale_price' => array(
					'label' => 'Sale price',
					'feed_name' => 'g:sale_price',
				),
				'sale_price_effective_date' => array(
					'label' => 'Sale price effective date',
					'feed_name' => 'g:sale_price_effective_date',
				),
				'unit_pricing_measure' => array(
					'label' => 'Unit pricing measure',
					'feed_name' => 'g:unit_pricing_measure',
				),
				'unit_pricing_base_measure' => array(
					'label' => 'Unit pricing base measure',
					'feed_name' => 'g:unit_pricing_base_measure',
				),
				'installment' => array(
					'label' => 'Installment',
					'feed_name' => 'g:installment',
				),
				'loyalty_points' => array(
					'label' => 'Loyalty points',
					'feed_name' => 'g:loyalty_points',
				),
				'subscription_cost-period' => array(
					'label' => 'Subscription Cost - Period',
					'feed_name' => 'g:subscription_cost-period',
				),
				'subscription_cost-period_length' => array(
					'label' => 'subscription Coste - Priod Length',
					'feed_name' => 'g:subscription_cost-period_length',
				),
				'subscription_cost-amount' => array(
					'label' => 'Subscription Cost - Amount',
					'feed_name' => 'g:subscription_cost-amount',
				),
			),
			'Product category' => array(
				'google_product_category' => array(
					'label' => 'Google product category',
					'feed_name' => 'g:google_product_category',
				),
				'product_type' => array(
					'label' => 'Product type',
					'feed_name' => 'g:product_type',
				),
			),
			'Product identifiers' => array(
				'brand' => array(
					'label' => 'Brand',
					'feed_name' => 'g:brand',
				),
				'gtin' => array(
					'label' => 'GTIN',
					'feed_name' => 'g:gtin',
				),
				'mpn' => array(
					'label' => 'MPN',
					'feed_name' => 'g:mpn',
				),
				'identifier_exists' => array(
					'label' => 'Identifier exists',
					'feed_name' => 'g:identifier_exists',
				),
			),
			'Detailed product description' => array(
				'condition' => array(
					'label' => 'Condition',
					'feed_name' => 'g:condition',
				),
				'adult' => array(
					'label' => 'Adult',
					'feed_name' => 'g:adult',
				),
				'multipack' => array(
					'label' => 'Multipack',
					'feed_name' => 'g:multipack',
				),
				'is_bundle' => array(
					'label' => 'Is bundle',
					'feed_name' => 'g:is_bundle',
				),
				'energy_efficiency_class' => array(
					'label' => 'Energy efficiency class',
					'feed_name' => 'g:energy_efficiency_class',
				),
				'min_energy_efficiency_class' => array(
					'label' => 'Minimum energy efficiency class',
					'feed_name' => 'g:min_energy_efficiency_class',
				),
				'max_energy_efficiency_class' => array(
					'label' => 'Maximum energy efficiency class',
					'feed_name' => 'g:max_energy_efficiency_class',
				),
				'age_group' => array(
					'label' => 'Age group',
					'feed_name' => 'g:age_group',
				),
				'color' => array(
					'label' => 'Color',
					'feed_name' => 'g:color',
				),
				'gender' => array(
					'label' => 'Gender',
					'feed_name' => 'g:gender',
				),
				'material' => array(
					'label' => 'Material',
					'feed_name' => 'g:material',
				),
				'pattern' => array(
					'label' => 'Pattern',
					'feed_name' => 'g:pattern',
				),
				'size' => array(
					'label' => 'Size',
					'feed_name' => 'g:size',
				),
				'size_type' => array(
					'label' => 'Size type',
					'feed_name' => 'g:size_type',
				),
				'size_system' => array(
					'label' => 'Size system',
					'feed_name' => 'g:size_system',
				),
				'item_group_id' => array(
					'label' => 'Item group ID',
					'feed_name' => 'g:item_group_id',
				),
				'product_detail-section_name' => array(
					'label' => 'Product Detail - Section Name',
					'feed_name' => 'g:product_detail-section_name',
				),
				'product_detail-attribute_name' => array(
					'label' => 'Product Detail - Attribute Name',
					'feed_name' => 'g:product_detail-attribute_name',
				),
				'product_detail-attribute_value' => array(
					'label' => 'Product Detail - Attribute Value',
					'feed_name' => 'g:product_detail-attribute_value',
				),
				'product_highlight' => array(
					'label' => 'Product Highlight',
					'feed_name' => 'g:product_highlight',
				),
			),
			'Shopping campaigns and other configurations' => array(
				'adwords_redirect' => array(
					'label' => 'Adwords redirect',
					'feed_name' => 'g:adwords_redirect',
				),
				//				"ads_redirect" => array(
				//              "label" =>"Ads redirect (new)",
				//                  "feed_name" => "g:ads_redirect",
				//              ),
								'custom_label_0' => array(
									'label' => 'Custom label 0',
									'feed_name' => 'g:custom_label_0',
								),
				'custom_label_1' => array(
					'label' => 'Custom label 1',
					'feed_name' => 'g:custom_label_1',
				),
				'custom_label_2' => array(
					'label' => 'Custom label 2',
					'feed_name' => 'g:custom_label_2',
				),
				'custom_label_3' => array(
					'label' => 'Custom label 3',
					'feed_name' => 'g:custom_label_3',
				),
				'custom_label_4' => array(
					'label' => 'Custom label 4',
					'feed_name' => 'g:custom_label_4',
				),
				'promotion_id' => array(
					'label' => 'Promotion ID',
					'feed_name' => 'g:promotion_id',
				),
				'included_destination' => array(
					'label' => 'Included destination',
					'feed_name' => 'included_destination',
				),
				'excluded_destination' => array(
					'label' => 'Excluded destination',
					'feed_name' => 'g:excluded_destination',
				),
				'shopping_ads_excluded_country' => array(
					'label' => 'Shopping Ads Excluded Country',
					'feed_name' => 'g:shopping_ads_excluded_country',
				),
				'store_code' => array(
					'label' => 'Store Code',
					'feed_name' => 'g:store_code',
				),
				'quantity' => array(
					'label' => 'Quantity',
					'feed_name' => 'g:quantity',
				),

			),
			'Shipping' => array(
				'shipping-price' => array(
					'label' => 'Shipping - Price',
					'feed_name' => 'g:shipping-price',
				),
				'shipping-country' => array(
					'label' => 'Shipping - Country',
					'feed_name' => 'g:shipping-country',
				),
				'shipping-region' => array(
					'label' => 'Shipping - Region',
					'feed_name' => 'g:shipping-region',
				),
				'shipping-service' => array(
					'label' => 'Shipping - Service',
					'feed_name' => 'g:shipping-service',
				),
				'shipping_label' => array(
					'label' => 'Shipping label',
					'feed_name' => 'g:shipping_label',
				),
				'shipping_weight' => array(
					'label' => 'Shipping weight',
					'feed_name' => 'g:shipping_weight',
				),
				'shipping_length' => array(
					'label' => 'Shipping length',
					'feed_name' => 'g:shipping_length',
				),
				'shipping_width' => array(
					'label' => 'Shipping width',
					'feed_name' => 'g:shipping_width',
				),
				'shipping_height' => array(
					'label' => 'Shipping height',
					'feed_name' => 'g:shipping_height',
				),
				'min_handling_time' => array(
					'label' => 'Minimum handling time',
					'feed_name' => 'g:min_handling_time',
				),
				'max_handling_time' => array(
					'label' => 'Maximum handling time',
					'feed_name' => 'g:max_handling_time',
				),
				'ships_from_country' => array(
					'label' => 'Ships From Country',
					'feed_name' => 'g:ships_from_country',
				),
				'transit_time_label' => array(
					'label' => 'Transit Time Label',
					'feed_name' => 'g:transit_time_label',
				),
			),
			'Tax' => array(
				'tax-rate' => array(
					'label' => 'Tax - Rate',
					'feed_name' => 'g:tax-rate',
				),
				'tax-country' => array(
					'label' => 'Tax - Country',
					'feed_name' => 'g:tax-country',
				),
				'tax-region' => array(
					'label' => 'Tax - Region',
					'feed_name' => 'g:tax-region',
				),
				'tax-tax_ship' => array(
					'label' => 'Tax - Tax on Shipping',
					'feed_name' => 'g:tax_ship',
				),
				'tax_category' => array(
					'label' => 'Tax category',
					'feed_name' => 'g:tax_category',
				),
			),
		);
	}

	public function elex_gpf_get_product_ids( $cat_cond, $variation ) {
		$product_type = 'true' === $variation ? 'simple,variable' : 'simple';
			$product_ids = get_posts(
				array(
					'post_type' => 'product',
					'numberposts' => -1,
					'post_status' => 'publish',
					'fields'          => 'ids',
					'product_type' => $product_type,
					'tax_query' => array(
						array(
							'taxonomy' => 'product_cat',
							'field' => 'term_id',
							'terms' => trim( $cat_cond, "'" ),
							'operator' => 'IN',
						),
					),
				)
			);
		return $product_ids;
	}
}

new Elex_Gpf_Ajax_Call();
