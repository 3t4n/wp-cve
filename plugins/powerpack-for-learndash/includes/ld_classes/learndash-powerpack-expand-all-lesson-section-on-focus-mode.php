<?php
/**
 * Expand all lesson sections in focus mode
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LearnDash_PowerPack_Expand_All_Lesson_Section_On_Focus_Mode', false ) ) {
	/**
	 * LearnDash_PowerPack_Expand_All_Lesson_Section_On_Focus_Mode Class.
	 */
	class LearnDash_PowerPack_Expand_All_Lesson_Section_On_Focus_Mode {
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
					'learndash-nav-widget-expand-class',
					[ $this, 'learndash_nav_widget_expand_class_func' ],
					10,
					4
				);
			}
		}

		/**
		 * Add the 'ld-expanded' class to the child steps.
		 *
		 * @param String $expanded_class The value for the CSS class.
		 * @param int    $lesson_id The lesson ID.
		 * @param int    $course_id The course ID.
		 * @param int    $user_id The user ID.
		 *
		 * @return String The modified CSS class.
		 */
		public function learndash_nav_widget_expand_class_func( $expanded_class = '', $lesson_id = 0, $course_id = 0, $user_id = 0 ) {
			// keep all child steps expanded. To keep them closed always, change this to ''.
			$expanded_class = 'ld-expanded';

			return $expanded_class;
		}

		/**
		 * Add class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'lesson', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Lesson Section', 'learndash-powerpack' );
			$class_description = esc_html__( 'Expand all lesson section on Focus mode', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Expand_All_Lesson_Section_On_Focus_Mode();
}

