<?php

/**
 * WP Offload SES
 * https://wordpress.org/plugins/wp-ses/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_WP_SES' ) ) {
	class BWFAN_Compatibility_With_WP_SES {

		public function __construct() {
			add_action( 'bwfan_before_send_email', array( $this, 'disable_open_click_tracking' ) );
		}

		/**
		 * Disable open click tracking to improve performance
		 */
		public static function disable_open_click_tracking( $data ) {
			/** WP Offload SES option settings */
			$wp_ses_settings = get_option( 'wposes_settings' );
			if ( empty( $wp_ses_settings ) || ! is_array( $wp_ses_settings ) ) {
				return;
			}

			add_filter( 'pre_option_wposes_settings', function ( $value_return ) use ( $wp_ses_settings ) {
				$wp_ses_settings['enable-click-tracking'] = '0';
				$wp_ses_settings['enable-open-tracking']  = '0';

				return $wp_ses_settings;
			}, PHP_INT_MAX );
		}
	}

	if ( isset( $GLOBALS['wposes_meta'] ) ) {
		new BWFAN_Compatibility_With_WP_SES();
	}
}
