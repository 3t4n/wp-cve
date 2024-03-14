<?php
/**
 *  WP Crontrol Support.
 *
 * @package User Activity Log
 */

/*
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Logs cron event management from the WP Crontrol plugin
 * Plugin URL: https://wordpress.org/plugins/wp-crontrol/
 *
 * Requires WP Crontrol 1.9.0 or later.
 */

if ( ! function_exists( 'ualp_added_new_event' ) ) {
	/**
	 * Added new cron event.
	 *
	 * @param string $event Event.
	 */
	function ualp_added_new_event( $event ) {
		$obj_type    = $event->hook;
		$action      = $event->hook;
		$post_id     = '';
		$post_title  = '';
		$hook        = 'added_new_event';
		$description = 'Added cron event ' . $event->hook;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'crontrol/added_new_event', 'ualp_added_new_event' );
add_action( 'crontrol/added_new_php_event', 'ualp_added_new_event' );

if ( ! function_exists( 'ualp_ran_event' ) ) {
	/**
	 * Manually ran cron event.
	 *
	 * @param string $event Event.
	 */
	function ualp_ran_event( $event ) {
		$obj_type    = $event->hook;
		$action      = $event->hook;
		$post_id     = '';
		$post_title  = '';
		$hook        = 'ran_event';
		$description = 'Manually ran cron event ' . $event->hook;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'crontrol/ran_event', 'ualp_ran_event' );

if ( ! function_exists( 'ualp_deleted_event' ) ) {
	/**
	 * Deleted Cron event.
	 *
	 * @param string $event Event.
	 */
	function ualp_deleted_event( $event ) {
		$obj_type   = $event->hook;
		$action     = $event->hook;
		$post_id    = '';
		$post_title = 'Deleted cron event ' . $event->hook;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'crontrol/deleted_event', 'ualp_deleted_event' );

if ( ! function_exists( 'ualp_deleted_all_with_hook' ) ) {
	/**
	 * Deleted all cron hook.
	 *
	 * @param string $hook Hook.
	 * @param string $deleted Deleted.
	 */
	function ualp_deleted_all_with_hook( $hook, $deleted ) {
		$obj_type   = '';
		$action     = 'deleted_all_with_hook';
		$post_id    = '';
		$post_title = 'Deleted all ' . $hook . ' cron events';
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'crontrol/deleted_all_with_hook', 'ualp_deleted_all_with_hook', 10, 2 );

if ( ! function_exists( 'ualp_edited_event' ) ) {
	/**
	 * Edit cron event.
	 *
	 * @param string $event Event.
	 * @param string $original Original.
	 */
	function ualp_edited_event( $event, $original ) {
		$obj_type = $event->hook;
		$action   = $event->hook;
		$post_id  = '';

		$post_title = 'Edited cron event ' . $hook;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'crontrol/edited_event', 'ualp_edited_event', 10, 2 );
add_action( 'crontrol/edited_php_event', 'ualp_edited_event', 10, 2 );

if ( ! function_exists( 'ualp_added_new_schedule' ) ) {
	/**
	 * Added cron schedule.
	 *
	 * @param string $name Name.
	 * @param string $interval Interval.
	 * @param string $display DIsplay.
	 */
	function ualp_added_new_schedule( $name, $interval, $display ) {
		$obj_type = $name;
		$action   = $display;
		$post_id  = '';

		$description = 'Added cron schedule ' . $name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'crontrol/added_new_schedule', 'ualp_added_new_schedule', 10, 3 );

if ( ! function_exists( 'ualp_deleted_schedule' ) ) {
	/**
	 * Delete cron Schedule.
	 *
	 * @param string $name Name.
	 */
	function ualp_deleted_schedule( $name ) {
		$obj_type = $name;
		$action   = $name;
		$post_id  = '';

		$post_title = 'Deleted cron schedule ' . $name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'crontrol/deleted_schedule', 'ualp_deleted_schedule' );
