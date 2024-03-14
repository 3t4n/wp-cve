<?php
/**
 * Show quiz continue button when student fails quiz
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Quiz_Continue_Button_On_Student_Fail', false ) ) {
	/**
	 * LearnDash_PowerPack_Quiz_Continue_Button_On_Student_Fail Class.
	 */
	class LearnDash_PowerPack_Quiz_Continue_Button_On_Student_Fail {
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
				add_filter(
					'show_quiz_continue_buttom_on_fail',
					[ $this, 'show_quiz_continue_buttom_on_fail_func' ],
					10,
					2
				);
			}
		}

		/**
		 * Shows the quiz continue button.
		 *
		 * @param bool $show_button whether show or not the button.
		 * @param int  $quiz_id The ID of the quiz.
		 *
		 * @return bool If show the button.
		 */
		public function show_quiz_continue_buttom_on_fail_func( $show_button = false, $quiz_id = 0 ) {
			// Example to show the continue button only on quiz 232
			// if ( $quiz_id == 232 ).
			$show_button = true;

			return $show_button;
		}

		/**
		 * Add the class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'quiz', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Quiz continue button option', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option will show Quiz continue button for all quiz if the student fails.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Quiz_Continue_Button_On_Student_Fail();
}

