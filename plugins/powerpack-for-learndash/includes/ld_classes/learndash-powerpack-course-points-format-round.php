<?php
/**
 * Course points format rounding
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Course_Points_Format_Round', false ) ) {
	/**
	 * LearnDash_PowerPack_Course_Points_Format_Round Class.
	 */
	class LearnDash_PowerPack_Course_Points_Format_Round {
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
		public $text_label = 'learndash_custom_decimal_points_setting';

		/**
		 * Constrcutor
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_filter(
					'learndash_course_points_format_round',
					[ $this, 'learndash_course_points_format_round_func' ]
				);
			}
		}

		/**
		 * Set the decimal places to 2.
		 *
		 * @param int $decimal_places The decimal places number.
		 *
		 * @return int The decimal places modified to 2.
		 */
		public function learndash_course_points_format_round_func( $decimal_places = 1 ) {
			$get_label_text = $this->get_label_text();

			if ( ! isset( $get_label_text ) ) {
				return $decimal_places;
			}
			// Change the used decimal places to 2.
			$decimal_places = absint( $get_label_text );

			// Always return the $decimal_places.
			return $decimal_places;
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
			$class_title       = esc_html__( 'Course Points Format', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to select decimal place formatting outside of the [courseinfo] shortcode.', 'learndash-powerpack' );

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
			<div class=""><?php esc_html_e( 'Enter decimal points used, 0 or higher', 'learndash-powerpack' ); ?></div>
			<div class="">
				<input type="text" placeholder="" class="" value="<?php echo esc_html( $get_label_text ); ?>" name="<?php echo esc_html( $this->text_label ); ?>" data-type="text">
			</div>
			<?php
			$html_options = ob_get_clean();

			return $html_options;
		}
	}

	new LearnDash_PowerPack_Course_Points_Format_Round();
}

