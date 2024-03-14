<?php
/**
 * User Switching Support.
 *
 * @package User Activity Log
 */

if ( ! function_exists( 'ual_switch_to_user' ) ) {
	/**
	 * Fires when a user switches to another user account.
	 *
	 * @param int $user_id User ID.
	 * @param int $old_user_id Old User ID.
	 */
	function ual_switch_to_user( $user_id, $old_user_id ) {
		$user_info     = get_userdata( $user_id );
		$user_name     = isset( $user_info->display_name ) ? $user_info->display_name : '';
		$old_user_info = get_userdata( $old_user_id );
		$old_user_name = isset( $old_user_info->display_name ) ? $old_user_info->display_name : '';
		$obj_type      = 'User Switching';
		$action        = 'Switched to';
		$post_id       = '';
		$post_title    = ucfirst( $old_user_name ) . ' Switched to ' . ucfirst( $user_name );
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'switch_to_user', 'ual_switch_to_user', 15, 2 );

if ( ! function_exists( 'ual_switch_back_user' ) ) {
	/**
	 * Fires when a user switches back to their originating account.
	 *
	 * @param int $user_id User ID.
	 * @param int $old_user_id Old User ID.
	 */
	function ual_switch_back_user( $user_id, $old_user_id ) {
		$user_info     = get_userdata( $user_id );
		$user_name     = isset( $user_info->display_name ) ? $user_info->display_name : '';
		$old_user_info = get_userdata( $old_user_id );
		$old_user_name = isset( $old_user_info->display_name ) ? $old_user_info->display_name : '';
		$obj_type      = 'User Switching';
		$action        = 'Switched back to';
		$post_id       = '';
		$post_title    = ucfirst( $old_user_name ) . ' Switched back to ' . ucfirst( $user_name );
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'switch_back_user', 'ual_switch_back_user', 15, 2 );

if ( ! function_exists( 'ual_switch_off_user' ) ) {
	/**
	 * Fires when a user switches off.
	 *
	 * @param int $old_user_id Old User ID.
	 */
	function ual_switch_off_user( $old_user_id ) {
		$old_user_info = get_userdata( $old_user_id );
		$old_user_name = isset( $old_user_info->display_name ) ? $old_user_info->display_name : '';
		$obj_type      = 'User Switching';
		$action        = 'Switched off user';
		$post_id       = '';
		$post_title    = ucfirst( $old_user_name ) . ' Switched off user';
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'switch_off_user', 'ual_switch_off_user', 15, 1 );
