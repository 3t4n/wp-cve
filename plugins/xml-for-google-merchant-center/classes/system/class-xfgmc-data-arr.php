<?php
/**
 * Set and Get the Plugin Data
 *
 * @package                 iCopyDoc Plugins (v1, core 16-08-2023)
 * @subpackage              XML for Google Merchant Center
 * @since                   0.1.0
 * 
 * @version                 4.0.0 (30-08-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 * 
 * @param         
 *
 * @depends                 classes:    
 *                          traits:     
 *                          methods:    
 *                          functions:  
 *                          constants:  
 *                          options:    
 */
defined( 'ABSPATH' ) || exit;

class XFGMC_Data_Arr {
	private $data_arr = [ 
		[ 0 => 'xfgmc_status_sborki', 1 => '-1', 2 => 'private', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_date_sborki', 1 => '0000000001', 2 => 'private', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_date_sborki_end', 1 => '0000000001', 2 => 'private', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_date_save_set', 1 => '0000000001', 2 => 'private', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_count_products_in_feed', 1 => '-1', 2 => 'private', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_file_url', 1 => '', 2 => 'private', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_file_file', 1 => '', 2 => 'private', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_errors', 1 => '', 2 => 'private', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_status_cron', 1 => 'off', 2 => 'private', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_run_cron', 1 => 'off', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_ufup', 1 => '0', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_feed_assignment', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_adapt_facebook', 1 => 'no', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_whot_export', 1 => 'all', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_desc', 1 => 'fullexcerpt', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_the_content', 1 => 'enabled', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_var_desc_priority', 1 => 'on', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_target_country', 1 => 'RU', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_wooc_currencies', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_main_product', 1 => 'other', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_step_export', 1 => '500', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_cache', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_usa_tax_info', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_tax_region', 1 => 'ID', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_tax_rate', 1 => '8.75', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_sipping_tax', 1 => 'no', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_def_store_code', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_behavior_onbackorder', 1 => 'out_of_stock', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_availability_date', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_g_stock', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_default_condition', 1 => 'new', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_skip_missing_products', 1 => '0', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_skip_backorders_products', 1 => '0', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_no_default_png_products', 1 => '0', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_one_variable', 1 => '0', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_def_shipping_weight_unit', 1 => 'kg', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_def_shipping_country', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_def_delivery_area_type', 1 => 'region', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_def_delivery_area_value', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_def_shipping_service', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_def_shipping_price', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_tax_info', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_def_shipping_label', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_s_return_rule_label', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_def_return_rule_label', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_def_min_handling_time', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_def_max_handling_time', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_instead_of_id', 1 => 'default', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_product_type', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_product_type_home', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_sale_price', 1 => 'no', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_gtin', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_gtin_post_meta', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_mpn', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_mpn_post_meta', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_age', 1 => 'default', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_age_group_post_meta', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_brand', 1 => 'off', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_brand_post_meta', 1 => '', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_color', 1 => 'off', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_material', 1 => 'off', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_pattern', 1 => 'off', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_gender', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_gender_alt', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_size', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_size_type', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_size_type_alt', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_size_system', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку

		],
		[ 0 => 'xfgmc_size_system_alt', 1 => 'disabled', 2 => 'public', // TODO: Удалить потом эту строку

		],
	];

	public function __construct( $blog_title = '', $currency_id_xml = '', $data_arr = array() ) {
		if ( empty( $blog_title ) ) {
			$blog_title = mb_strimwidth( get_bloginfo( 'name' ), 0, 20 );
			$this->blog_title = $blog_title;
		}
		if ( empty( $currency_id_xml ) ) {
			if ( class_exists( 'WooCommerce' ) ) {
				$currency_id_xml = get_woocommerce_currency();
			} else {
				$currency_id_xml = 'USD';
			}
			$this->currency_id_xml = $currency_id_xml;
		}
		if ( ! empty( $data_arr ) ) {
			$this->data_arr = $data_arr;
		}
		array_push( $this->data_arr,
			array( 'xfgmc_shop_name', $this->blog_title, 'public' ),
			array( 'xfgmc_shop_description', $this->blog_title, 'public' ),
			array( 'xfgmc_default_currency', $this->currency_id_xml, 'public' )
		);

		$args_arr = array( $this->blog_title, $this->currency_id_xml );
		$this->data_arr = apply_filters( 'xfgmc_set_default_feed_settings_result_arr_filter', $this->data_arr, $args_arr );
	}

	public function get_data_arr() {
		return $this->data_arr;
	}

	// @return array([0] => opt_key1, [1] => opt_key2, ...)
	public function get_opts_name( $whot = '' ) {
		if ( $this->data_arr ) {
			$res_arr = array();
			for ( $i = 0; $i < count( $this->data_arr ); $i++ ) {
				switch ( $whot ) {
					case "public":
						if ( $this->data_arr[ $i ][2] === 'public' ) {
							$res_arr[] = $this->data_arr[ $i ][0];
						}
						break;
					case "private":
						if ( $this->data_arr[ $i ][2] === 'private' ) {
							$res_arr[] = $this->data_arr[ $i ][0];
						}
						break;
					default:
						$res_arr[] = $this->data_arr[ $i ][0];
				}
			}
			return $res_arr;
		} else {
			return array();
		}
	}

	// @return array(opt_name1 => opt_val1, opt_name2 => opt_val2, ...)
	public function get_opts_name_and_def_date( $whot = 'all' ) {
		if ( $this->data_arr ) {
			$res_arr = array();
			for ( $i = 0; $i < count( $this->data_arr ); $i++ ) {
				switch ( $whot ) {
					case "public":
						if ( $this->data_arr[ $i ][2] === 'public' ) {
							$res_arr[ $this->data_arr[ $i ][0] ] = $this->data_arr[ $i ][1];
						}
						break;
					case "private":
						if ( $this->data_arr[ $i ][2] === 'private' ) {
							$res_arr[ $this->data_arr[ $i ][0] ] = $this->data_arr[ $i ][1];
						}
						break;
					default:
						$res_arr[ $this->data_arr[ $i ][0] ] = $this->data_arr[ $i ][1];
				}
			}
			return $res_arr;
		} else {
			return array();
		}
	}

	public function get_opts_name_and_def_date_obj( $whot = 'all' ) {
		$source_arr = $this->get_opts_name_and_def_date( $whot );

		$res_arr = array();
		foreach ( $source_arr as $key => $value ) {
			$res_arr[] = new XFGMC_Data_Arr_Helper( $key, $value ); // return unit obj
		}
		return $res_arr;
	}
}
class XFGMC_Data_Arr_Helper {
	private $opt_name;
	private $opt_def_value;

	function __construct( $name = '', $def_value = '' ) {
		$this->opt_name = $name;
		$this->opt_def_value = $def_value;
	}

	function get_name() {
		return $this->opt_name;
	}

	function get_value() {
		return $this->opt_def_value;
	}
}
?>