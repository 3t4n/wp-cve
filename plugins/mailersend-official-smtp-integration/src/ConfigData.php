<?php

namespace MailerSend;

class ConfigData {

	/**
	 * Get option
	 *
	 * @access      public
	 *
	 * @param $key
	 *
	 * @return      string
	 * @since       1.0.0
	 */
	public static function get( $key ): string {

		// return option
		return get_option( $key, '' );
	}

	/**
	 * Save option
	 *
	 * @access      public
	 *
	 * @param $key
	 * @param $value
	 *
	 * @return      boolean
	 * @since       1.0.0
	 */
	public static function set( $key, $value ): bool {

		if ( get_option( $key ) === false ) {

			// save option
			return add_option( $key, $value, '', false );
		} else {

			// check if user is allowed to manage options
			if ( ! current_user_can( 'manage_options' ) ) {
				return false;
			}

			// update option if necessary
			update_option( $key, $value, false );

			return true;
		}
	}

	/**
	 * Check if SMTP credentials exist
	 *
	 * @access      public
	 * @return      boolean
	 * @since       1.0.0
	 */
	public static function hasCredentials(): bool {

		$smtp_user = self::get( 'mailersend_smtp_user' );
		$smtp_pass = self::get( 'mailersend_smtp_pwd' );

		if ( self::configMode() !== 'default' ) {
			$smtp_pass = defined( 'MAILERSEND_SMTP_PASSWORD' ) ? MAILERSEND_SMTP_PASSWORD : '';
		}

		// check if user and pass option exist and not empty
		if ( ! empty( $smtp_user ) && ! empty( $smtp_pass ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if SMTP credentials are declared
	 *
	 * @access      public
	 * @return      boolean
	 * @since       1.0.0
	 */
	public static function hasConfigCredentials(): bool {

		$smtp_pass = defined( 'MAILERSEND_SMTP_PASSWORD' ) ? MAILERSEND_SMTP_PASSWORD : '';

		// check if pass is declared and not empty
		if ( ! empty( $smtp_pass ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if SMTP credentials storage mode
	 *
	 * @access      public
	 * @return      string
	 * @since       1.0.0
	 */
	public static function configMode(): string {

		return get_option( 'mailersend_config_mode', 'default' );
	}

	/**
	 * Delete all MailerSend options
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public static function deleteData() {

		// available mailersend options
		$ops = [
			'mailersend_smtp_user',
			'mailersend_smtp_pwd',
			'mailersend_config_mode',
			'mailersend_sender_name',
			'mailersend_sender_email',
			'mailersend_recipient_cc',
			'mailersend_recipient_bcc',
			'mailersend_reply_to'
		];

		// loop through options and delete
		foreach ( $ops as $option ) {
			delete_option( $option );
		}

		// delete transients
		delete_transient( 'mailersend_error' );

		// deactivate plugin
		deactivate_plugins( MAILERSEND_SMTP_BASENAME );

		// redirect and exit
		wp_redirect( admin_url( 'plugins.php' ) );
		exit;
	}
}
