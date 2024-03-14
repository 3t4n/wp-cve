<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WFFN_Compatibility_WC_Deposit
 * plugin weblink: https://www.webtomizer.com/
 * plugin site: https://woocommerce-deposits.com/
 */

if ( ! class_exists( 'WFFN_Compatibility_WC_Deposit' ) ) {
	class WFFN_Compatibility_WC_Deposit {
		public function __construct() {
			add_filter( 'wfty_maybe_update_order', [ $this, 'maybe_wc_deposit_order' ] );

			//update order for funnel tacking meta box
			add_filter( 'bwf_tracking_insert_order', [ $this, 'maybe_wc_deposit_order' ] );
		}

		public function is_enable() {
			if ( function_exists( 'wc_deposits_woocommerce_is_active' ) && wc_deposits_woocommerce_is_active() ) {
				return true;
			}
			return false;
		}

		/**
		 * @param $order
		 *
		 * @return bool|mixed|WC_Order|WC_Order_Refund
		 */
		public function maybe_wc_deposit_order( $order ) {

			if ( ! $this->is_enable() ) {
				return $order;
			}

			if ( $order && $order->get_type() === 'wcdp_payment' ) {
				$order = wc_get_order( $order->get_parent_id() );
			}

			return $order;
		}
	}


	WFFN_Plugin_Compatibilities::register( new WFFN_Compatibility_WC_Deposit(), 'wc_deposit' );
}
