<?php
/**
 * Change price type for open courses
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Change_The_Price_Type_For_Open_Courses', false ) ) {
	/**
	 * Learndash_Powerpack_Change_The_Price_Type_For_Open_Courses Class.
	 */
	class LearnDash_PowerPack_Change_The_Price_Type_For_Open_Courses {
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
				add_action( 'wp_footer', [ $this, 'learndash_wp_footer_price_type' ] );
			}
		}

		/**
		 * Set the course price type to 'closed' only if it is currently 'open'.
		 */
		public function learndash_wp_footer_price_type() {
			$course_query_args = [
				'post_type'   => 'sfwd-courses',
				'post_status' => 'publish',
				'fields'      => 'ids',
			];
			$course_query      = new WP_Query( $course_query_args );

			if ( ! empty( $course_query->posts ) ) {
				foreach ( $course_query->posts as $course_id ) {
					// Example #2: Set the course price type to 'closed' only if it is currently 'open'.
					$course_price_type = learndash_get_setting( $course_id, 'course_price_type' );
					if ( 'open' === $course_price_type ) {
						learndash_update_setting( $course_id, 'course_price_type', 'closed' );
					}
				}
			}
		}

		/**
		 * Add the class details.
		 *
		 * @return array The details for the class.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'course', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Change the Price Type for only open Courses', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to set the course price type to closed only if it is currently open.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Change_The_Price_Type_For_Open_Courses();
}

