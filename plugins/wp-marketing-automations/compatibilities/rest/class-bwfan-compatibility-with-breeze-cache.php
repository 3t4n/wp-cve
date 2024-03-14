<?php

/**
 * Breeze
 *
 * https://wordpress.org/plugins/breeze/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_Breeze_Cache' ) ) {
	class BWFAN_Compatibility_With_Breeze_Cache {

		public function __construct() {
			add_filter( 'option_breeze_advanced_settings', array( $this, 'exclude_autonami_endpoint_urls' ), 999 );
		}

		/**
		 * Exclude Autonami endpoints from cache
		 *
		 * @param $options
		 *
		 * @return mixed
		 */
		public function exclude_autonami_endpoint_urls( $options ) {
			$new_urls = [ site_url( 'wp-json/' . BWFAN_API_NAMESPACE . '/*' ), site_url( 'wp-json/woofunnels/*' ), site_url( 'wp-json/funnelkit-automations/*' ) ];

			$excluded_urls = isset( $options['breeze-exclude-urls'] ) && is_array( $options['breeze-exclude-urls'] ) ? $options['breeze-exclude-urls'] : [];
			$excluded_urls = array_unique( array_merge( $new_urls, $excluded_urls ) );
			sort( $excluded_urls );

			$options['breeze-exclude-urls'] = $excluded_urls;

			return $options;
		}
	}

	if ( defined( 'BREEZE_VERSION' ) ) {
		new BWFAN_Compatibility_With_Breeze_Cache();
	}
}
