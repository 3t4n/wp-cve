<?php

/**
 * Uni CPO Plugin Compatibility
 * https://wordpress.org/plugins/uni-woo-custom-product-options/
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'BWFAN_Compatibility_With_Uni_Cpo' ) ) {
	class BWFAN_Compatibility_With_Uni_Cpo {
		public function __construct() {
			add_filter( 'bwfan_abandoned_modify_cart_item_data', [ $this, 'bwfan_uni_cpo_item_data' ] );
		}

		/**
		 * @param $item_data
		 *
		 * @return mixed
		 */
		public function bwfan_uni_cpo_item_data( $item_data ) {
			if ( ! isset( $item_data['_cpo_data'] ) ) {
				return $item_data;
			}

			$cpo_data = $item_data['_cpo_data'];
			unset( $item_data['_cpo_data'] );
			$item_data['cpo_data'] = $cpo_data;

			return $item_data;
		}
	}

	if ( defined( 'UNI_CPO_PLUGIN_FILE' ) ) {
		new BWFAN_Compatibility_With_Uni_Cpo();
	}
}
