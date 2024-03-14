<?php

/**
 * Password Protected
 * https://wordpress.org/plugins/password-protected/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_Password_Protected' ) ) {
	class BWFAN_Compatibility_With_Password_Protected {

		public function __construct() {
			add_filter( 'password_protected_is_active', array( $this, 'bwfan_allow_rest_api_password_protected' ), 100 );
		}

		/**
		 * Allow Autonami and WooFunnels endpoints in rest calls
		 *
		 * @param $status
		 *
		 * @return false|mixed
		 */
		public function bwfan_allow_rest_api_password_protected( $status ) {
			$rest_route = isset( $GLOBALS['wp']->query_vars['rest_route'] ) ? $GLOBALS['wp']->query_vars['rest_route'] : '';

			if ( empty( $rest_route ) ) {
				return $status;
			}

			if ( strpos( $rest_route, 'autonami' ) !== false || strpos( $rest_route, 'woofunnel' ) !== false ) {
				return false;
			}

			return $status;
		}
	}

	if ( class_exists( 'Password_Protected' ) ) {
		new BWFAN_Compatibility_With_Password_Protected();
	}
}
