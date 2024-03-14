<?php

/**
 * WordPress Multilingual Plugin
 * https://wpml.org/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_WPML' ) ) {
	class BWFAN_Compatibility_With_WPML {

		public function __construct() {
			add_action( 'bwfan_email_setup_locale', [ $this, 'translate_email_body' ] );
		}

		/**
		 * setup locale for email with translation plugins
		 *
		 * @param $lang
		 */
		public function translate_email_body( $lang ) {
			if ( empty( $lang ) ) {
				return;
			}

			global $woocommerce_wpml;
			if ( ! class_exists( 'woocommerce_wpml' ) || ! $woocommerce_wpml instanceof woocommerce_wpml ) {
				return;
			}
			$woocommerce_wpml->emails->change_email_language( $lang );
		}
	}

	if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
		new BWFAN_Compatibility_With_WPML();
	}
}
