<?php

namespace MailerSend\Admin;

class NoticeView {

	/**
	 * Constructor
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public function __construct() {

	}

	/**
	 * Notice - Warning defined constant SMTP Password
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public static function warning_defined_smtp_found() {

		?>

        <div class="notice notice-warning mailersend-warning-declare-found-notice mailersend-notice is-dismissible">
            <p><?php _e( 'Please remove the defined MAILERSEND_SMTP_PASSWORD from your config file before proceeding.', 'mailersend-official-smtp-integration' ); ?></p>
        </div>
		<?php
	}

	/**
	 * Notice - Warning invalid recipient email address
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public static function invalid_recipient_email() {

		?>

        <div class="notice notice-warning mailersend-warning-declare-found-notice mailersend-notice is-dismissible">
            <p><?php _e( 'You have entered an invalid recipient email address.', 'mailersend-official-smtp-integration' ); ?></p>
        </div>
		<?php
	}

	/**
	 * Notice - Error could not save SMTP settings
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public static function error_could_not_save_setting() {

		?>

        <div class="notice notice-error mailersend-warning-declare-found-notice mailersend-notice is-dismissible">
            <p><?php _e( 'Could not save SMTP settings.', 'mailersend-official-smtp-integration' ); ?></p>
        </div>
		<?php
	}

	/**
	 * Notice - Error invalid email address
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public static function error_invalid_email() {

		?>

        <div class="notice notice-error mailersend-invalid-email-notice mailersend-notice is-dismissible">
            <p><?php _e( 'You have entered an invalid email address.', 'mailersend-official-smtp-integration' ); ?></p>
        </div>
		<?php
	}

	/**
	 * Notice - Error invalid CC email address
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public static function error_invalid_email_cc() {

		?>

        <div class="notice notice-error mailersend-invalid-email-notice mailersend-notice is-dismissible">
            <p><?php _e( 'You have entered an invalid CC email address.', 'mailersend-official-smtp-integration' ); ?></p>
        </div>
		<?php
	}

	/**
	 * Notice - Error invalid BCC email address
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public static function error_invalid_email_bcc() {

		?>

        <div class="notice notice-error mailersend-invalid-email-notice mailersend-notice is-dismissible">
            <p><?php _e( 'You have entered an invalid BCC email address.', 'mailersend-official-smtp-integration' ); ?></p>
        </div>
		<?php
	}

	/**
	 * Notice - Error invalid Reply-To email address
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public static function error_invalid_email_reply_to() {

		?>

        <div class="notice notice-error mailersend-invalid-email-notice mailersend-notice is-dismissible">
            <p><?php _e( 'You have entered an invalid Reply-To email address.', 'mailersend-official-smtp-integration' ); ?></p>
        </div>
		<?php
	}


	/**
	 * Notice - Error invalid name
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public static function error_invalid_name() {

		?>

        <div class="notice notice-error mailersend-invalid-email-notice mailersend-notice is-dismissible">
            <p><?php _e( 'You have entered an invalid name.', 'mailersend-official-smtp-integration' ); ?></p>
        </div>
		<?php
	}

	/**
	 * Notice - Error invalid credentials
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public static function error_invalid_credentials() {

		?>

        <div class="notice notice-error mailersend-invalid-email-notice mailersend-notice is-dismissible">
            <p><?php _e( 'Invalid username or password.', 'mailersend-official-smtp-integration' ); ?></p>
        </div>
		<?php
	}

	/**
	 * Notice - Error while sending test email
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public static function error_sending_test_email() {

		?>

        <div class="notice notice-error mailersend-invalid-email-notice mailersend-notice is-dismissible">
            <p><?php _e( 'There was a problem sending the test email.', 'mailersend-official-smtp-integration' ); ?></p>
        </div>
		<?php
	}

	/**
	 * Notice - Success on sending test email
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public static function success_sending_test_email() {

		?>

        <div class="notice notice-success mailersend-invalid-email-notice mailersend-notice is-dismissible">
            <p><?php _e( 'A test email was sent successfully.', 'mailersend-official-smtp-integration' ); ?></p>
        </div>
		<?php
	}

	/**
	 * Notice - Success on SMTP settings saved
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public static function successfully_saved() {

		?>

        <div class="notice notice-success mailersend-successfully-saved-notice mailersend-notice is-dismissible">
            <p><?php _e( 'SMTP settings were successfully saved.', 'mailersend-official-smtp-integration' ); ?></p>
        </div>
		<?php
	}

	/**
	 * Notice - Success on deleting SMTP settings
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public static function successfully_deleted() {

		?>

        <div class="notice notice-success mailersend-successfully-deleted-notice mailersend-notice is-dismissible">
            <p><?php _e( 'SMTP Settings were removed.', 'mailersend-official-smtp-integration' ); ?></p>
        </div>
		<?php
	}

	/**
	 * Notice - Error maximum tags exceeded
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public static function error_maximum_tags_exceeded() {

		?>

        <div class="notice notice-error mailersend-warning-declare-found-notice mailersend-notice is-dismissible">
            <p><?php _e( 'You can save a maximum of 5 tags.', 'mailersend-official-smtp-integration' ); ?></p>
        </div>
		<?php
	}
}
