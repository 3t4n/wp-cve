<?php

/**
 * Product section of the plugin
 *
 * @link          
 *
 * @package  Webtoffee_Product_Feed_Sync_Google_Promotions
 */
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Webtoffee_Product_Feed_Google_Promotions')) {

	class Webtoffee_Product_Feed_Google_Promotions {

		public $module_id = '';
		public static $module_id_static = '';
		public $module_base = 'google_promotions';
		public $module_name = 'Webtoffee Product Feed Catlaog for Google Promotions';
		public $min_base_version = '1.0.0'; /* Minimum `Import export plugin` required to run this add on plugin */
		private $importer = null;
		private $exporter = null;
		private $product_categories = null;
		private $product_tags = null;
		private $product_taxonomies = array();
		private $all_meta_keys = array();
		private $product_attributes = array();
		private $exclude_hidden_meta_columns = array();
		private $found_product_meta = array();
		private $found_product_hidden_meta = array();
		private $selected_column_names = null;

		public function __construct() {
			/**
			 *   Checking the minimum required version of `Import export plugin` plugin available
			 */
			if (!Webtoffee_Product_Feed_Sync_Common_Helper::check_base_version($this->module_base, $this->module_name, $this->min_base_version)) {
				return;
			}
			if (!function_exists('is_plugin_active')) {
				include_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}
			if (!is_plugin_active('woocommerce/woocommerce.php')) {
				return;
			}

			$this->module_id = Webtoffee_Product_Feed_Sync::get_module_id($this->module_base);
			self::$module_id_static = $this->module_id;

			add_filter('wt_pf_exporter_post_types_basic', array($this, 'wt_pf_exporter_post_types_basic'), 10, 1);

			add_filter('wt_pf_exporter_alter_mapping_fields_basic', array($this, 'exporter_alter_mapping_fields'), 10, 3);

			add_filter('wt_pf_exporter_alter_advanced_fields_basic', array($this, 'exporter_alter_advanced_fields'), 10, 3);

			add_filter('wt_pf_exporter_do_export_basic', array($this, 'exporter_do_export'), 10, 7);

			add_filter('wt_pf_exporter_steps_basic', array($this, 'fn_wt_pf_exporter_steps_basic'), 10, 2);
			
			add_filter('wt_pf_exporter_alter_advanced_fields_basic', array($this, 'wt_exporter_set_delimeter_default'), 10, 3);
			
			
		}

		
		public function wt_exporter_set_delimeter_default($advanced_screen_fields, $to_export, $advanced_form_data) {

			if ('google_promotions' === $to_export) {
				
				$advanced_screen_fields['delimiter']['sele_vals'] = array('tab' => array('value' => __('Tab', 'webtoffee-product-feed' ), 'val' => "\t"));
				$advanced_screen_fields['delimiter']['value'] = "\t";
				$advanced_screen_fields['delimiter']['attr'] = array(
					'style' => 'width:290px!important;'
				);
				$advanced_screen_fields['delimiter']['after_form_field'] = '';
				$advanced_screen_fields['delimiter']['help_text']= __( 'Separator for differentiating the columns in the CSV file. Only Tab is supported for Google Promotions', 'webtoffee-product-feed' );
			}
			
			return $advanced_screen_fields;
		}

		/**
		 * Add/Remove steps in export section.
		 * @param array $steps array of built in steps
		 * @param string $base or aka $to_export product, order etc
		 * @return array $steps 
		 */
		public function fn_wt_pf_exporter_steps_basic($steps, $to_export) {

			if ('google_promotions' === $to_export) {
				unset($steps['category_mapping']);
			}
			return $steps;
		}

		public function exporter_do_export($export_data, $base, $step, $form_data, $selected_template_data, $method_export, $batch_offset) {
			if ($this->module_base != $base) {
				return $export_data;
			}

			$this->set_selected_column_names($form_data);

                        include WT_PRODUCT_FEED_PLUGIN_PATH . '/admin/modules/export/wt-product.php';                        
			include plugin_dir_path(__FILE__) . 'export/export.php';
			$export = new Webtoffee_Product_Feed_Google_PromotionsExport($this);

			$header_row = $export->prepare_header();

			$data_row = $export->prepare_data_to_export($form_data, $batch_offset, $step);

			$export_data = array(
				'head_data' => $header_row,
				'body_data' => $data_row['data'],
				'total' => $data_row['total'],
			);

			if (isset($data_row['no_post'])) {
				$export_data['no_post'] = $data_row['no_post'];
			}


			return $export_data;
		}

		/**
		 * Adding current post type to export list
		 *
		 */
		public function wt_pf_exporter_post_types_basic($arr) {

			$arr['google_promotions'] = __('Google Promotions', 'webtoffee-product-feed');
			return $arr;
		}

		public static function get_product_post_columns() {
			return include plugin_dir_path(__FILE__) . 'data/data-product-post-columns.php';
		}

		public function set_selected_column_names($full_form_data) {

			if (is_null($this->selected_column_names)) {
				$this->selected_column_names = array();
				if (isset($full_form_data['mapping_form_data']['mapping_selected_fields']) && !empty($full_form_data['mapping_form_data']['mapping_selected_fields'])) {
					$selected_mapped_fields = array();
					foreach ($full_form_data['mapping_form_data']['mapping_selected_fields'] as $key => $value) {
						if ("" != $value) {
							$this->selected_column_names[$key] = $value;
						}
					}
				}
				if (isset($full_form_data['meta_step_form_data']['mapping_selected_fields']) && !empty($full_form_data['meta_step_form_data']['mapping_selected_fields'])) {
					$export_additional_columns = $full_form_data['meta_step_form_data']['mapping_selected_fields'];

					foreach ($export_additional_columns as $value) {
						foreach ($value as $key => $vl) {
							if ("" != $vl) {
								$this->selected_column_names[$key] = $vl;
							}
						}
					}
				}
				$this->selected_column_names = ($this->selected_column_names);
			}


			return $full_form_data;
		}

		public function get_selected_column_names() {

			return $this->selected_column_names;
		}

		public function exporter_alter_mapping_fields($fields, $base, $mapping_form_data) {
			if ($base == $this->module_base) {
				$fields = self::get_product_post_columns();
			}
			return $fields;
		}

		public function exporter_alter_advanced_fields($fields, $base, $advanced_form_data) {
			if ($this->module_base != $base) {
				return $fields;
			}
			$out = array();
			$out['header_empty_row'] = array(
				'tr_html' => '<tr id="header_empty_row"><th></th><td></td></tr>'
			);
			foreach ($fields as $fieldk => $fieldv) {
				$out[$fieldk] = $fieldv;
			}
                        $out['file_as']['sele_vals'] = array(
                            'xml'=>__('XML'), 
                            'tsv'=>__('TSV')
                        );
                        $out['delimiter']['sele_vals'] = array(
                            'tab' => array('value' => __('Tab'), 'val' => "\t"),
                            'comma' => array('value' => __('Comma'), 'val' => ",")                        
                        );
			return $out;
		}

	}

}

new Webtoffee_Product_Feed_Google_Promotions();
