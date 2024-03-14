<?php

namespace MailerSend;

class Actions {

	/**
	 * Action constructor
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public function __construct() {

	}

	/**
	 * Save SMTP Credentials
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public function saveCredentials() {

		// check if user is allowed to manage options
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// check referer and nonce
		if ( ! empty( $_POST ) && check_admin_referer( 'update_credentials', 'mailersend_update_nonce' ) ) {

			// check for smtp user field
			if ( isset( $_POST['smtp_user'] ) ) {

				$smtp_user = sanitize_text_field( $_POST['smtp_user'] );

				if ( ConfigData::set( 'mailersend_smtp_user', $smtp_user ) === false ) {
					add_action( 'admin_notices', [
						__NAMESPACE__ . '\\Admin\\NoticeView',
						'error_could_not_save_setting'
					] );
				}

			}

			// check for smtp password field
			if ( isset( $_POST['smtp_pass'] ) ) {

				$smtp_pass = $_POST['smtp_pass'];

				if ( ConfigData::set( 'mailersend_smtp_pwd', $smtp_pass ) === false ) {
					add_action( 'admin_notices', [
						__NAMESPACE__ . '\\Admin\\NoticeView',
						'error_could_not_save_setting'
					] );
				}
			}

			// check if config file option was enabled
			if ( isset( $_POST['smtp_config_file'] ) ) {

				$config_mode = sanitize_text_field( $_POST['smtp_config_file'] );

				if ( $config_mode !== '1' ) {
					return;
				}

				if ( ConfigData::set( 'mailersend_config_mode', 'config_file' ) === false ) {

					add_action( 'admin_notices', [
						__NAMESPACE__ . '\\Admin\\NoticeView',
						'error_could_not_save_setting'
					] );
				}

			} else {

				// if config file option not enabled, set back to default (database)
				if ( ConfigData::set( 'mailersend_config_mode', 'default' ) === false ) {
					add_action( 'admin_notices', [
						__NAMESPACE__ . '\\Admin\\NoticeView',
						'error_could_not_save_setting'
					] );
				}
			}

			// return configuration saved feedback
			add_action( 'admin_notices', [ __NAMESPACE__ . '\\Admin\\NoticeView', 'successfully_saved' ] );
		}
	}

	/**
	 * Save SMTP Configuration
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public function saveConfig() {

		// check if user is allowed to manage options
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// check referer and nonce
		if ( ! empty( $_POST ) && check_admin_referer( 'update_settings', 'mailersend_update_nonce' ) ) {

			// check for tags
			if ( isset( $_POST['sender_tags'] ) ) {

				$sender_tags = sanitize_text_field( $_POST['sender_tags'] );

				$tags = array_map( 'trim', array_filter( explode( ',', $sender_tags ) ) );
				
				if ( count( $tags ) > 5 ) {
					add_action( 'admin_notices', [
						__NAMESPACE__ . '\\Admin\\NoticeView',
						'error_maximum_tags_exceeded'
					] );
				}

				$sender_tags = implode( ', ', array_slice( $tags, 0, 5 ) );

				if ( ConfigData::set( 'mailersend_sender_tags', $sender_tags ) === false ) {
					add_action( 'admin_notices', [
						__NAMESPACE__ . '\\Admin\\NoticeView',
						'error_could_not_save_setting'
					] );
				}
			}

			// check for sender name field
			if ( isset( $_POST['sender_name'] ) ) {

				$sender_name = sanitize_text_field( $_POST['sender_name'] );

				if ( ConfigData::set( 'mailersend_sender_name', $sender_name ) === false ) {
					add_action( 'admin_notices', [
						__NAMESPACE__ . '\\Admin\\NoticeView',
						'error_could_not_save_setting'
					] );
				}
			}

			// check for sender email field
			if ( isset( $_POST['sender_email'] ) ) {

				$sender_email = '';

				if ( ! empty( $_POST['sender_email'] ) ) {
					$sender_email = sanitize_text_field( $_POST['sender_email'] );

					if ( ! filter_var( trim( $sender_email ), FILTER_VALIDATE_EMAIL ) ) {

						add_action( 'admin_notices', [ __NAMESPACE__ . '\\Admin\\NoticeView', 'error_invalid_email' ] );

						return;
					}
				}

				if ( ConfigData::set( 'mailersend_sender_email', trim( $sender_email ) ) === false ) {
					add_action( 'admin_notices', [
						__NAMESPACE__ . '\\Admin\\NoticeView',
						'error_could_not_save_setting'
					] );
				}
			}

			// check for recipient cc field
			if ( isset( $_POST['cc_recipient'] ) ) {

				$recipient_cc = sanitize_text_field( $_POST['cc_recipient'] );

				if ( ! empty( $recipient_cc ) ) {

					foreach ( explode( ';', $recipient_cc ) as $cc_email ) {

						if ( ! filter_var( trim( $cc_email ), FILTER_VALIDATE_EMAIL ) ) {

							add_action( 'admin_notices', [
								__NAMESPACE__ . '\\Admin\\NoticeView',
								'error_invalid_email_cc'
							] );

							return;
						}
					}
					if ( ConfigData::set( 'mailersend_recipient_cc', '' ) === false ) {
						add_action( 'admin_notices', [
							__NAMESPACE__ . '\\Admin\\NoticeView',
							'error_could_not_save_setting'
						] );
					}
				}

				if ( ConfigData::set( 'mailersend_recipient_cc', $recipient_cc ) === false ) {
					add_action( 'admin_notices', [
						__NAMESPACE__ . '\\Admin\\NoticeView',
						'error_could_not_save_setting'
					] );
				}
			}

			// check for recipient bcc field
			if ( isset( $_POST['bcc_recipient'] ) ) {

				$recipient_bcc = sanitize_text_field( $_POST['bcc_recipient'] );

				if ( ! empty( $recipient_bcc ) ) {

					foreach ( explode( ';', $recipient_bcc ) as $bcc_email ) {

						if ( ! filter_var( trim( $bcc_email ), FILTER_VALIDATE_EMAIL ) ) {

							add_action( 'admin_notices', [
								__NAMESPACE__ . '\\Admin\\NoticeView',
								'error_invalid_email_bcc'
							] );

							return;
						}
					}
				}

				if ( ConfigData::set( 'mailersend_recipient_bcc', $recipient_bcc ) === false ) {
					add_action( 'admin_notices', [
						__NAMESPACE__ . '\\Admin\\NoticeView',
						'error_could_not_save_setting'
					] );
				}
			}

			// check for reply-to field
			if ( isset( $_POST['reply_to'] ) ) {

				$reply_to = sanitize_text_field( $_POST['reply_to'] );

				if ( ! empty( $reply_to ) ) {

					if ( ! filter_var( $reply_to, FILTER_VALIDATE_EMAIL ) ) {

						add_action( 'admin_notices', [
							__NAMESPACE__ . '\\Admin\\NoticeView',
							'error_invalid_email_reply_to'
						] );

						return;
					}
				}

				if ( ConfigData::set( 'mailersend_reply_to', $reply_to ) === false ) {
					add_action( 'admin_notices', [
						__NAMESPACE__ . '\\Admin\\NoticeView',
						'error_could_not_save_setting'
					] );
				}
			}

			// return configuration saved feedback
			add_action( 'admin_notices', [ __NAMESPACE__ . '\\Admin\\NoticeView', 'successfully_saved' ] );
		}
	}

	/**
	 * Send Test Email
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public function sendTest() {

		if ( ! empty( $_POST ) && check_admin_referer( 'mailer_test', 'mailersend_test_nonce' ) ) {

			$recipient = '';

			if ( ! empty( $_POST['test_recipient'] ) ) {
				$recipient = sanitize_email( $_POST['test_recipient'] );
			}

			if ( ! filter_var( $recipient, FILTER_VALIDATE_EMAIL ) ) {

				add_action( 'admin_notices', [ __NAMESPACE__ . '\\Admin\\NoticeView', 'invalid_recipient_email' ] );

				return;
			}

			// Send the test mail.
			$result = wp_mail(
				$recipient,
				esc_html__( 'Welcome to MailerSend SMTP!' ),
				esc_html__( 'This email has been sent by MailerSend SMTP relay server.' )
			);

			if ( $result ) {
				add_action( 'admin_notices', [ __NAMESPACE__ . '\\Admin\\NoticeView', 'success_sending_test_email' ] );
			} else {
				add_action( 'admin_notices', [ __NAMESPACE__ . '\\Admin\\NoticeView', 'error_sending_test_email' ] );
			}
		}
	}

	/**
	 * Delete plugin configuration
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public function deleteConfig() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ConfigData::hasConfigCredentials() === true ) {

			// warn user about defined SMTP Password constant
			add_action( 'admin_notices', [ __NAMESPACE__ . '\\Admin\\NoticeView', 'warning_defined_smtp_found' ] );

			return;
		}

		ConfigData::deleteData();

		// return deletion completed feedback
		add_action( 'admin_notices', [ __NAMESPACE__ . '\\Admin\\NoticeView', 'successfully_deleted' ] );
	}

}
