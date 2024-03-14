<?php
/**
 * LearnPress bbPress Functions
 *
 * Define common functions for both front-end and back-end
 *
 * @author   ThimPress
 * @package  LearnPress/bbPress/Functions
 * @version  3.0.4
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'learn_press_bbp_get_course' ) ) {
	/**
	 * Get course of forum.
	 *
	 * @param $forum_id
	 *
	 * @return bool| int
	 */
	function learn_press_bbp_get_course( $forum_id ) {

		global $wpdb;

		if ( $forum_id ) {
			$query = $wpdb->prepare( "
				SELECT course.ID FROM {$wpdb->posts} course
					INNER JOIN {$wpdb->postmeta} course_meta ON course_meta.post_id = course.ID AND course_meta.meta_key = %s AND course_meta.meta_value = %d
					INNER JOIN {$wpdb->posts} forum ON forum.ID = course_meta.meta_value
					WHERE forum.ID = %d",
				'_lp_course_forum', $forum_id, $forum_id );
			return $course_id = $wpdb->get_var( $query );
		}

		return false;
	}
}

/*function learn_press_bbp_get_user_role($role, $user_id, $user){
	return 'lp_teacher';
}

add_filter('bbp_get_user_role', 'learn_press_bbp_get_user_role', 10, 3);*/