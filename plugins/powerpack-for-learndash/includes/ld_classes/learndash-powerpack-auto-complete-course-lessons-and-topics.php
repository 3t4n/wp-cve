<?php
/**
 * Auto complete course lessons and topics
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Auto_Complete_Course_Lessons_And_Topics', false ) ) {
	/**
	 * LearnDash_PowerPack_Auto_Complete_Course_Lessons_And_Topics Class.
	 */
	class LearnDash_PowerPack_Auto_Complete_Course_Lessons_And_Topics {
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
				add_action( 'template_redirect', [ $this, 'template_redirect_auto_complete_func' ] );
			}
		}

		/**
		 * Get the Mark Complete form/button.
		 *
		 * @param array $args The arguments list.
		 *
		 * @return String the HTML text to show the Mark Complete button or false.
		 */
		public function template_redirect_auto_complete_func( $args = [] ) {
			// Comma sperated course_ids to exclude from logic.
			$excluded_courses = [];

			$user_id = get_current_user_id();
			if ( ! $user_id ) {
				return false;
			}

			/**
			 * Never trust the global $post object. Too many plugins
			 * override this with no WP_Post content.
			 */
			$current_step_id = get_the_ID();
			if ( empty( $current_step_id ) ) {
				return false;
			}

			// Get the current course step POST and make sure it is one from LD.
			$current_step_post = get_post( $current_step_id );
			if ( ( ! $current_step_post ) || ( ! is_a( $current_step_post, 'WP_Post' ) ) || ( ! in_array( $current_step_post->post_type, learndash_get_post_types( 'course_steps' ), true ) ) ) {
				return false;
			}

			// Check that the current user has access.
			if ( ! sfwd_lms_has_access( $current_step_id, $user_id ) ) {
				return false;
			}

			// Get the course step.
			$course_id = learndash_get_course_id();
			if ( ! $course_id ) {
				return false;
			}

			// Check that we are not excluding this course.
			if ( in_array( $course_id, $excluded_courses, true ) ) {
				return false;
			}

			/**
			 * Get the Mark Complete form/button.
			 * If this does NOT return empty then the mark complete
			 * button would be shown on the page to the user. We use
			 * that to know if we can automatically mark the step
			 * complete here.
			 */
			$mark_html = learndash_mark_complete( $current_step_post );
			if ( ! empty( $mark_html ) ) {
				return learndash_process_mark_complete( $user_id, $current_step_id );
			}

			return false;
		}

		/**
		 * Add class details.
		 *
		 * @return array Class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'course', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Auto Complete Course Lessons and Topics', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to Auto Complete Course Lessons and Topics.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Auto_Complete_Course_Lessons_And_Topics();
}
