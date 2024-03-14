<?php
/**
 * Hide open option from course access settings
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Hide_The_Open_Option_From_Course_Access_Settings', false ) ) {
	/**
	 * LearnDash_PowerPack_Hide_The_Open_Option_From_Course_Access_Settings Class.
	 */
	class LearnDash_PowerPack_Hide_The_Open_Option_From_Course_Access_Settings {
		/**
		 * Current class name
		 *
		 * @var string
		 */
		public $current_class = '';

		/**
		 * Construct
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_action( 'admin_head', [ $this, 'learndash_admin_head_func' ] );
			}
		}

		/**
		 * Returns the style for the admin head.
		 */
		public function learndash_admin_head_func() {
			echo '<style>
			#learndash-course-access-settings_course_price_type_field > span:nth-child(2) > div:nth-child(1) > fieldset:nth-child(1) > p:nth-child(2) {
			  display: none;
			}
			#learndash-course-access-settings_course_price_type_field > span:nth-child(2) > div:nth-child(1) > fieldset:nth-child(1) > p:nth-child(3) {
			display: none;
			}
		  </style>';
		}

		/**
		 * Add class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'course', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Course Access Settings', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to hide the Open option from Course Access Settings', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Hide_The_Open_Option_From_Course_Access_Settings();
}

