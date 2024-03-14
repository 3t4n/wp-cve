<?php

namespace MailerSend;

class Mail {

	/**
	 * Constructor
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public function __construct() {

		add_action( 'phpmailer_init', [ $this, 'init' ], PHP_INT_MAX );
		add_action( 'wp_mail_failed', [ $this, 'mailFailed' ] );

		add_filter( 'wp_mail_from', [ $this, 'filterFromEmail' ], PHP_INT_MAX );
		add_filter( 'wp_mail_from_name', [ $this, 'filterFromName' ], PHP_INT_MAX );
	}

	/**
	 * PHP Mailer Hook Initialization
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public function init( $wp_mailer ) {

		// Configure PHP Mailer to use MailerSend SMTP
		$wp_mailer->isSMTP();
		$wp_mailer->Host       = defined( 'MAILERSEND_SMTP_HOST' ) ? MAILERSEND_SMTP_HOST : 'smtp.mailersend.net'; // SMTP host
		$wp_mailer->Port       = defined( 'MAILERSEND_SMTP_PORT' ) ? MAILERSEND_SMTP_PORT : 587; // SMTP port
		$wp_mailer->SMTPAuth   = true; // Enable SMTP authentication
		$wp_mailer->Username   = ConfigData::get( 'mailersend_smtp_user' ); // SMTP username
		$wp_mailer->Password   = ( ConfigData::configMode() !== 'default' ) ? ( defined( 'MAILERSEND_SMTP_PASSWORD' ) ? MAILERSEND_SMTP_PASSWORD : '' ) : ConfigData::get( 'mailersend_smtp_pwd' ); // SMTP password
		$wp_mailer->SMTPSecure = 'tls';

		// Set Reply-To if needed
		$this->setReplyTo( $wp_mailer );

		// Set Mail CC if needed
		$this->setMailCC( $wp_mailer );

		// Set Mail BCC if needed
		$this->setMailBCC( $wp_mailer );

		// Add custom tags
		$mailersend_tags = ConfigData::get( 'mailersend_sender_tags' );

		if ( strlen( $mailersend_tags ) > 0 ) {
			$wp_mailer->addCustomHeader( 'X-MailerSend-Tags', $mailersend_tags );
		}
	}

	/**
	 * Update From Email in PHP Mailer
	 *
	 * @access      public
	 *
	 * @param $email
	 *
	 * @return      string
	 * @since       1.0.0
	 */
	public function filterFromEmail( $email ): string {

		$sender_email = ConfigData::get( 'mailersend_sender_email' );

		return ! empty( $sender_email ) ? $sender_email : $email;
	}

	/**
	 * Update From Name in PHP Mailer
	 *
	 * @access      public
	 *
	 * @param $name
	 *
	 * @return      string
	 * @since       1.0.0
	 */
	public function filterFromName( $name ): string {

		$sender_name = ConfigData::get( 'mailersend_sender_name' );

		if ( empty( $name ) ) {
			$name = get_bloginfo( 'name' );
		}

		return ! empty( $sender_name ) ? $sender_name : $name;
	}

	/**
	 * Catch PHP Mailer Failed
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public function mailFailed( $error ) {

		$time_stamp = '[' . date( 'Y-m-d H:i:s' ) . '] - ';
		$message    = $error->get_error_code() . ' - ' . implode( ", ", $error->get_error_messages() );

		$error_list = get_transient( 'mailersend_error' );

		if ( $error_list === false ) {
			$error_list = [];
		}

		$error_list[] = $time_stamp . $message;

		set_transient( 'mailersend_error', $error_list, MINUTE_IN_SECONDS );

		// Log error in Debug Mode
		if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
			ob_start();
			if ( error_log( $time_stamp . $message . PHP_EOL, 3, MAILERSEND_SMTP_DIR . 'mailersend.log' ) === false ) {
				error_log( $message );
			}
			ob_end_clean();
		}
	}

	/**
	 * Set Reply-To in PHP Mailer
	 *
	 * @access      private
	 * @return      void
	 * @since       1.0.0
	 */
	private function setReplyTo( $wp_mailer ) {

		$email = ConfigData::get( ' mailersend_reply_to' );

		if ( ! empty( $email ) && filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {

			$wp_mailer->addReplyTo( $email );
		}
	}

	/**
	 * Set CC Emails in PHP Mailer
	 *
	 * @access      private
	 * @return      void
	 * @since       1.0.0
	 */
	private function setMailCC( $wp_mailer ) {

		$cc_emails = ConfigData::get( ' mailersend_recipient_cc' );

		foreach ( explode( ';', $cc_emails ) as $email ) {

			if ( filter_var( trim( $email ), FILTER_VALIDATE_EMAIL ) ) {

				$wp_mailer->AddCC( $email );
			}
		}
	}


	/**
	 * Set BCC Emails in PHP Mailer
	 *
	 * @access      private
	 * @return      void
	 * @since       1.0.0
	 */
	private function setMailBCC( $wp_mailer ) {

		$bcc_emails = ConfigData::get( ' mailersend_recipient_bcc' );

		foreach ( explode( ';', $bcc_emails ) as $email ) {

			if ( filter_var( trim( $email ), FILTER_VALIDATE_EMAIL ) ) {

				$wp_mailer->addBCC( $email );
			}
		}
	}
}
