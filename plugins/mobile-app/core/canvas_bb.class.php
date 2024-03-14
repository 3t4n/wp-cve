<?php

if ( ! defined( 'CANVAS_DIR' ) ) {
	die();
}

class CanvasBb {

	/**
	 * The init function for Canvas bbPress, add the needed actions
	 */
	public static function init() {
		add_action( 'bbp_post_notify_subscribers', array( 'CanvasBb', 'bb_notification_after_save' ), 10, 3 );
	}

	/**
	 * Sent notifications using push notifications
	 *
	 * @param int   $reply_id the reply ID.
	 * @param int   $topic_id the topic ID.
	 * @param array $user_ids the list of users will be sent the notifications (excluding the user who made the comment/reply).
	 */
	public static function bb_notification_after_save( $reply_id, $topic_id, $user_ids ) {

		$is_reply = bbp_is_reply($reply_id) ? 1 : 0;

		if ( ($is_reply && Canvas::get_option( 'bb_reply' )) || ( !$is_reply  && Canvas::get_option( 'bb_comment' )) ) {

			// Strip tags from text and setup mail data.
			$reply_author_name = bbp_get_reply_author_display_name( $reply_id );
			$topic_title       = wp_specialchars_decode( wp_strip_all_tags( bbp_get_topic_title( $topic_id ) ), ENT_QUOTES );
			$reply_author_name = wp_specialchars_decode( wp_strip_all_tags( $reply_author_name ), ENT_QUOTES );
			$reply_content     = wp_specialchars_decode( wp_strip_all_tags( bbp_get_reply_content( $reply_id ) ), ENT_QUOTES );
			$reply_url         = bbp_get_reply_url( $reply_id );

			$push_api = self::get_push_api();

			$push_api->save_bb_log(
				'notification',
				array(
					'title'   => $topic_title,
					'author'  => $reply_author_name,
					'content' => $reply_content,
					'url'     => $reply_url,
				)
			);

			/**
			 * Allow to customize title of push notification.
			 *
			 * @param string $topic_title Title.
			 *
			 * @since 3.4.2
			 */
			$title = apply_filters( 'canvas_push_bb_notification_title', $topic_title );

			/**
			 * Allow to customize text of push notification.
			 *
			 * @param string $reply_content Text.
			 *
			 * @since 3.4.2
			 */
			$text = apply_filters( 'canvas_push_bb_notification_msg', $reply_content );

			/**
			 * Allow to customize url of push notification.
			 *
			 * @param string $reply_url URL.
			 *
			 * @since 3.4.2
			 */
			$url = apply_filters( 'canvas_push_bb_notification_url', $reply_url );
			$push_api->send_to_users( $title, $text, $user_ids, $url );
		}
	}

	/**
	 * Return CanvasNotifications instance
	 *
	 * @return CanvasNotifications
	 */
	public static function get_push_api() {
		if ( ! class_exists( 'CanvasNotifications' ) ) {
			require_once dirname( __FILE__ ) . '/push/canvas-notifications.class.php';
		}

		return CanvasNotifications::get();
	}
}

if ( Canvas::get_option( 'bb_comment' ) || Canvas::get_option( 'bb_reply' ) ) {
	add_action( 'bbp_init', array( 'CanvasBb', 'init' ) );
}