<?php

/**
 * SiteGround Optimizer
 *
 * https://wordpress.org/plugins/sg-cachepress/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_SG_Cache' ) ) {
	class BWFAN_Compatibility_With_SG_Cache {

		public function __construct() {
			/** Exclude FK endpoints from cache */
			add_filter( 'option_siteground_optimizer_excluded_urls', array( $this, 'exclude_endpoints' ), PHP_INT_MAX );
			add_filter( 'default_option_siteground_optimizer_excluded_urls', array( $this, 'exclude_endpoints' ), PHP_INT_MAX );
		}

		/**
		 * Exclude endpoints from SiteGround cache
		 *
		 * @param $value
		 *
		 * @return array|mixed
		 */
		public function exclude_endpoints( $value ) {
			$value = BWFAN_Common::make_array( $value );

			$value[] = "/wp-json/" . BWFAN_API_NAMESPACE . "/*";
			$value[] = "/wp-json/woofunnels/*";
			$value[] = "/wp-json/funnelkit-automations/*";

			return BWFAN_Common::unique( $value );
		}
	}

	if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
		new BWFAN_Compatibility_With_SG_Cache();
	}
}
