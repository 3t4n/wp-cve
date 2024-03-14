<?php

if ( ! defined( 'CANVAS_DIR' ) ) {
	die();
}

class CanvasBp {


	public static function init() {
		add_action( 'bp_notification_after_save', array( 'CanvasBp', 'bp_notification_after_save' ) );
		if ( self::option_on( 'bp_private_messages' ) ) {
			add_action( 'messages_message_after_save', array( 'CanvasBp', 'messages_message_after_save' ) );
		}
		if ( self::option_on( 'bp_global_messages' ) ) {
			add_action( 'messages_notice_after_save', array( 'CanvasBp', 'messages_notice_after_save' ) );
		}
	}

	/**
	 * Sent notifications using push notifications
	 *
	 * @param \BP_Notifications_Notification $notification
	 */
	public static function bp_notification_after_save( $notification ) {
		if ( ( 'messages' == $notification->component_name ) && ( 'new_message' == $notification->component_action ) ) {
			return; // handler at other place
		}
		if ( ( 'friends' == $notification->component_name ) ) {
			if ( ! self::option_on( 'bp_friends' ) ) {
				return;
			}
		} elseif ( ! self::option_on( 'bp_other_notitications' ) ) {
			return;
		}

		// Skip new social articles by default.
		$skip = ( 'social_articles' == $notification->component_name ) && ( 0 === strpos( $notification->component_action, 'new_article' ) );

		/**
		* Allow to skip any unwanted notification.
		*
		* @since 3.2
		*
		* @param bool $skip Skip notification if true.
		* @param string $name Component name.
		* @param string $action Component action.
		* @param BP_Notifications_Notification $notification Notification instance.
		*/
		if ( apply_filters( 'canvas_push_bp_notification_skip', $skip, $notification->component_name, $notification->component_action, $notification ) ) {
			return; // skip unwanted notifications.
		}

		$push_api = self::get_push_api();

		$push_api->save_bp_log( 'notification', $notification );

		$title = self::notification_title( $notification );
		$text  = self::notification_description( $notification );
		$url   = self::extract_url( $text );

		self::normalize_title_and_text( $title, $text );
		if ( ( 'activity' == $notification->component_name ) && ( 'new_at_mention' == $notification->component_action ) ) {
			// link to the mention
			$url = trailingslashit( bp_core_get_userlink( $notification->user_id, false, true ) ) . 'activity/mentions/#acomment-' . $notification->item_id;
		} elseif ( ( 'friends' == $notification->component_name ) && ( 'friendship_request' == $notification->component_action ) ) {
			// link to friendship messages at the receiver's account. It will show accept link.
			$url = trailingslashit( bp_core_get_userlink( $notification->user_id, false, true ) ) . bp_get_friends_slug() . '/requests/?new';
		} elseif ( ( 'buddyboss_wall_like_notifier' == $notification->component_name ) && ( 0 === strpos( $notification->component_action, 'new_wall_post_like_' ) ) ) {
			// make it as generic as possible
			$title = 'New Wall Post Like';
			$text  = 'Someone liked one of your posts';
		}

		$users_list = array( $notification->user_id );

		/**
		* Allow to customize title of push notification.
		*
		* @since 3.2
		*
		* @param string $title Title.
		* @param int[] $users_list Array with single user ID.
		* @param string $url URL.
		* @param string $text Text.
		* @param BP_Notifications_Notification $notification Notification instance.
		*/
		$title = apply_filters( 'canvas_push_bp_notification_title', $title, $users_list, $url, $text, $notification );

		/**
		* Allow to customize text of push notification.
		*
		* @since 3.2
		*
		* @param string $text Text.
		* @param int[] $users_list Array with single user ID.
		* @param string $url URL.
		* @param string $title Title.
		* @param BP_Notifications_Notification $notification Notification instance.
		*/
		$text = apply_filters( 'canvas_push_bp_notification_msg', $text, $users_list, $url, $title, $notification );

		/**
		* Allow to customize url of push notification.
		*
		* @since 3.2
		*
		* @param string $url URL.
		* @param int[] $users_list Array with single user ID.
		* @param string $title Title.
		* @param string $text Text.
		* @param BP_Notifications_Notification $notification Notification instance.
		*/
		$url = apply_filters( 'canvas_push_bp_notification_url', $url, $users_list, $title, $text, $notification );

		$push_api->send_to_users( $title, $text, $users_list, $url );
	}

	/**
	 * Sent messages using push notifications
	 *
	 * @param \BP_Messages_Message $message
	 */
	public static function messages_message_after_save( $message ) {
		$push_api = self::get_push_api();
		$push_api->save_bp_log( 'message', $message );

		$title = $message->subject;
		$text  = wp_trim_words( $message->message, 20, '...' );

		$sender_name = bp_core_get_user_displayname( $message->sender_id );
		$url         = trailingslashit( get_site_url() ) . 'canvas-api/bp/' . bp_get_messages_slug() . '/view/' . $message->thread_id . '/';

		$users_list = $receiver_name = array();
		foreach ( $message->recipients as $user ) {
			$users_list[]    = $user->user_id;
			$receiver_name[] = bp_core_get_user_displayname( $user->user_id );
		}
		$receiver_name = implode( ', ', $receiver_name );

		// Use predefined or translated string
		$default_title = "New message from $sender_name";

		$title = self::get_bp_string(
			Canvas::get_option( 'bp_private_messages_title' ),
			array( 'messages-unread', 'post_title' ),
			array(
				'[{{{site.name}}}]' => '',
				'{{sender.name}}'   => $sender_name,
				'%sender%'          => $sender_name,
				'%receiver%'        => $receiver_name,
			),
			$default_title
		);

		self::normalize_title_and_text( $title, $text );

		/**
		* Allow to customize title of push message.
		*
		* @since 3.2
		*
		* @param string $title Title.
		* @param int[] $users_list Array with single or many user ID.
		* @param string $url URL.
		* @param string $text Text.
		* @param bp_private_messages_Message $message Message instance.
		*/
		$title = apply_filters( 'canvas_push_bp_message_title', $title, $users_list, $url, $text, $message );

		/**
		* Allow to customize text of push message.
		*
		* @since 3.2
		*
		* @param string $text Text.
		* @param int[] $users_list Array with single or many user ID.
		* @param string $url URL.
		* @param string $title Title.
		* @param bp_private_messages_Message $message Message instance.
		*/
		$text = apply_filters( 'canvas_push_bp_message_msg', $text, $users_list, $url, $title, $message );

		/**
		* Allow to customize url of push message.
		*
		* @since 3.2
		*
		* @param string $url URL.
		* @param int[] $users_list Array with single or many user ID.
		* @param string $title Title.
		* @param string $text Text.
		* @param bp_private_messages_Message $message Message instance.
		*/
		$url = apply_filters( 'canvas_push_bp_message_url', $url, $users_list, $title, $text, $message );

		$push_api->send_to_users( $title, $text, $users_list, $url );
	}

	/**
	 * Sent message notices using push notifications.
	 * For all available users.
	 *
	 * @param \BP_Messages_Notice $message
	 */
	public static function messages_notice_after_save( $message ) {
		$push_api = self::get_push_api();
		$push_api->save_bp_log( 'notice', $message );

		$title = $message->subject;
		$text  = $message->message;
		$url   = self::extract_url( $text );

		self::normalize_title_and_text( $title, $text );
		$title = 'BP Notice: ' . $title;

		/**
		* Allow to customize title of push notice.
		*
		* @since 3.2
		*
		* @param string $title Title.
		* @param string $url URL.
		* @param string $text Text.
		* @param bp_private_messages_Notice $message Notice instance.
		*/
		$title = apply_filters( 'canvas_push_bp_notice_title', $title, $url, $text, $message );

		/**
		* Allow to customize title of push notice.
		*
		* @since 3.2
		*
		* @param string $text Text.
		* @param string $url URL.
		* @param string $title Title.
		* @param bp_private_messages_Notice $message Notice instance.
		*/
		$text = apply_filters( 'canvas_push_bp_notice_msg', $text, $url, $title, $message );

		/**
		* Allow to customize title of push notice.
		*
		* @since 3.2
		*
		* @param string $url URL.
		* @param string $title Title.
		* @param string $text Text.
		* @param bp_private_messages_Notice $message Notice instance.
		*/
		$url = apply_filters( 'canvas_push_bp_notice_url', $url, $title, $text, $message );

		$push_api->send_to_users( $title, $text, true, $url );
	}

	/**
	 * Get full-text description for a notification.
	 *
	 * @param BP_Notifications_Notification $notification
	 * @return string
	 */
	private static function notification_description( $notification ) {
		// use the same way as native function bp_get_the_notification_description()
		$bp          = buddypress();
		$description = '';

		// Callback function exists.
		if ( isset( $bp->{ $notification->component_name }->notification_callback ) && is_callable( $bp->{ $notification->component_name }->notification_callback ) ) {
			$description = call_user_func( $bp->{ $notification->component_name }->notification_callback, $notification->component_action, $notification->item_id, $notification->secondary_item_id, 1, 'string', $notification->id );

			// @deprecated format_notification_function - 1.5
		} elseif ( isset( $bp->{ $notification->component_name }->format_notification_function ) && function_exists( $bp->{ $notification->component_name }->format_notification_function ) ) {
			$description = call_user_func( $bp->{ $notification->component_name }->format_notification_function, $notification->component_action, $notification->item_id, $notification->secondary_item_id, 1 );

			// Allow non BuddyPress components to hook in.
		} else {
			if ( is_plugin_active( 'buddyboss-platform/bp-loader.php' ) ) {
				$description = apply_filters_ref_array( 'bp_notifications_get_notifications_for_user', array( $notification->component_action, $notification->item_id, $notification->secondary_item_id, 1, 'string', $notification->component_action, $notification->component_name, $notification->id, 'web' ) );
			} else if ( is_plugin_active( 'buddypress/bp-loader.php' ) ) {
				$description = apply_filters_ref_array( 'bp_notifications_get_notifications_for_user', array( $notification->component_action, $notification->item_id, $notification->secondary_item_id, 1, 'string', $notification->component_action, $notification->component_name, $notification->id ) );
			}
		}

		return apply_filters( 'bp_get_the_notification_description', $description, $notification );
	}

	private static function extract_url( &$message ) {
		$url = '';
		if ( preg_match( '!^<a href="(.*?)"[^>]*?>([^<]*?)</a>$!', $message, $m ) ) {
			$url     = $m[1];
			$message = strip_tags( $m[2] );
		}
		return $url;
	}

	/**
	 * Get title for a notification.
	 *
	 * @param BP_Notifications_Notification $notification
	 * @return string
	 */
	private static function notification_title( $notification ) {
		$actions = explode( '_', $notification->component_action );
		foreach ( $actions as $key => $val ) {
			$actions[ $key ] = ucfirst( $val );
		}
		return implode( ' ', $actions );
	}

	private static function option_on( $name ) {
		return Canvas::get_option( $name );
	}

	/**
	 * Use user's template or get template string from BP and substitute values
	 *
	 * @param string $title
	 * @param array  $names
	 * @param array  $values
	 * @param string $default
	 */
	static function get_bp_string( $title, $names, $values = array(), $default = '' ) {
		$result = array();
		if ( '' == $title ) {
			if ( class_exists( 'BuddyPress' ) ) {
				BuddyPress::instance(); // initialize it
			}
			if ( function_exists( 'bp_email_get_schema' ) ) {
				$result = bp_email_get_schema();
				if ( ! is_array( $names ) ) {
					$names = array( $names );
				}
				foreach ( $names as $name ) {
					if ( isset( $result[ $name ] ) ) {
						$result = $result[ $name ];
						break;
					} else {
						$result = $default;
					}
				}
			}
		} else {
			$result = $title;
		}
		if ( empty( $result ) || ! is_string( $result ) ) {
			$result = $default;
		}
		// substitute values
		return trim( str_replace( array_keys( $values ), array_values( $values ), $result ) );
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

	/**
	 * Decode html entity codes
	 *
	 * @param string $title
	 * @param string $text
	 */
	private static function normalize_title_and_text( &$title, &$text ) {
		$title = html_entity_decode( $title, ENT_QUOTES | ENT_HTML401 );
		$text  = html_entity_decode( $text, ENT_QUOTES | ENT_HTML401 );
	}
}

if ( Canvas::get_option( 'bp_private_messages' ) || Canvas::get_option( 'bp_global_messages' ) || Canvas::get_option( 'bp_friends' ) || Canvas::get_option( 'bp_other_notitications' ) ) {
	add_action( 'bp_init', array( 'CanvasBp', 'init' ) );
}
