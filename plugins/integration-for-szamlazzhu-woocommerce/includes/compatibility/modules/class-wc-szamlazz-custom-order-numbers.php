<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Custom Order Numbers for WooCommerce & Sequential Order Numbers Compatibility
class WC_Szamlazz_Custom_Order_Number_Compatibility {

	public static function init() {
		add_filter('wc_szamlazz_ipn_request_parameters', array( __CLASS__, 'change_oder_number' ));
	}

	public static function change_oder_number( $ipn_parameters ) {
		if(empty($ipn_parameters['order_number'])) return $ipn_parameters;

		//Different meta key for Sequential Order Numbers
		$meta_key = '_alg_wc_full_custom_order_number';
		if(defined('WT_SEQUENCIAL_ORDNUMBER_VERSION')) $meta_key = '_order_number';

		$args = array(
			'post_type'      => 'shop_order',
			'posts_per_page' => 1,
			'post_status'    => 'any',
			'meta_key' => $meta_key,
			'meta_value' => $ipn_parameters['order_number'],
			'fields' => 'ids',
		);
		$orders = get_posts( $args );
		if($orders) {
			$ipn_parameters['order_number'] = $orders[0];
		}
		return $ipn_parameters;
	}

}

WC_Szamlazz_Custom_Order_Number_Compatibility::init();
