<?php

/**
 * Perfmatters
 * https://perfmatters.io/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WFFN_Compatibility_With_Perfmatters' ) ) {
	class WFFN_Compatibility_With_Perfmatters {

		public function __construct() {
			add_filter( 'rest_jsonp_enabled', array( $this, 'allow_rest_apis_with_perfmatters' ), 100 );
		}

		public function is_enable() {
			if ( defined( 'PERFMATTERS_VERSION' ) ) {
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
		public function allow_rest_apis_with_perfmatters( $status ) {

			if ( true !== $this->is_enable() ) {
				return $status;
			}

			if ( ! is_array( $GLOBALS['wp']->query_vars ) || ! isset( $GLOBALS['wp']->query_vars['rest_route'] ) ) {
				return $status;
			}
			$rest_route = $GLOBALS['wp']->query_vars['rest_route'];

			if ( ! is_null( $rest_route ) && strpos( $rest_route, 'woofunnel' ) !== false ) {
				remove_filter( 'rest_authentication_errors', 'perfmatters_rest_authentication_errors', 20 );
			}

			return $status;
		}
	}

	WFFN_Plugin_Compatibilities::register( new WFFN_Compatibility_With_Perfmatters(), 'rest_perfmatters' );
}
