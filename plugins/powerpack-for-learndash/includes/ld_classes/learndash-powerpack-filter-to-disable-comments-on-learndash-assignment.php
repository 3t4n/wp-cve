<?php
/**
 * Disable comments on LearnDash assignments
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LearnDash_PowerPack_Filter_To_Disable_Comments_On_Learndash_Assignment', false ) ) {
	/**
	 * LearnDash_PowerPack_Filter_To_Disable_Comments_On_Learndash_Assignment Class.
	 */
	class LearnDash_PowerPack_Filter_To_Disable_Comments_On_Learndash_Assignment {
		/**
		 * Current class name
		 *
		 * @var string
		 */
		public $current_class = '';

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_filter( 'comments_open', [ $this, 'comments_open_func' ], 10, 2 );
			}
		}

		/**
		 * Returns wether the comments are open.
		 *
		 * @param bool $open The comment status.
		 * @param int  $post_id The post ID.
		 *
		 * @return bool The status of the comment.
		 */
		public function comments_open_func( $open, $post_id ) {
			// Check that $post_id is not empty. This filter is called sometimes with empty $post_id.
			if ( ! empty( $post_id ) ) {
				// Get the post from $post_id and check that it is valid WP_Post and an Assignment.
				$post = get_post( $post_id );
				if ( ( $post ) && ( $post instanceof WP_Post ) && ( 'sfwd-assignment' === $post->post_type ) ) {
					// Now check the user capability. Here we are checking if the current user is.
					// administrator. But can be checked in for other atrributes.
					if ( ! current_user_can( 'administrator' ) ) {
						// If the user is not an admin we set the open to false.
						$open = false;
					}
				}
			}

			// Always return $open.
			return $open;
		}

		/**
		 * Add the class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'comment', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Disable comments on LearnDash Assignment', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option disable comments on LearnDash Assignment.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Filter_To_Disable_Comments_On_Learndash_Assignment();
}

