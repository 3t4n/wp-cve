<?php
/**
 * Display course content below prerequisite message
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LearnDash_PowerPack_Display_Course_Content_Below_Prerequisite_Message', false ) ) {
	/**
	 * LearnDash_PowerPack_Display_Course_Content_Below_Prerequisite_Message Class.
	 */
	class LearnDash_PowerPack_Display_Course_Content_Below_Prerequisite_Message {
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
				add_action( 'learndash-alert-after', [ $this, 'learndash_alert_after_func' ] );
			}
		}

		/**
		 * Displays Course Content.
		 */
		public function learndash_alert_after_func() {
			$courseid = learndash_get_course_id();
			$user_id  = get_current_user_id();

			if ( $courseid && $user_id ) {
				if ( ! learndash_course_completed( $user_id, $courseid ) ) {
					$coursecontent = get_the_content();
					echo esc_html( $coursecontent );
				}
			}
		}

		/**
		 * Add the class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'course', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Display course content below prerequisite message', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to Display course content below prerequisite message.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Display_Course_Content_Below_Prerequisite_Message();
}
