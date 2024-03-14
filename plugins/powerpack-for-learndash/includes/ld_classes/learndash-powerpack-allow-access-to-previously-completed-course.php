<?php
/**
 * Allow acces to previously ccompleted courses
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LearnDash_PowerPack_Allow_Access_To_Previously_Completed_Course', false ) ) {
	/**
	 * LearnDash_PowerPack_Allow_Access_To_Previously_Completed_Course Class.
	 */
	class LearnDash_PowerPack_Allow_Access_To_Previously_Completed_Course {
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
				add_filter( 'sfwd_lms_has_access', [ $this, 'sfwd_lms_has_access_func' ], 1001, 3 );
			}
		}

		/**
		 * Checks if the user has access.
		 *
		 * @param boolean $has_access If the user has access.
		 * @param int     $step_id The ID of the step.
		 * @param int     $user_id The ID of the user.
		 *
		 * @return boolean $has_access whether the user has access.
		 */
		public function sfwd_lms_has_access_func( $has_access = false, $step_id = 0, $user_id = 0 ) {
			// Only override if current access is false.
			if ( ! $has_access ) {
				if ( empty( $user_id ) ) {
					$user_id = get_current_user_id();
				}

				if ( ( ! empty( $user_id ) ) && ( ! empty( $step_id ) ) ) {
					$course_id = learndash_get_course_id( $step_id );
					if ( ! empty( $course_id ) ) {
						$user_meta_course_progress = get_user_meta( $user_id, '_sfwd-course_progress', true );
						if ( ( is_array( $user_meta_course_progress ) ) && ( isset( $user_meta_course_progress[ $course_id ] ) ) ) {
							// If here the user does not have access but had access to the course at some point.
							$step_post_type = get_post_type( $step_id );
							if ( 'sfwd-courses' === $step_post_type ) {
								$has_access = true;
							} elseif ( 'sfwd-lessons' === $step_post_type ) {
								// If the user has previously completed the course > lesson then allow access.
								if ( ( isset( $user_meta_course_progress[ $course_id ]['lessons'][ $step_id ] ) ) && ( $user_meta_course_progress[ $course_id ]['lessons'][ $step_id ] ) ) {
									$has_access = true;
								}
							} elseif ( 'sfwd-topic' === $step_post_type ) {
								$lesson_id = learndash_get_lesson_id( $step_id, $course_id );
								if ( ! empty( $lesson_id ) ) {
									// If the user has previously completed the course > lesson > topic then allow access.
									if ( ( isset( $user_meta_course_progress[ $course_id ]['topics'][ $lesson_id ][ $step_id ] ) ) && ( $user_meta_course_progress[ $course_id ]['topics'][ $lesson_id ][ $step_id ] ) ) {
										$has_access = true;
									}
								}
							}
						}
					}
				}
			}

			// Always return $has_access.
			return $has_access;
		}

		/**
		 * Add class details.
		 *
		 * @return array Class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'course', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Allow access to previously completed course', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to allow access to previously completed course steps after course access is removed.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Allow_Access_To_Previously_Completed_Course();
}

