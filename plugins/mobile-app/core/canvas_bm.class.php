<?php

if ( ! defined( 'CANVAS_DIR' ) ) {
	die();
}

class CanvasBm {
	public static function init() {
		add_action( 'better_messages_message_sent', array( 'CanvasBm', 'send_push_notification' ) );
	}

	public static function send_push_notification( $message ) {
		$participants = Better_Messages()->functions->get_participants( $message->thread_id );
		$participants = $participants['recipients'] ?? array();
		$participants = array_values( $participants );
		$push_api     = self::get_push_api();
		$sender       = get_user_by( 'id', $message->sender_id );

		if ( ! $sender instanceof \WP_User ) {
			return;
		}

		$push_api->send_to_users(
			sprintf(
				esc_html__( 'New chat notification from %s' ),
				esc_html( $sender->display_name )
			),
			$message->message,
			$participants
		);
	}

	public static function get_push_api() {
		if ( ! class_exists( 'CanvasNotifications' ) ) {
			require_once dirname( __FILE__ ) . '/push/canvas-notifications.class.php';
		}
		return CanvasNotifications::get();
	}
}

add_action( 'plugins_loaded', array( 'CanvasBm', 'init' ), 10, 21 );
