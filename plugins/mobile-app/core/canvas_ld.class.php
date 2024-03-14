<?php

if ( ! defined( 'CANVAS_DIR' ) ) {
	die();
}

class CanvasLd {


	public static function init() {
		add_action( 'learndash_assignment_approved', array( 'CanvasLd', 'learndash_assignment_approved' ) );
		add_action( 'pre_comment_on_post', array( 'CanvasLd', 'learndash_new_assignment_comment' ) );
	}

	/**
	 * Send approved assignments using push notifications
	 *
	 * @param int $assignment_id The assignment post ID
	 */
	public static function learndash_assignment_approved( $assignment_id ) {

		if ( ! self::option_on( 'ld_approved_assignments' ) ) {
			return;
		}

		$push_api = self::get_push_api();

		$push_api->save_ld_log( 'assignment-approved', $assignment_id );

		$title = 'Your assignment has been approved.';
		$text  = html_entity_decode( get_the_title( $assignment_id ) ) . ' has been approved.';
		$url   = get_post_permalink( $assignment_id );

		$users_list = array( get_post_field( 'post_author', $assignment_id ) );

		/**
		* Allow to customize title of push notification.
		*
		* @since 3.2
		*
		* @param string $title Title.
		* @param int[] $users_list Array with single user ID.
		* @param string $url URL.
		* @param string $text Text.
		*/
		$title = apply_filters( 'canvas_push_ld_assignment_approved_title', $title, $users_list, $url, $text );

		/**
		* Allow to customize text of push notification.
		*
		* @since 3.2
		*
		* @param string $text Text.
		* @param int[] $users_list Array with single user ID.
		* @param string $url URL.
		* @param string $title Title.
		*/
		$text = apply_filters( 'canvas_push_ld_assignment_approved_msg', $text, $users_list, $url, $title );

		/**
		* Allow to customize url of push notification.
		*
		* @since 3.2
		*
		* @param string $url URL.
		* @param int[] $users_list Array with single user ID.
		* @param string $title Title.
		* @param string $text Text.
		*/
		$url = apply_filters( 'canvas_push_ld_assignment_approved_url', $url, $users_list, $title, $text );

		$push_api->send_to_users( $title, $text, $users_list, $url );
	}

	/**
	 * Send new assignment comment using push notifications
	 *
	 * @param int $comment_post_id The comment ID.
	 */
	public static function learndash_new_assignment_comment( $comment_post_id ) {

		if ( get_post_type( $comment_post_id ) !== 'sfwd-assignment' ) {
			return;
		}

		$push_api = self::get_push_api();

		$push_api->save_ld_log( 'new-assignment-comment', $comment_post_id );

		$title = 'Your assignment has a new comment.';
		$text  = html_entity_decode( get_the_title( $comment_post_id ) ) . ' has a new comment.';
		$url   = get_post_permalink( $comment_post_id );

		$users_list = array( get_post_field( 'post_author', $comment_post_id ) );

		/**
		* Allow to customize title of push notification.
		*
		* @since 3.2
		*
		* @param string $title Title.
		* @param int[] $users_list Array with single user ID.
		* @param string $url URL.
		* @param string $text Text.
		*/
		$title = apply_filters( 'canvas_push_ld_comment_title', $title, $users_list, $url, $text );

		/**
		* Allow to customize text of push notification.
		*
		* @since 3.2
		*
		* @param string $text Text.
		* @param int[] $users_list Array with single user ID.
		* @param string $url URL.
		* @param string $title Title.
		*/
		$text = apply_filters( 'canvas_push_ld_comment_msg', $text, $users_list, $url, $title );

		/**
		* Allow to customize url of push notification.
		*
		* @since 3.2
		*
		* @param string $url URL.
		* @param int[] $users_list Array with single user ID.
		* @param string $title Title.
		* @param string $text Text.
		*/
		$url = apply_filters( 'canvas_push_ld_comment_url', $url, $users_list, $title, $text );

		$push_api->send_to_users( $title, $text, $users_list, $url );
	}

	private static function option_on( $name ) {
		return Canvas::get_option( $name );
	}

	/**
	 * Return CanvasNotifications instance
	 *
	 * @return CanvasNotifications
	 */
	static function get_push_api() {
		if ( ! class_exists( 'CanvasNotifications' ) ) {
			require_once dirname( __FILE__ ) . '/push/canvas-notifications.class.php';
		}
		return CanvasNotifications::get();
	}
}

if ( Canvas::get_option( 'ld_approved_assignments' ) || Canvas::get_option( 'ld_new_assignment_comment' ) ) {
	add_action( 'wp_loaded', array( 'CanvasLd', 'init' ) );
}
