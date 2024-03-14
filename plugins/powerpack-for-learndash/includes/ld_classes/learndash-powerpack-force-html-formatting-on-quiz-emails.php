<?php
/**
 * Force HTML in quiz emails
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LearnDash_PowerPack_Force_Html_Formatting_On_Quiz_Emails', false ) ) {
	/**
	 * LearnDash_PowerPack_Force_Html_Formatting_On_Quiz_Emails Class.
	 */
	class LearnDash_PowerPack_Force_Html_Formatting_On_Quiz_Emails {
		/**
		 * Current class name
		 *
		 * @var string
		 */
		public $current_class = '';

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_filter( 'learndash_quiz_email', [ $this, 'learndash_quiz_email_func' ] );
				add_filter( 'learndash_quiz_email_admin', [ $this, 'learndash_quiz_email_admin_func' ] );
			}
		}

		/**
		 * Formats the email messages for the user emails.
		 *
		 * @param array $email_params The parameters for the email.
		 *
		 * @return array The modified array.
		 */
		public function learndash_quiz_email_func( $email_params = [] ) {
			$global_mapper = new WpProQuiz_Model_GlobalSettingsMapper();
			$user_email    = $global_mapper->getUserEmailSettings();

			// If the email setting are using HTML we use WP to format the message.
			if ( ( isset( $user_email['html'] ) ) && ( $user_email['html'] ) ) {
				if ( ( isset( $email_params['msg'] ) ) && ( ! empty( $email_params['msg'] ) ) ) {
					$email_params['msg'] = wpautop( $email_params['msg'] );
				}
			}

			// Always return $email_params.
			return $email_params;
		}

		/**
		 * Formats the email messages for the admin emails.
		 *
		 * @param array $email_params The parameters for the email.
		 *
		 * @return array The modified array.
		 */
		public function learndash_quiz_email_admin_func( $email_params = [] ) {
			$global_mapper = new WpProQuiz_Model_GlobalSettingsMapper();
			$admin_email   = $global_mapper->getEmailSettings();

			// If the email setting are using HTML we use WP to format the message.
			if ( ( isset( $admin_email['html'] ) ) && ( $admin_email['html'] ) ) {
				if ( ( isset( $email_params['msg'] ) ) && ( ! empty( $email_params['msg'] ) ) ) {
					$email_params['msg'] = wpautop( $email_params['msg'] );
				}
			}

			// Always return $email_params.
			return $email_params;
		}

		/**
		 * Add class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'quiz', 'learndash-powerpack' );
			$class_title       = esc_html__( 'HTML formatting', 'learndash-powerpack' );
			$class_description = esc_html__( 'Force HTML formatting on Quiz emails.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Force_Html_Formatting_On_Quiz_Emails();
}

