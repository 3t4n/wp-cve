<?php
/**
 * Stop use of the_content filter on LearnDash CPTs
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Post_Type_To_Stop_Use_Of_The_Content_Filter', false ) ) {
	/**
	 * LearnDash_PowerPack_Post_Type_To_Stop_Use_Of_The_Content_Filter Class.
	 */
	class LearnDash_PowerPack_Post_Type_To_Stop_Use_Of_The_Content_Filter {
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
				add_filter( 'learndash_post_args', [ $this, 'learndash_post_args_func' ], 10, 1 );
			}
		}

		/**
		 * Modifies the post arguments array.
		 *
		 * @param array $post_args The post args.
		 *
		 * @return array The modified post args.
		 */
		public function learndash_post_args_func( $post_args = [] ) {
			// As an example we want to affect only Lessons.
			// We need to set the 'template_redirect' element in the lessons array to false.
			if ( isset( $post_args['sfwd-lessons']['template_redirect'] ) ) {
				$post_args['sfwd-lessons']['template_redirect'] = false;
			}

			if ( isset( $post_args['sfwd-courses']['template_redirect'] ) ) {
				$post_args['sfwd-courses']['template_redirect'] = false;
			}

			if ( isset( $post_args['sfwd-topic']['template_redirect'] ) ) {
				$post_args['sfwd-topic']['template_redirect'] = false;
			}

			if ( isset( $post_args['sfwd-quiz']['template_redirect'] ) ) {
				$post_args['sfwd-quiz']['template_redirect'] = false;
			}

			if ( isset( $post_args['sfwd-question']['template_redirect'] ) ) {
				$post_args['sfwd-question']['template_redirect'] = false;
			}

			// Then return the $post_args array.
			return $post_args;
		}

		/**
		 * Add class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'wp_filter', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Stop use of the_content filter to display content', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option will Stop use of the_content filter to display content for LearnDash Post Type.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Post_Type_To_Stop_Use_Of_The_Content_Filter();
}

