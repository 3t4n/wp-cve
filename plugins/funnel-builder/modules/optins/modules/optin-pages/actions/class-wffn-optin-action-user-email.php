<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * This class will Create send email after optin form submit
 * Class WFFN_Optin_Action_User_Email
 */
if ( ! class_exists( 'WFFN_Optin_Action_User_Email' ) ) {
	#[AllowDynamicProperties]

  class WFFN_Optin_Action_User_Email extends WFFN_Optin_Action {

		private static $slug = 'user_email_notification';
		private static $ins = null;
		public $priority = 40;

		/**
		 * WFFN_Optin_Action_User_Email constructor.
		 */
		public function __construct() {
			parent::__construct();
		}

		/**
		 * @return WFFN_Optin_Action_User_Email|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public static function get_slug() {
			return self::$slug;
		}

		/**
		 * @param $posted_data
		 * @param $fields_settings
		 * @param $optin_action_settings
		 *
		 * @return array|bool|mixed
		 */
		public function handle_action( $posted_data, $fields_settings, $optin_action_settings ) {
			$posted_data = parent::handle_action( $posted_data, $fields_settings, $optin_action_settings );

			if ( ! is_array( $posted_data ) || ( is_array( $posted_data ) && count( $posted_data ) < 1 ) ) {
				return $posted_data;
			}

			$current_step = WFFN_Core()->data->get_current_step();
			$option       = get_post_meta( absint( $current_step['id'] ), 'wffn_actions_custom_settings', true );

			if ( ! is_array( $option ) || ( is_array( $option ) && isset( $option['lead_enable_notify'] ) && ! wffn_string_to_bool( $option['lead_enable_notify'] ) ) ) {
				return $posted_data;
			}

			$optin_email = $this->get_optin_data( WFFN_Optin_Pages::WFOP_EMAIL_FIELD_SLUG );
			$content     = apply_filters( 'the_content', $option['lead_notification_body'] );

			$modified_content = WFFN_Common::modify_content_emogrifier( $content );
			if ( ! empty( $modified_content ) ) {
				$content = $modified_content;
			}
			$db_options = WFOPP_Core()->optin_pages->get_option();
			$subject    = do_shortcode( $option['lead_notification_subject'] );
			$this->trigger_email( $optin_email, $subject, $db_options['op_user_name'], $db_options['op_user_email'], $db_options['op_user_email_reply'], $content );

			return $posted_data;
		}

		public function test_email( $option ) {
			$db_options  = WFOPP_Core()->optin_pages->get_option();
			$optin_email = $option['test_email'];
			$content     = apply_filters( 'the_content', $option['lead_notification_body'] );

			$modified_content = WFFN_Common::modify_content_emogrifier( $content );
			if ( ! empty( $modified_content ) ) {
				$content = $modified_content;
			}
			$subject  = do_shortcode( $option['lead_notification_subject'] );
			$result   = $this->trigger_email( $optin_email, $subject, $db_options['op_user_name'], $db_options['op_user_email'], $db_options['op_user_email_reply'], $content );
			$response = [ 'success' => true ];

			if ( ! $result ) {
				$response['success'] = false;
			}

			return $response;
		}

		public function trigger_email( $send_email, $subject, $from_name, $from_email, $reply_to_email, $content ) {
			$content = WFFN_Common::wffn_correct_protocol_url( $content );

			$to      = $send_email;
			$subject = stripslashes( $subject );
			$headers = 'From: ' . $from_name . ' <' . $from_email . '>' . "\r\n";
			$headers .= "Reply-To: " . $reply_to_email . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

			$message = '<html><body>';
			$message .= '<table style="width: 100%" cellpadding="5" cellspacing="5" border="0">';
			$message .= $content;
			$message .= "</table>";
			$message .= "</body></html>";

			return wp_mail( $to, $subject, $message, $headers ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_mail_wp_mail

		}

	}

	if ( class_exists( 'WFOPP_Core' ) ) {
		WFOPP_Core()->optin_actions->register( WFFN_Optin_Action_User_Email::get_instance() );
	}
}
