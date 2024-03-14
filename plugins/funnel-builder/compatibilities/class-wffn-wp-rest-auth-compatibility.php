<?php

/**
 * Class WFFN_REST_AUTH_Compatibility
 * plugin weblink: https://wordpress.org/plugins/wp-rest-api-authentication/
 * plugin author: miniOrange
 */

if ( ! class_exists( 'WFFN_REST_AUTH_Compatibility' ) ) {
	class WFFN_REST_AUTH_Compatibility {
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
		 * @return bool
		 */
		public function allow_rest_apis() {
			$rest_route = $GLOBALS['wp']->query_vars['rest_route'];
			if ( !is_null($rest_route) && (false !== strpos( $rest_route, 'autonami' ) || false !== strpos( $rest_route, 'woofunnel' )) ) {
				return true;
			}

			return false;
		}
	}


	WFFN_Plugin_Compatibilities::register( new WFFN_REST_AUTH_Compatibility(), 'rest_auth' );
}


