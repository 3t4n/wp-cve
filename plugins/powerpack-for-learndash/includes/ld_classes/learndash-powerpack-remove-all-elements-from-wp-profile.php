<?php
/**
 * Remove all elements from WP profile
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Remove_All_Elements_From_Wp_Profile', false ) ) {
	/**
	 * LearnDash_PowerPack_Remove_All_Elements_From_Wp_Profile Class.
	 */
	class LearnDash_PowerPack_Remove_All_Elements_From_Wp_Profile {
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
				add_action( 'admin_init', [ $this, 'learndash_admin_init_remove_element' ] );
			}
		}

		/**
		 * Removes elements from WP Profile Class.
		 */
		public function learndash_admin_init_remove_element() {
			global $sfwd_lms;

			// Removes 'User Enrolled in Courses’ and 'User Enrolled in Groups’ sections.
			remove_action( 'load-profile.php', [ $sfwd_lms->ld_admin_user_profile_edit, 'on_load_user_profile' ] );
			remove_action( 'load-user-edit.php', [ $sfwd_lms->ld_admin_user_profile_edit, 'on_load_user_profile' ] );

			remove_action( 'show_user_profile', [ $sfwd_lms->ld_admin_user_profile_edit, 'show_user_profile' ] );
			remove_action( 'edit_user_profile', [ $sfwd_lms->ld_admin_user_profile_edit, 'show_user_profile' ] );

			// Remove the 'Course Info’ section.
			remove_action( 'show_user_profile', [ $sfwd_lms, 'show_course_info' ] );
			remove_action( 'edit_user_profile', [ $sfwd_lms, 'show_course_info' ] );

			// Removes the 'Permanently Delete Course Data’ section.
			remove_action( 'show_user_profile', 'learndash_delete_user_data_link', 1000 );
			remove_action( 'edit_user_profile', 'learndash_delete_user_data_link', 1000 );
		}

		/**
		 * Add class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'WordPress', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Elements', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to remove all elements from WP Profile.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Remove_All_Elements_From_Wp_Profile();
}

