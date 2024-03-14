<?php

/**
 * WordPress REST API Authentication
 * By miniOrange
 * https://wordpress.org/plugins/wp-rest-api-authentication/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WFFN_Compatibility_With_WP_Rest_Authenticate' ) ) {
	class WFFN_Compatibility_With_WP_Rest_Authenticate {

		public function __construct() {
			add_filter( 'dra_allow_rest_api', [ $this, 'allow_rest_apis' ] );
		}

		public function is_enable() {
			if ( function_exists( 'mo_api_auth_activate_miniorange_api_authentication' ) ) {
				return true;
			}
			return false;
		}

		/**
		 * Allow WooFunnels endpoints in rest calls
		 *
		 * @return bool
		 */
		public function allow_rest_apis() {

			if ( true !== $this->is_enable() ) {
				return false;
			}

			$rest_route = $GLOBALS['wp']->query_vars['rest_route'];
			if ( !is_null($rest_route) && false !== strpos( $rest_route, 'woofunnel' ) ) {
				return true;
			}

			return false;
		}
	}

	WFFN_Plugin_Compatibilities::register( new WFFN_Compatibility_With_WP_Rest_Authenticate(), 'rest_authenticate' );
}
