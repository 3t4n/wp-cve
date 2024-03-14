<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WFFN_Compatibility_WC_Cashfree
 * plugin weblink: https://www.gocashfree.com
 * plugin author: techsupport@gocashfree.com
 */

if ( ! class_exists( 'WFFN_Compatibility_WC_Cashfree' ) ) {
	class WFFN_Compatibility_WC_Cashfree {
		public function __construct() {
			add_filter( 'wc_cashfree_return_url', [ $this, 'return_url' ], 10, 1 );
		}

		public function is_enable() {
			if ( class_exists( 'WC_Gateway_cashfree' ) ) {
				return true;
			}

			return false;
		}

		/**
		 * add wffn-si params in return url for open wffn thankyou page
		 *
		 * @param $url
		 *
		 * @return mixed|string
		 */
		public function return_url( $url ) {
			if ( ! $this->is_enable() ) {
				return $url;
			}

			return add_query_arg( array( 'wffn-si' => WFFN_Core()->data->get_transient_key() ), $url );
		}
	}

	WFFN_Plugin_Compatibilities::register( new WFFN_Compatibility_WC_Cashfree(), 'wc_cashfree' );
}
