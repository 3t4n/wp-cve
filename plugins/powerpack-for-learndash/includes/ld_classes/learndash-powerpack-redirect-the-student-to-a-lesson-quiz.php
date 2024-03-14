<?php
/**
 * Redirect studemt to a lesson quiz
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Redirect_The_Student_To_A_Lesson_Quiz', false ) ) {
	/**
	 * LearnDash_PowerPack_Redirect_The_Student_To_A_Lesson_Quiz Class.
	 */
	class LearnDash_PowerPack_Redirect_The_Student_To_A_Lesson_Quiz {
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
				add_filter( 'learndash_completion_redirect', [ $this, 'learndash_completion_redirect_func' ], 10, 2 );
			}
		}

		/**
		 * Redirects on Topic completion.
		 *
		 * @param String $link The link to redirect.
		 * @param int    $post_id The ID of the post.
		 *
		 * @return String The link to redirect.
		 */
		public function learndash_completion_redirect_func( $link, $post_id ) {
			// We only want to do this for Topics. But the below code can be adapted to work for Lessons.
			if ( 'sfwd-topic' === get_post_type( $post_id ) ) {
				// First we get the topic progress. This will return all the sibling topics.
				// More important it will show the next item.
				$progress = learndash_get_course_progress( null, $post_id );

				// Normally when the user completed topic #3 of #5 the 'next' element will point to the #4 topic.
				// But when the student reaches the end of the topic chain it will be empty.
				if ( ! empty( $progress ) && ( isset( $progress['next'] ) ) && ( empty( $progress['next'] ) ) ) {
					// So this is where we now want to get the parent lesson_id and determine if it has a quiz.
					$lesson_id = learndash_get_setting( $post_id, 'lesson' );
					if ( ! empty( $lesson_id ) ) {
						$lesson_quizzes = learndash_get_lesson_quiz_list( $lesson_id );
						if ( ! empty( $lesson_quizzes ) ) {
							// If we have some lesson quizzes we loop through these to find the first one not completed by the user.
							// This should be the first one but we don't want to assume.
							foreach ( $lesson_quizzes as $lesson_quiz ) {
								if ( 'notcompleted' === $lesson_quiz['status'] ) {
									// Once we find a non-completed quiz we set the $link to the quiz.
									// permalink then break out of out loop.
									$link = $lesson_quiz['permalink'];
									break;
								}
							}
						}
					}
				}
			}

			// Always return $link.
			return $link;
		}

		/**
		 * Add class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'lesson', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Lesson Quiz', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to redirect the student to a Lesson Quiz when they complete the last Lesson Topic.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Redirect_The_Student_To_A_Lesson_Quiz();
}

