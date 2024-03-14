<?php
/**
 * Display custom message on ld_course_list shortcode
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Display_A_Custom_Message_Shortcode', false ) ) {
	/**
	 * LearnDash_PowerPack_Display_A_Custom_Message_Shortcode Class.
	 */
	class LearnDash_PowerPack_Display_A_Custom_Message_Shortcode {
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
		public $text_label = 'learndash_custom_message_ld_course_list_shortcode';

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_filter( 'ld_course_list', [ $this, 'ld_course_list_func' ], 10, 3 );
			}
		}

		/**
		 * Shows a custom message if the courses list is empty.
		 *
		 * @param String $output HTML code for the courses list.
		 * @param array  $atts List of attributes.
		 * @param String $filter The filter to use.
		 *
		 * @return String The modified list with the custom message.
		 */
		public function ld_course_list_func( $output, $atts, $filter ) {
			$get_label_text = $this->get_label_text();

			if ( empty( $get_label_text ) ) {
				return $output;
			}
			// If the ld-course-list-items div does not have items/courses to display, use a custom message as output.
			if ( preg_match( '@<div class="ld-course-list-items row">\s+</div>@', $output ) ) {
				$output = '<strong>' . $get_label_text . '</strong>';
			}

			// Always return $output.
			return $output;
		}

		/**
		 * Return the label text.
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
		 * Add class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'course', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Custom message for a shortcode [ld_course_list]', 'learndash-powerpack' );
			$class_description = esc_html__( 'Display a custom message when ld_course_list shortcode returns no results.', 'learndash-powerpack' );

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
		 * @return String The HTML code for the input field.
		 */
		public function get_form_input_fields() {
			$get_label_text = $this->get_label_text();
			ob_start();
			?>
			<div class=""><?php esc_html_e( 'Enter Custom message for shortcode ld_course_list', 'learndash-powerpack' ); ?></div>
			<div class="">
				<input type="text" placeholder="" class="" value="<?php echo esc_html( $get_label_text ); ?>" name="<?php echo esc_html( $this->text_label ); ?>" data-type="text">
			</div>
			<?php
			$html_options = ob_get_clean();

			return $html_options;
		}
	}

	new LearnDash_PowerPack_Display_A_Custom_Message_Shortcode();
}

