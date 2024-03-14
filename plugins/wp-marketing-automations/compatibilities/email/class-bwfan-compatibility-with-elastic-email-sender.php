<?php

/**
 * Elastic Email Sender
 * https://wordpress.org/plugins/elastic-email-sender/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_Elastic_Email' ) ) {
	class BWFAN_Compatibility_With_Elastic_Email {

		public function __construct() {
			add_action( 'bwfan_before_send_email', array( $this, 'remove_elastic_email_settings' ) );
		}

		/**
		 * Disable elastic email settings
		 *
		 * @return void
		 */
		public static function remove_elastic_email_settings( $data ) {
			/** Set mime type by default text/html **/
			if ( 'texthtml' === get_option( 'ee_mimetype' ) ) {
				return;
			}

			add_filter( 'pre_option_ee_mimetype', function ( $value ) {
				return 'texthtml';
			}, PHP_INT_MAX );

			/** Set From email **/
			add_filter( 'pre_option_ee_config_from_name', function ( $return ) use ( $data ) {
				$from_email = isset( $data['senders_email'] ) ? $data['senders_email'] : $data['from_email'];

				return ! empty( $from_email ) ? $from_email : $return;
			}, PHP_INT_MAX );

			/** Set From email **/
			add_filter( 'pre_option_ee_config_from_email', function ( $return ) use ( $data ) {
				$from_name = isset( $data['senders_name'] ) ? $data['senders_name'] : $data['from_name'];

				return ! empty( $from_name ) ? $from_name : $return;
			}, PHP_INT_MAX );
		}
	}

	if ( class_exists( 'eemail' ) ) {
		new BWFAN_Compatibility_With_Elastic_Email();
	}
}
