<?php

/**
 * Wp-Rocket
 *
 * https://wp-rocket.me/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_Wp_Rocket' ) ) {
	class BWFAN_Compatibility_With_Wp_Rocket {

		public function __construct() {
			add_filter( 'rocket_cache_reject_uri', array( $this, 'exclude_autonami_endpoint_option' ), 100 );
		}

		/**
		 * Exclude Autonami and WooFunnels endpoints from wp-rocket cache
		 *
		 * @param $uris
		 *
		 * @return mixed
		 */
		public function exclude_autonami_endpoint_option( $uris ) {
			$uris[] = "/wp-json/" . BWFAN_API_NAMESPACE . "/*";
			$uris[] = "/wp-json/woofunnels/*";
			$uris[] = "/wp-json/funnelkit-automations/*";

			return $uris;
		}
	}

	if ( function_exists( 'rocket_clean_home' ) ) {
		new BWFAN_Compatibility_With_Wp_Rocket();
	}
}
