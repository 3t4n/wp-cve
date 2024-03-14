<?php
/**
 * Change Stripe purchase button text
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Change_Stripe_Purchase_Button_Text', false ) ) {
	/**
	 * LearnDash_PowerPack_Change_Stripe_Purchase_Button_Text Class.
	 */
	class LearnDash_PowerPack_Change_Stripe_Purchase_Button_Text {
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
		public $text_label = 'learndash_sample_lesson_lable';

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_filter(
					'learndash_stripe_purchase_button_text',
					[ $this, 'learndash_stripe_purchase_button_text_func' ]
				);
			}
		}

		/**
		 * Creates the label for the Stripe payment button.
		 *
		 * @param String $label The Label to display.
		 *
		 * @return String The content for the label.
		 */
		public function learndash_stripe_purchase_button_text_func( $label ) {
			$get_label_text = $this->get_label_text();

			if ( empty( $get_label_text ) ) {
				return $label;
			}

			return $get_label_text;
		}

		/**
		 * Returns the label text for the Stripe payment button.
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

			return '';
		}

		/**
		 * Get the class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'stripe', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Stripe Purchase Button Text', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to change Stripe Purchase Button Text', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => $this->get_form_input_fields(),
			];
		}

		/**
		 * Returns the HTML code for the input field.
		 *
		 * @return String The HTML code to create the input field.
		 */
		public function get_form_input_fields() {
			$get_label_text = $this->get_label_text();
			ob_start();
			?>
			<div class=""><?php esc_html_e( 'Button Text', 'learndash-powerpack' ); ?></div>
			<div class="">
				<input type="text" placeholder="" class="" value="<?php echo esc_html( $get_label_text ); ?>" name="<?php echo esc_html( $this->text_label ); ?>" data-type="text">
			</div>
			<?php
			$html_options = ob_get_clean();

			return $html_options;
		}
	}

	new LearnDash_PowerPack_Change_Stripe_Purchase_Button_Text();
}

