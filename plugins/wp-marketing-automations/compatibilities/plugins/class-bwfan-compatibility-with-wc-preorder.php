<?php

/**
 * WooCommerce Pre-Orders
 * https://woocommerce.com/products/woocommerce-pre-orders/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_WC_PreOrders' ) ) {
	class BWFAN_Compatibility_With_WC_PreOrders {

		public function __construct() {
			add_filter( 'woocommerce_order_is_paid_statuses', array( $this, 'append_order_status' ) );
		}

		/**
		 * passing wc-pre-order status as paid order
		 *
		 * @param $status
		 *
		 * @return mixed
		 */
		public function append_order_status( $status ) {
			$status[] = 'wc-pre-ordered';

			return $status;
		}
	}

	if ( class_exists( 'WC_Pre_Orders' ) ) {
		new BWFAN_Compatibility_With_WC_PreOrders();
	}
}
