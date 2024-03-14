<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Is User allowed to edit posts
 *
 * Args: $user_id
 *
 * Return:
 * - user_nicename or user_login if allowed
 * - false if not allowed
 *
 * @since 1.0.3
 */
function chessgame_shizzle_is_moderator( $user_id ) {

	if ( $user_id > 0 ) {
		if ( user_can( $user_id, 'publish_posts' ) ) {
			// Only moderators
			$userdata = get_userdata( $user_id );
			if (is_object($userdata)) {
				if ( isset( $userdata->display_name ) ) {
					return esc_attr( $userdata->display_name );
				} else {
					return esc_attr( $userdata->user_login );
				}
			}
		}
	}
	return false;

}


/*
 * Get all the users with capability 'moderate_comments'.
 *
 * @return: Array with User objects.
 *
 * @since 1.0.3
 */
function chessgame_shizzle_get_moderators() {

	$role__in = array( 'Administrator', 'Editor', 'Author' );
	$role__in = apply_filters( 'chessgame_shizzle_get_moderators_role__in', $role__in );

	// role__in will only work since WP 4.4.
	$users_query = new WP_User_Query( array(
		'role__in' => $role__in,
		'fields'   => 'all',
		'orderby'  => 'display_name',
		) );
	$users = $users_query->get_results();

	$moderators = array();

	if ( is_array($users) && ! empty($users) ) {
		foreach ( $users as $user_info ) {

			if ($user_info === FALSE) {
				// Invalid $user_id
				continue;
			}

			// No capability
			if ( ! user_can( $user_info, 'publish_posts' ) ) {
				continue;
			}

			$moderators[] = $user_info;
		}
	}

	return $moderators;

}
