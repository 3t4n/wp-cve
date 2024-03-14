<?php
/**
 * BuddyPress Support.
 *
 * @package User Activity Log
 */

/*
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! function_exists( 'ual_buddy_press_create_group' ) ) {
	/**
	 * Create a group.
	 *
	 * @param int    $group_id Group ID.
	 * @param int    $member Member.
	 * @param object $group Group.
	 */
	function ual_buddy_press_create_group( $group_id, $member, $group ) {
		$action     = 'Group created';
		$obj_type   = 'BuddyPress';
		$post_id    = $group->id;
		$post_title = 'Group created ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );

	}
}
add_action( 'groups_create_group', 'ual_buddy_press_create_group', 15, 3 );

if ( ! function_exists( 'ual_buddy_press_update_group' ) ) {
	/**
	 * Update a group.
	 *
	 * @param int    $group_id Group ID.
	 * @param object $group Group.
	 */
	function ual_buddy_press_update_group( $group_id, $group ) {
		$action     = 'Group Update';
		$obj_type   = 'BuddyPress';
		$post_id    = $group->id;
		$post_title = 'Group Update ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );

	}
}
add_action( 'groups_update_group', 'ual_buddy_press_update_group', 15, 2 );

if ( ! function_exists( 'ual_buddy_press_delete_group' ) ) {
	/**
	 * Delete a group.
	 *
	 * @param int $group_id Group ID.
	 */
	function ual_buddy_press_delete_group( $group_id ) {
		$action   = 'Group Delete';
		$obj_type = 'BuddyPress';
		$post_id  = $group_id;
		if ( is_numeric( $group_id ) ) {
			$group = groups_get_group(
				array(
					'group_id' => $group_id,
				)
			);
		}
		$post_title = 'Group Delete ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}


add_action( 'groups_before_delete_group', 'ual_buddy_press_delete_group', 15, 1 );

if ( ! function_exists( 'ual_buddy_press_leave_group' ) ) {
	/**
	 * Leave a group for user.
	 *
	 * @param int $group_id Group ID.
	 * @param int $user_id User ID.
	 */
	function ual_buddy_press_leave_group( $group_id, $user_id ) {
		$action   = 'Leave Group';
		$obj_type = 'BuddyPress';
		$post_id  = $group_id;
		$user     = get_user_by( 'id', $user_id );
		if ( is_numeric( $group_id ) ) {
			$group = groups_get_group(
				array(
					'group_id' => $group_id,
				)
			);
		}
		$post_title = $user->display_name . ' Leave Group ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'groups_leave_group', 'ual_buddy_press_leave_group', 15, 2 );

if ( ! function_exists( 'ual_buddy_press_join_group' ) ) {
	/**
	 * Join a group.
	 *
	 * @param int $group_id Group ID.
	 * @param int $user_id User ID.
	 */
	function ual_buddy_press_join_group( $group_id, $user_id ) {
		$action   = 'Join Group';
		$obj_type = 'BuddyPress';
		$post_id  = $group_id;
		$user     = get_user_by( 'id', $user_id );
		if ( is_numeric( $group_id ) ) {
			$group = groups_get_group(
				array(
					'group_id' => $group_id,
				)
			);
		}
		$post_title = $user->display_name . ' Join Group ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'groups_join_group', 'ual_buddy_press_join_group', 15, 2 );

if ( ! function_exists( 'ual_buddy_press_promote_group' ) ) {
	/**
	 * Promote a group
	 *
	 * @param int    $group_id Group ID.
	 * @param int    $user_id User ID.
	 * @param string $status Status.
	 */
	function ual_buddy_press_promote_group( $group_id, $user_id, $status ) {
		$action   = 'promoted Group';
		$obj_type = 'BuddyPress';
		$post_id  = $group_id;
		$user     = get_user_by( 'id', $user_id );
		if ( is_numeric( $group_id ) ) {
			$group = groups_get_group(
				array(
					'group_id' => $group_id,
				)
			);
		}
		$post_title = $user->display_name . ' Promoted Group ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'groups_promote_member', 'ual_buddy_press_promote_group', 15, 3 );

if ( ! function_exists( 'ual_buddy_press_demote_group' ) ) {
	/**
	 * Demote a group
	 *
	 * @param int $group_id Group ID.
	 * @param int $user_id User ID.
	 */
	function ual_buddy_press_demote_group( $group_id, $user_id ) {
		$action   = 'demoted Group';
		$obj_type = 'BuddyPress';
		$post_id  = $group_id;
		$user     = get_user_by( 'id', $user_id );
		if ( is_numeric( $group_id ) ) {
			$group = groups_get_group(
				array(
					'group_id' => $group_id,
				)
			);
		}
		$post_title = $user->display_name . ' Demoted Group ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}

add_action( 'groups_demote_member', 'ual_buddy_press_demote_group', 15, 2 );

if ( ! function_exists( 'ual_buddy_press_ban_group' ) ) {
	/**
	 * Ban a group
	 *
	 * @param int $group_id Group ID.
	 * @param int $user_id User ID.
	 */
	function ual_buddy_press_ban_group( $group_id, $user_id ) {
		$action   = 'Ban Member';
		$obj_type = 'BuddyPress';
		$post_id  = $group_id;
		$user     = get_user_by( 'id', $user_id );
		if ( is_numeric( $group_id ) ) {
			$group = groups_get_group(
				array(
					'group_id' => $group_id,
				)
			);
		}
		$post_title = $user->display_name . ' Ban Member ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'groups_ban_member', 'ual_buddy_press_ban_group', 15, 2 );


if ( ! function_exists( 'ual_buddy_press_un_ban_group' ) ) {
	/**
	 * Unban a group
	 *
	 * @param int $group_id Group ID.
	 * @param int $user_id User ID.
	 */
	function ual_buddy_press_un_ban_group( $group_id, $user_id ) {
		$action   = 'unban member';
		$obj_type = 'BuddyPress';
		$post_id  = $group_id;
		$user     = get_user_by( 'id', $user_id );
		if ( is_numeric( $group_id ) ) {
			$group = groups_get_group(
				array(
					'group_id' => $group_id,
				)
			);
		}
		$post_title = $user->display_name . ' Unban Member ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'groups_unban_member', 'ual_buddy_press_un_ban_group', 15, 2 );


if ( ! function_exists( 'ual_buddy_press_remove_member_group' ) ) {
	/**
	 * Remove member a group
	 *
	 * @param int $group_id Group ID.
	 * @param int $user_id User ID.
	 */
	function ual_buddy_press_remove_member_group( $group_id, $user_id ) {
		$action   = 'Remove Member';
		$obj_type = 'BuddyPress';
		$post_id  = $group_id;
		$user     = get_user_by( 'id', $user_id );
		if ( is_numeric( $group_id ) ) {
			$group = groups_get_group(
				array(
					'group_id' => $group_id,
				)
			);
		}
		$post_title = $user->display_name . ' Remove Member ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'groups_remove_member', 'ual_buddy_press_remove_member_group', 15, 2 );

if ( ! function_exists( 'ual_buddy_press_profile_field_save' ) ) {
	/**
	 * Profile field save
	 *
	 * @param object $field Field.
	 */
	function ual_buddy_press_profile_field_save( $field ) {
		$action     = isset( $field->id ) ? 'Updated' : 'Created';
		$obj_type   = 'BuddyPress';
		$post_id    = '';
		$post_title = $action . ' profile field group ' . $field->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'xprofile_field_after_save', 'ual_buddy_press_profile_field_save', 15, 1 );

if ( ! function_exists( 'ual_buddy_press_profile_field_delete' ) ) {
	/**
	 * Profile field delete
	 *
	 * @param object $field Field.
	 */
	function ual_buddy_press_profile_field_delete( $field ) {
		$action     = 'Deleted';
		$obj_type   = 'BuddyPress';
		$post_id    = '';
		$post_title = $action . ' profile field group ' . $field->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'xprofile_fields_deleted_field', 'ual_buddy_press_profile_field_delete', 15, 1 );

if ( ! function_exists( 'ual_buddy_press_profile_group_save' ) ) {
	/**
	 *  Create/Update Profile Field Group
	 *
	 * @param object $group Group.
	 */
	function ual_buddy_press_profile_group_save( $group ) {
		global $wpdb;
		$action     = ( $group->id === $wpdb->insert_id ) ? 'created' : 'updated';
		$obj_type   = 'BuddyPress';
		$post_id    = '';
		$post_title = $action . ' profile field group ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'xprofile_group_after_save', 'ual_buddy_press_profile_group_save', 15, 1 );

if ( ! function_exists( 'ual_buddy_press_profile_group_delete' ) ) {
	/**
	 * Deleted Profile Field Group
	 *
	 * @param object $group Group.
	 */
	function ual_buddy_press_profile_group_delete( $group ) {
		$action     = 'Deleted';
		$obj_type   = 'BuddyPress';
		$post_id    = '';
		$post_title = $action . ' profile field group ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'xprofile_groups_deleted_group', 'ual_buddy_press_profile_group_delete', 15, 1 );
