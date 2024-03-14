<?php

/**
 * Force Login
 * https://wordpress.org/plugins/wp-force-login/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_Force_Login' ) ) {
	class BWFAN_Compatibility_With_Force_Login {

		public function __construct() {
			add_filter( 'rest_jsonp_enabled', array( $this, 'bwfan_allow_rest_apis_with_force_login' ), 100 );
		}

		/**
		 * Allow Autonami and WooFunnels endpoints in rest calls
		 *
		 * @param $status
		 *
		 * @return mixed
		 */
		public function bwfan_allow_rest_apis_with_force_login( $status ) {
			$rest_route = $GLOBALS['wp']->query_vars['rest_route'];

			if ( false !== strpos( $rest_route, 'autonami' ) || false !== strpos( $rest_route, 'woofunnel' ) || false !== strpos( $rest_route, 'funnelkit' ) ) {
				remove_filter( 'rest_authentication_errors', 'v_forcelogin_rest_access', 99 );
			}

			return $status;
		}
	}

	if ( function_exists( 'v_forcelogin_rest_access' ) ) {
		new BWFAN_Compatibility_With_Force_Login();
	}
}
