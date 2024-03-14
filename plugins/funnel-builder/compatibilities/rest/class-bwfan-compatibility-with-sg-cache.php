<?php

/**
 * SiteGround Optimizer
 *
 * https://wordpress.org/plugins/sg-cachepress/
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WFFN_Compatibility_With_SG_Cache' ) ) {
	class WFFN_Compatibility_With_SG_Cache {

		public function __construct() {
			add_filter( 'option_siteground_optimizer_excluded_urls', array( $this, 'exclude_endpoint_option' ), 100 );
			add_filter( 'default_option_siteground_optimizer_excluded_urls', array( $this, 'exclude_endpoint_option_default' ), 100 );
		}

		public function is_enable() {
			if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
				return true;
			}
			return false;
		}

		/**
		 * Exclude WooFunnels endpoints from SiteGround cache
		 *
		 * @param $value
		 *
		 * @return mixed
		 */
		public function exclude_endpoint_option( $value ) {
			if ( true !== $this->is_enable() ) {
				return $value;
			}

			$value[] = "/wp-json/funnelkit-app/*";
			$value[] = "/wp-json/woofunnels-analytics/*";
			$value[] = "/wp-json/woofunnels/*";
			$value[] = "/wp-json/woofunnel_customer/*";

			return $value;
		}

		/**
		 * Exclude WooFunnels endpoints from SiteGround cache
		 * Passing default arguments if none value set
		 *
		 * @param $default
		 *
		 * @return mixed
		 */
		public function exclude_endpoint_option_default( $default ) {
			if ( true !== $this->is_enable() ) {
				return $default;
			}

			$default[] = "/wp-json/funnelkit-app/*";
			$default[] = "/wp-json/woofunnels-analytics/*";
			$default[] = "/wp-json/woofunnels/*";
			$default[] = "/wp-json/woofunnel_customer/*";

			return $default;
		}
	}

	WFFN_Plugin_Compatibilities::register( new WFFN_Compatibility_With_SG_Cache(), 'rest_sg_cache' );
}
