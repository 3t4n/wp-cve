<?php
/**
 * Show course progression next step link to all users
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Show_Course_Progression_Next_Step_Link_All_User', false ) ) {
	/**
	 * LearnDash_PowerPack_Show_Course_Progression_Next_Step_Link_All_User Class.
	 */
	class LearnDash_PowerPack_Show_Course_Progression_Next_Step_Link_All_User {
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
		 * Returns wether the next link will be showed to the user.
		 *
		 * @param bool $show_next_link The var to storage if the next link will be showed.
		 * @param int  $user_id The ID of the user.
		 * @param int  $post_id The ID of the post.
		 *
		 * @return bool Wether the next link will be showed to the user.
		 */
		public function learndash_show_next_link_func( $show_next_link = false, $user_id = 0, $post_id = 0 ) {
			if ( is_user_logged_in() ) {
				$show_next_link = true;
			}

			return $show_next_link;
		}

		/**
		 * Add the class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'course', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Show Course Progression Next Step Link for all users', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option will allow all user to see Course Progression Next Step Link without completing the Lessons/Topic', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Show_Course_Progression_Next_Step_Link_All_User();
}

