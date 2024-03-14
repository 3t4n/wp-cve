<?php

/**
 * WC Product Bundle
 * https://woocommerce.com/products/product-bundles/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_WC_Product_Bundle' ) ) {
	class BWFAN_Compatibility_With_WC_Product_Bundle {

		public function __construct() {
			add_filter( 'bwfan_abandoned_cart_items_visibility', array( $this, 'bwfan_modify_abandoned_cart_items' ), 10, 1 );
		}

		/**
		 * @return bool
		 */
		public function bwfan_modify_abandoned_cart_items( $cart ) {
			foreach ( $cart as $cart_item_key => $item ) {
				if ( ! wc_pb_maybe_is_bundled_cart_item( $item ) ) {
					continue;
				}

				$bundled_item_id = $item['bundled_item_id'];
				if ( empty( $bundled_item_id ) ) {
					continue;
				}

				$bundled_item_data = new WC_Bundled_Item_Data( $bundled_item_id );
				if ( ! $bundled_item_data instanceof WC_Bundled_Item_Data ) {
					continue;
				}

				$bundled_cart_visibility = $bundled_item_data->get_meta( 'cart_visibility' );

				if ( 'hidden' !== $bundled_cart_visibility ) {
					continue;
				}

				unset( $cart[ $cart_item_key ] );
			}

			return $cart;
		}
	}

	/**
	 * Checking WC Product Bundle existence
	 */
	if ( class_exists( 'WC_Bundles' ) ) {
		new BWFAN_Compatibility_With_WC_Product_Bundle();
	}
}
