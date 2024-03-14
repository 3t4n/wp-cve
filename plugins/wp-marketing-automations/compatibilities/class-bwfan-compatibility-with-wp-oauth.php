<?php

/**
 * WP OAuth Server
 * https://wordpress.org/plugins/oauth2-provider/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_WP_Oauth' ) ) {
	class BWFAN_Compatibility_With_WP_Oauth {

		public function __construct() {
			add_filter( 'rest_jsonp_enabled', array( $this, 'bwfan_allow_rest_apis_with_wp_oauth' ) );
		}

		/**
		 * @param $status
		 *
		 * @return mixed
		 */
		public function bwfan_allow_rest_apis_with_wp_oauth( $status ) {
			$rest_route = $GLOBALS['wp']->query_vars['rest_route'];
			if ( strpos( $rest_route, 'autonami' ) !== false || strpos( $rest_route, 'woofunnel' ) !== false ) {
				BWFAN_Common::remove_actions( 'rest_authentication_errors', 'WO_Server', 'wpoauth_block_unauthenticated_rest_requests' );
			}

			return $status;
		}
	}

	if ( defined( 'WPOAUTH_VERSION' ) && class_exists( 'WO_SERVER' ) ) {
		new BWFAN_Compatibility_With_WP_Oauth();
	}
}
