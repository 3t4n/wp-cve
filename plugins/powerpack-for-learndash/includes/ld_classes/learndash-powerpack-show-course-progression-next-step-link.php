<?php
/**
 * Show course progression next step link
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Show_Course_Progression_Next_Step_Link', false ) ) {
	/**
	 * LearnDash_PowerPack_Show_Course_Progression_Next_Step_Link Class.
	 */
	class LearnDash_PowerPack_Show_Course_Progression_Next_Step_Link {
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
				add_filter( 'learndash_show_next_link', [ $this, 'learndash_show_next_link_func' ], 10, 3 );
			}
		}

		/**
		 * Wether the next link will be showed to the user.
		 *
		 * @param bool $show_next_link The var to store if the next link will be show.
		 * @param int  $user_id The ID of the user.
		 * @param int  $post_id The ID of the post.
		 *
		 * @return bool Wether the next link will be show.
		 */
		public function learndash_show_next_link_func( $show_next_link = false, $user_id = 0, $post_id = 0 ) {
			// Example 1) Check if user is admin or group_leader.
			if ( ( user_can( $user_id, 'administrator' ) ) ) {
				$show_next_link = true;
			}

			// Example 2) Check post type
			// $post_type = get_post_type( $post_id );
			// if ( $post_type == 'sfwd-lessons')
			// $show_next_link = true.

			return $show_next_link;
		}

		/**
		 * Add the class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'course', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Show Course Progression Next Step Link for admin', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option will allow only admin to show Course Progression Next Step Link without completing the Lessons/Topic', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Show_Course_Progression_Next_Step_Link();
}

