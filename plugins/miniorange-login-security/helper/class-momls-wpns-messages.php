<?php
/**
 * This file has all the notifications that are shown throughout the plugin.
 *
 * @package miniorange-login-security/helper/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'Momls_Wpns_Messages' ) ) {
	/**
	 * This Class has all the notifications that are shown throughout the plugin.
	 */
	class Momls_Wpns_Messages {

		const TWOFA_ENABLED                   = 'Two Factor protection has been enabled.';
		const TWOFA_DISABLED                  = 'Two Factor protection has been disabled.';
		const TWO_FA_ON_LOGIN_PROMPT_ENABLED  = '2FA prompt on the WP Login Page Enabled.';
		const TWO_FA_ON_LOGIN_PROMPT_DISABLED = '2FA prompt on the WP Login Page Disabled.';
		const SUPPORT_FORM_VALUES             = 'Please submit your query along with email.';
		const SUPPORT_FORM_SENT               = 'Thanks for getting in touch! We shall get back to you shortly.';
		const SUPPORT_FORM_ERROR              = 'Your query could not be submitted. Please try again.';
		const DEACTIVATE_PLUGIN               = 'Plugin deactivated successfully';
		const UNKNOWN_ERROR                   = 'Error processing your request. Please try again.';
		const CONFIG_SAVED                    = 'Configuration saved successfully.';
		const REQUIRED_FIELDS                 = 'Please enter all the required fields';
		const RESET_PASS                      = 'You password has been reset successfully and sent to your registered email. Please check your mailbox.';
		const FEEDBACK                        = "<div class='custom-notice notice notice-warning feedback-notice'><p><p class='notice-message'>Looking for a feature? Help us make the plugin better. Send us your feedback using the Support Form below.</p><button class='feedback notice-button'><i>Dismiss</i></button></p></div>";
		const PASS_LENGTH                     = 'Choose a password with minimum length 6.';
		const ERR_OTP_EMAIL                   = 'There was an error in sending email. Please click on Resend OTP to try again.';
		const OTP_SENT                        = 'A passcode is sent to {{method}}. Please enter the otp below.';
		const REG_SUCCESS                     = 'Your account has been retrieved successfully.';
		const ACCOUNT_EXISTS                  = 'You already have an account with miniOrange. Please enter a valid password.';
		const INVALID_CRED                    = 'Invalid username or password. Please try again.';
		const REQUIRED_OTP                    = 'Please enter a value in OTP field.';
		const INVALID_OTP                     = 'Invalid one time passcode. Please enter a valid passcode.';
		const INVALID_PHONE                   = 'Please enter the phone number in the following format: <b>+##country code## ##phone number##';
		const PASS_MISMATCH                   = 'Password and Confirm Password do not match.';
		const WARNING                         = 'Please select folder for backup';
		const INVALID_EMAIL                   = 'Please enter valid Email ID';
		const TWO_FACTOR_ENABLE               = 'Two-factor is enabled. Configure it in the Two-Factor tab.';
		const TWO_FACTOR_DISABLE              = 'Two-factor is disabled.';
		const NONCE_ERROR                     = 'Nonce error';
		const NOTIF_DISABLE                   = 'Notifications are disabled.';
		const CUSTOMER_NOT_VALID              = 'The customer is not valid';
		const CURL_ERROR                      = 'Please try again';

		/**
		 * Return actual messages according to the key.
		 *
		 * @param string $message key of the message to be shown.
		 * @param array  $data Array.
		 * @return string
		 */
		public static function momls_show_message( $message, $data = array() ) {
			$message = constant( 'self::' . $message );
			foreach ( $data as $key => $value ) {
				$message = str_replace( '{{' . $key . '}}', $value, $message );
			}
			return $message;
		}

	}
}


