<?php
/**
 * Add Custom Content to the single course template output
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LearnDash_PowerPack_Add_Custom_Content_To_The_Single_Course_Template_Output ', false ) ) {
	/**
	 * LearnDash_PowerPack_Add_Custom_Content_To_The_Single_Course_Template_Output Class.
	 */
	class LearnDash_PowerPack_Add_Custom_Content_To_The_Single_Course_Template_Output {
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
		public $text_label = 'learndash_custom_content_to_the_single_course_template';

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_filter(
					'ld_after_course_status_template_container',
					[ $this, 'ld_after_course_status_template_container_func' ],
					10,
					4
				);
			}
		}

		/**
		 * Creates the template after courses
		 *
		 * @param String $output the var to store the output.
		 * @param String $course_status The status of the course.
		 * @param int    $course_id The ID of the course.
		 * @param int    $user_id The ID of the user.
		 */
		public function ld_after_course_status_template_container_func( $output = '', $course_status = 'not_started', $course_id = 0, $user_id = 0 ) {
			$get_label_text = $this->get_label_text();

			if ( empty( $get_label_text ) ) {
				return $output;
			}
			if ( 'completed' === $course_status ) {
				$output .= '<p>' . $get_label_text . '</p>';
			}

			return $output;
		}

		/**
		 * Class details
		 *
		 * @return array with the details of the class.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'course', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Custom content to the single Course template output', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to add custom content to the single Course template output after the Course Status complete.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => $this->get_form_input_fields(),
			];
		}

		/**
		 * Get label text
		 *
		 * @return String The value of the label.
		 */
		public function get_label_text() {
			$get_option = get_option( $this->current_class );
			if ( is_array( $get_option ) ) {
				foreach ( $get_option as $key => $data_val ) {
					return $data_val['value'];
				}
			}

			return '';
		}

		/**
		 * Get the input field.
		 *
		 * @return String The html options for the input field.
		 */
		public function get_form_input_fields() {
			$get_label_text = $this->get_label_text();
			ob_start();
			?>
			<div class=""><?php esc_html_e( 'Enter custom content text', 'learndash-powerpack' ); ?></div>
			<div class="">
				<input type="text" placeholder="" class="" value="<?php echo esc_html( $get_label_text ); ?>" name="<?php echo esc_html( $this->text_label ); ?>" data-type="text">
			</div>
			<?php
			$html_options = ob_get_clean();

			return $html_options;
		}
	}

	new LearnDash_PowerPack_Add_Custom_Content_To_The_Single_Course_Template_Output();
}

