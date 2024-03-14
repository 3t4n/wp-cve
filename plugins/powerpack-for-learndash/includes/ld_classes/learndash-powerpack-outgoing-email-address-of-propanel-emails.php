<?php
/**
 * Change outgoing email address of ProPanel emails
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LearnDash_PowerPack_Outgoing_Email_Address_Of_Propanel_Emails', false ) ) {
	/**
	 * LearnDash_PowerPack_Outgoing_Email_Address_Of_Propanel_Emails Class.
	 */
	class LearnDash_PowerPack_Outgoing_Email_Address_Of_Propanel_Emails {
		/**
		 * Current class name
		 *
		 * @var string
		 */
		public $current_class = '';

		/**
		 * Text label
		 *
		 * @var string
		 */
		public $text_label = 'outgoing_email_address_of_propanel_emails';

		/**
		 * Cosntructor
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_filter( 'ld_propanel_email_users_args', [ $this, 'learndash_ld_propanel_email_users_args_func' ] );
			}
		}

		/**
		 * Set the headers for ongoing emails.
		 *
		 * @param array $mail_args The arguments for the email.
		 *
		 * @return array The array passed as parameter with the modified data.
		 */
		public function learndash_ld_propanel_email_users_args_func( $mail_args ) {
			$get_label_text = $this->get_label_text();
			if ( empty( $get_label_text ) ) {
				return $mail_args;
			}
			$mail_args['headers'] = [
				'content-type: text/html',
				'From: LearnDash <info@yoursite.com>',
				'Reply-to: info@yoursite.com',
			];

			return $mail_args;
		}

		/**
		 * Get the label text.
		 *
		 * @return String The text for the label.
		 */
		public function get_label_text() {
			$get_option = get_option( $this->current_class );
			if ( is_array( $get_option ) || is_object( $get_option ) ) {
				foreach ( $get_option as $key => $data_val ) {
					return $data_val['value'];
				}
			}
		}

		/**
		 * Add class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'email', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Outgoing email address of ProPanel emails', 'learndash-powerpack' );
			$class_description = esc_html__( 'Change the outgoing email address of ProPanel emails', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => $this->get_form_input_fields(),
			];
		}

		/**
		 * Returns the HTML code for create the input field.
		 *
		 * @return String the HTML code for create the input field.
		 */
		public function get_form_input_fields() {
			$get_label_text = $this->get_label_text();
			ob_start();
			?>
			<div class=""><?php esc_html_e( 'Enter Outgoing email', 'learndash-powerpack' ); ?></div>
			<div class="">
				<input type="text" placeholder="" class="" value="<?php echo esc_html( $get_label_text ); ?>" name="<?php echo esc_html( $this->text_label ); ?>" data-type="text">
			</div>
			<?php
			$html_options = ob_get_clean();

			return $html_options;
		}
	}

	new LearnDash_PowerPack_Outgoing_Email_Address_Of_Propanel_Emails();
}

