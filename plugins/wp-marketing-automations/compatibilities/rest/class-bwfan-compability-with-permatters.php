<?php

/**
 * Perfmatters
 * https://perfmatters.io/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_Perfmatters' ) ) {
	class BWFAN_Compatibility_With_Perfmatters {

		public function __construct() {
			add_filter( 'rest_jsonp_enabled', array( $this, 'bwfan_allow_rest_apis_with_perfmatters' ), 100 );
		}

		/**
		 * Allow Autonami and WooFunnels endpoints in rest calls
		 *
		 * @param $status
		 *
		 * @return mixed
		 */
		public function bwfan_allow_rest_apis_with_perfmatters( $status ) {
			if ( ! is_array( $GLOBALS['wp']->query_vars ) || ! isset( $GLOBALS['wp']->query_vars['rest_route'] ) ) {
				return $status;
			}

			$rest_route = $GLOBALS['wp']->query_vars['rest_route'];

			if ( strpos( $rest_route, 'autonami' ) !== false || strpos( $rest_route, 'woofunnel' ) !== false ) {
				remove_filter( 'rest_authentication_errors', 'perfmatters_rest_authentication_errors', 20 );
			}

			return $status;
		}
	}

	if ( defined( 'PERFMATTERS_VERSION' ) ) {
		new BWFAN_Compatibility_With_Perfmatters();
	}
}
