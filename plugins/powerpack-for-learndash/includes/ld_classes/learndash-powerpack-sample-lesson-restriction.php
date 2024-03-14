<?php
/**
 * Sample lesson restriction
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Sample_Lesson_Restriction', false ) ) {
	/**
	 * LearnDash_PowerPack_Sample_Lesson_Restriction Class.
	 */
	class LearnDash_PowerPack_Sample_Lesson_Restriction {
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
				add_filter( 'learndash_lesson_is_sample', [ $this, 'learndash_lesson_is_sample_func' ], 10, 2 );
			}
		}

		/**
		 * To get if the lesson is a sample lesson.
		 *
		 * @param bool $is_sample The var to store wether the lesson is sample or not.
		 * @param Post $post The post object.
		 *
		 * @return bool Whether the lesson is Sample lesson or not.
		 */
		public function learndash_lesson_is_sample_func( $is_sample, $post ) {
			if ( true === $is_sample ) {
				// Example 1: We want to only allow logged in users to access samples.
				if ( ! is_user_logged_in() ) {
					$is_sample = false;
				}
			}

			// Always return $is_sample.
			return $is_sample;
		}

		/**
		 * Add the class details.
		 *
		 * @return array The class details
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'lesson', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Sample lesson', 'learndash-powerpack' );
			$class_description = esc_html__( 'Only allow logged in users to access samples', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Sample_Lesson_Restriction();
}

