<?php

/**
 * Password Protected
 * https://wordpress.org/plugins/password-protected/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WFFN_Compatibility_With_Password_Protected' ) ) {
	class WFFN_Compatibility_With_Password_Protected {

		public function __construct() {
			add_filter( 'password_protected_is_active', array( $this, 'allow_rest_api_password_protected' ), 100 );
		}

		public function is_enable() {
			if ( class_exists( 'Password_Protected' ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Allow WooFunnels endpoints in rest calls
		 *
		 * @param $status
		 *
		 * @return false|mixed
		 */
		public function allow_rest_api_password_protected( $status ) {

			if ( true !== $this->is_enable() ) {
				return $status;
			}

			$rest_route = isset( $GLOBALS['wp']->query_vars['rest_route'] ) ? $GLOBALS['wp']->query_vars['rest_route'] : '';

			if ( empty( $rest_route ) ) {
				return $status;
			}

			if ( strpos( $rest_route, 'woofunnel' ) !== false ) {
				return false;
			}

			return $status;
		}
	}

	WFFN_Plugin_Compatibilities::register( new WFFN_Compatibility_With_Password_Protected(), 'rest_password_protected' );
}
