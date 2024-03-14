<?php

class KcSeoHooks {
	public function __construct() {
		add_action('plugins_loaded', [__CLASS__, 'plugins_loaded']);
	}

	public static function plugins_loaded() {
		$settings = get_option('kcseo_wp_schema_settings');
		if (KcSeoFunctions::isYoastActive()) {
			if (isset($settings['yoast_wpseo_json_ld_search']) && $settings['yoast_wpseo_json_ld_search']) {
				add_filter('disable_wpseo_json_ld_search', '__return_true');
			}
			if (isset($settings['yoast_wpseo_json_ld']) && $settings['yoast_wpseo_json_ld']) {
				add_filter('wpseo_json_ld_output', [__CLASS__, 'disable_yoast_schema_data'], 10);
				add_filter('wpseo_schema_graph_pieces', '__return_empty_array');
			}
		}

		if (KcSeoFunctions::isWcActive()) {
			if (isset($settings['wc_schema_disable']) && $settings['wc_schema_disable']) {
				add_filter('woocommerce_structured_data_type_for_page', [
					__CLASS__,
					'remove_product_structured_data',
				], 10, 2);
				add_action('init', [__CLASS__, 'remove_output_structured_data']);
			}
		}
		if (KcSeoFunctions::isEddActive()) {
			if (isset($settings['edd_schema_microdata']) && $settings['edd_schema_microdata']) {
				add_filter('edd_add_schema_microdata', '__return_false');
			}
		}
	}

	public static function disable_yoast_schema_data($data) {
		$data = [];

		return $data;
	}

	/**
	 * Remove all product structured data.
	 */
	public static function remove_product_structured_data($types) {
		if (($index = array_search('product', $types)) !== false) {
			unset($types[$index]);
		}

		return $types;
	}

	/* Remove the default WooCommerce 3 JSON/LD structured data */
	public static function remove_output_structured_data() {
		remove_action('wp_footer', [
			WC()->structured_data,
			'output_structured_data',
		], 10); // This removes structured data from all frontend pages
		remove_action('woocommerce_email_order_details', [
			WC()->structured_data,
			'output_email_structured_data',
		], 30); // This removes structured data from all Emails sent by WooCommerce
	}
}
