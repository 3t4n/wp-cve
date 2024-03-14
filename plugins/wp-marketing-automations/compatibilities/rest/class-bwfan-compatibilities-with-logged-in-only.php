<?php

/**
 * Logged-in-only
 * https://wordpress.org/plugins/wp-logged-in-only/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_Logged_In_Only' ) ) {
	class BWFAN_Compatibility_With_Logged_In_Only {

		public function __construct() {
			add_filter( 'rest_jsonp_enabled', array( $this, 'bwfan_allow_rest_apis' ), 100 );
		}

		/**
		 * Allow Autonami and WooFunnels endpoints in rest calls
		 *
		 * @param $status
		 *
		 * @return mixed
		 */
		public function bwfan_allow_rest_apis( $status ) {
			$rest_route = $GLOBALS['wp']->query_vars['rest_route'];

			if ( false !== strpos( $rest_route, 'autonami' ) || false !== strpos( $rest_route, 'woofunnel' ) || false !== strpos( $rest_route, 'funnelkit' ) ) {
				remove_filter( 'rest_authentication_errors', 'logged_in_only_rest_api' );
			}

			return $status;
		}
	}

	if ( function_exists( 'logged_in_only_rest_api' ) ) {
		new BWFAN_Compatibility_With_Logged_In_Only();
	}
}
