<?php
/**
 * Unenroll The User From All Common Groups
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Unenroll_The_User_From_All_Common_Groups', false ) ) {
	/**
	 * LearnDash_PowerPack_Unenroll_The_User_From_All_Common_Groups Class.
	 */
	class LearnDash_PowerPack_Unenroll_The_User_From_All_Common_Groups {
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
				add_filter(
					'learndash_user_course_access_expired',
					[ $this, 'learndash_user_course_access_expired_func' ],
					20,
					2
				);
			}
		}

		/**
		 * Set expired access to courses.
		 *
		 * @param int $user_id The ID of the user.
		 * @param int $course_id The ID of the course.
		 */
		public function learndash_user_course_access_expired_func( $user_id = 0, $course_id = 0 ) {
			$user_id   = absint( $user_id );
			$course_id = absint( $course_id );
			if ( ( ! empty( $user_id ) ) && ( ! empty( $course_id ) ) ) {
				// Get all the Groups the User is enrolled in.
				$user_group_ids = learndash_get_users_group_ids( $user_id );
				$user_group_ids = array_map( 'absint', $user_group_ids );

				// Get all the Groups the Course is enrolled in.
				$course_group_ids = learndash_get_course_groups( $course_id );
				$course_group_ids = array_map( 'absint', $course_group_ids );

				if ( ( ! empty( $user_group_ids ) ) && ( ! empty( $course_group_ids ) ) ) {
					// Get the common Groups that both the User and Course are enrolld in.
					$common_group_ids = array_intersect( $user_group_ids, $course_group_ids );
					if ( ! empty( $common_group_ids ) ) {
						foreach ( $common_group_ids as $group_id ) {
							// For each common Group...

							// First check if the Group checkbox "Enable automatic group enrollment when a user enrolls into any associated group course" is set.
							$group_auto_enroll_all_courses = get_post_meta( $group_id, 'ld_auto_enroll_group_courses', true );
							if ( 'yes' === $group_auto_enroll_all_courses ) {
								// For each comment Group unenroll the User.
								ld_update_group_access( $user_id, $group_id, true );
							} else {
								// Else check if the Course is one of the auto-enroll courses.
								$group_auto_enroll_courses_ids = get_post_meta( $group_id, 'ld_auto_enroll_group_course_ids', true );
								$group_auto_enroll_courses_ids = array_map( 'absint', $group_auto_enroll_courses_ids );

								if ( ( ! empty( $group_auto_enroll_courses_ids ) ) && ( in_array( $course_id, $group_auto_enroll_courses_ids, true ) ) ) {
									// For each comment Group unenroll the User.
									ld_update_group_access( $user_id, $group_id, true );
								}
							}
						}
					}
				}
			}
		}

		/**
		 * Add the class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'group', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Unenroll the User', 'learndash-powerpack' );
			$class_description = esc_html__( 'Unenroll the User from All common Groups when the Course access is expired.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Unenroll_The_User_From_All_Common_Groups();
}

