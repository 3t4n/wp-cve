<?php
/**
 * Course Grid custom button text
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LearnDash_PowerPack_Course_Grid_Custom_Button_Text', false ) ) {
	/**
	 * LearnDash_PowerPack_Course_Grid_Custom_Button_Text Class.
	 */
	class LearnDash_PowerPack_Course_Grid_Custom_Button_Text {
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
		public $text_label = 'learndash_course_grid_custom_button_text';

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_filter(
					'learndash_course_grid_custom_button_text',
					[ $this, 'learndash_course_grid_custom_button_text_func' ],
					10,
					2
				);
			}
		}

		/**
		 * Returns the button text.
		 *
		 * @param String $button_text The text for the button.
		 * @param int    $post_id The ID for the post.
		 *
		 * @return String The text for the button.
		 */
		public function learndash_course_grid_custom_button_text_func( $button_text = '', $post_id = 0 ) {
			$get_label_text = $this->get_label_text();

			if ( empty( $get_label_text ) ) {
				return $button_text;
			}
			// Example 1.
			// Change button label to something custom.
			$button_text = $get_label_text;

			// Always return $button_text.
			return $button_text;
		}

		/**
		 * Returns the label text.
		 *
		 * @return String The label text.
		 */
		public function get_label_text() {
			$get_option = get_option( $this->current_class );
			if ( is_array( $get_option ) || is_object( $get_option ) ) {
				foreach ( $get_option as $key => $data_val ) {
					if ( empty( $data_val['value'] ) ) {
						continue;
					}
					return $data_val['value'];
				}
			}

			return '';
		}

		/**
		 * Add the class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'button', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Course grid custom button text', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to change course grid custom button text.', 'learndash-powerpack' );

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
		 * @return String The HTML code for create the input field.
		 */
		public function get_form_input_fields() {
			$get_label_text = $this->get_label_text();
			ob_start();
			?>
			<div
				class=""><?php esc_html_e( 'Enter Take course grid custom button text', 'learndash-powerpack' ); ?></div>
			<div class="">
				<input type="text" placeholder="" class="" value="<?php echo esc_html( $get_label_text ); ?>" name="<?php echo esc_html( $this->text_label ); ?>" data-type="text">
			</div>
			<?php
			$html_options = ob_get_clean();

			return $html_options;
		}
	}

	new LearnDash_PowerPack_Course_Grid_Custom_Button_Text();
}

