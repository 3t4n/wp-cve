<?php
/**
 * Change 'Take this course' button label
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LearnDash_PowerPack_Take_This_Course_Button_Label', false ) ) {
	/**
	 * LearnDash_PowerPack_Take_This_Course_Button_Label Class.
	 */
	class LearnDash_PowerPack_Take_This_Course_Button_Label {
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
		public $text_label = 'learndash_take_this_course_button_label';

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_filter(
					'learndash_payment_closed_button',
					[ $this, 'learndash_payment_closed_button_func' ],
					30,
					2
				);
			}
		}

		/**
		 * Returns the HTML code for the payment button.
		 *
		 * @param String $custom_button The HTML code for the custom button.
		 * @param array  $payment_params The parameters for the payment.
		 *
		 * @return String The HTML code for the custom button for the payment.
		 */
		public function learndash_payment_closed_button_func( $custom_button = '', $payment_params = [] ) {
			$get_label_text = $this->get_label_text();

			if ( empty( $get_label_text ) ) {
				return $custom_button;
			}
			// Comma separated list of course ids to change the button. Leave empty array for all courses.
			$course_ids = [];

			// Replacement button text.
			$course_button_label = $get_label_text;

			/**
			 * If the $courses_ids is not empty and does not match the post ID passed via
			 * $payment_params['post'] then abort since this is not one of the courses we want to affect.
			 */
			if ( ( ! empty( $course_ids ) ) && ( ! in_array( $payment_params['post']->ID, $course_ids, true ) ) ) {
				return $custom_button;
			}

			/**
			 * Now if we are satisfied all the requirements are there, we parse the $custom_button
			 * HTML and extract the button label.
			 */
			preg_match_all( '/<a .*?>(.*?)<\/a>/', $custom_button, $matches );

			/**
			 * The preg_match_all() function will populate the $matches array. This array will have 2 nodes.
			 * Node [0] will contain the original HTML matching $custom_button.
			 * Node [1] will contain the displayed button text.
			 *
			 * [0] => Array (
			 *     [0] => <a class="btn-join" href="http://www.site.com" id="btn-join">Take this Course</a>
			 * )
			 * [1] => Array (
			 *     [0] => Take this Course
			 * )
			 */

			if ( ( is_array( $matches ) ) && ( isset( $matches[1] ) ) && ( ! empty( $matches[1] ) ) ) {
				// Finally we replace the button label in the $custom_button HTML.
				$custom_button = str_replace( $matches[1][0], $course_button_label, $custom_button );
			}

			// Always return $custom_button.
			return $custom_button;
		}

		/**
		 * Get the text for the label.
		 *
		 * @return String The label text.
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
		 * Add the class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'course', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Take this Course button label', 'learndash-powerpack' );
			$class_description = esc_html__( 'Replace the Take this Course button label for closed course', 'learndash-powerpack' );

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
			<div class=""><?php esc_html_e( 'Enter Take this Course button label', 'learndash-powerpack' ); ?></div>
			<div class="">
				<input type="text" placeholder="" class="" value="<?php echo esc_html( $get_label_text ); ?>" name="<?php echo esc_html( $this->text_label ); ?>" data-type="text">
			</div>
			<?php
			$html_options = ob_get_clean();

			return $html_options;
		}
	}

	new LearnDash_PowerPack_Take_This_Course_Button_Label();
}

