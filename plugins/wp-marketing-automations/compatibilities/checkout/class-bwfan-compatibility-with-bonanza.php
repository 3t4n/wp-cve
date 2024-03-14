<?php

/**
 * Bonanza
 * https://wordpress.org/plugins/bonanza-woocommerce-free-gifts-lite/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_Bonanza' ) ) {
	class BWFAN_Compatibility_With_Bonanza {

		public function __construct() {
			add_filter( 'bwfan_exclude_cart_items_to_restore', [ $this, 'exclude_gifts' ], 99, 3 );
		}

		/**
		 * Excluding restoring gift products
		 *
		 * @param $bool
		 * @param $key
		 * @param $data
		 *
		 * @return bool|mixed
		 */
		public function exclude_gifts( $bool, $key, $data ) {
			if ( isset( $data['xlwcfg_gift_id'] ) ) {
				$bool = true;
			}

			return $bool;
		}
	}

	if ( class_exists( 'XLWCFG_Core' ) ) {
		new BWFAN_Compatibility_With_Bonanza();
	}
}
