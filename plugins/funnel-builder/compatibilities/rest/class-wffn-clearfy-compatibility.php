<?php

/**
 * Clearfy Pro
 * https://wpshop.ru/plugins/clearfy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WFFN_Compatibility_With_Clearfy' ) ) {
	class WFFN_Compatibility_With_Clearfy {

		public function __construct() {
			add_filter( 'clearfy_rest_api_white_list', array( $this, 'whitelist_wffn_endpoints' ), 10, 1 );
		}

		public function is_enable() {
			if ( class_exists( 'Clearfy_Plugin' ) ) {
				return true;
			}

			return false;
		}

		/** white list wffn endpoints in clearfy pro plugin
		 *
		 * @param $white_list
		 *
		 * @return mixed
		 */
		public function whitelist_wffn_endpoints( $white_list ) {

			if ( true !== $this->is_enable() ) {
				return $white_list;
			}

			$white_list[] = 'woofunnels';
			$white_list[] = 'funnelkit-app';
			$white_list[] = 'woofunnels-analytics';
			$white_list[] = 'woofunnel_customer';

			return $white_list;
		}
	}

	WFFN_Plugin_Compatibilities::register( new WFFN_Compatibility_With_Clearfy(), 'rest_clearfy' );
}