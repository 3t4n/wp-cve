<?php

/**
 * Force Login
 * https://wordpress.org/plugins/wp-force-login/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WFFN_Compatibility_With_Force_Login' ) ) {
	class WFFN_Compatibility_With_Force_Login {

		public function __construct() {
			add_filter( 'rest_jsonp_enabled', array( $this, 'allow_rest_apis_with_force_login' ), 100 );
		}

		public function is_enable() {
			if ( function_exists( 'v_forcelogin_rest_access' ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Allow WooFunnels endpoints in rest calls
		 *
		 * @param $status
		 *
		 * @return mixed
		 */
		public function allow_rest_apis_with_force_login( $status ) {

			if ( true !== $this->is_enable() ) {
				return $status;
			}

			$rest_route = $GLOBALS['wp']->query_vars['rest_route'];

			if ( !is_null($rest_route) && strpos( $rest_route, 'woofunnel' ) !== false ) {
				remove_filter( 'rest_authentication_errors', 'v_forcelogin_rest_access', 99 );
			}

			return $status;
		}
	}

	WFFN_Plugin_Compatibilities::register( new WFFN_Compatibility_With_Force_Login(), 'rest_force_login' );
}
