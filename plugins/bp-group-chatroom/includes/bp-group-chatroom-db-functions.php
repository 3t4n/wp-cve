<?php
if ( !defined( 'ABSPATH' ) ) exit;

function bp_group_chat_who_is_online() {
	global $bp, $wpdb;
	
	if ( sanitize_text_field( $_POST['bp_group_chat_online_query'] ) == 1 ) {	
		//die if nonce fail
		$chat_group_id = sanitize_text_field( $_POST['bp_group_chat_group_id'] );
		check_ajax_referer( 'bpgl-nonce', 'security' );
		// only do this is member of the group or super admin
		if ( groups_is_user_member( $bp->loggedin_user->id, $chat_group_id )
			 || groups_is_user_mod( $bp->loggedin_user->id, $chat_group_id ) 
			 || groups_is_user_admin( $bp->loggedin_user->id, $chat_group_id )
			 || is_super_admin() ) {
				 
			//delete old user
			$sql = $wpdb->prepare( "DELETE FROM {$wpdb->base_prefix}bp_group_chat_online WHERE ".
								   "group_id=%d AND user_id=%d", 
								   $chat_group_id, $bp->loggedin_user->id  );
			$wpdb->query($sql);
			//delete old messages
			$settings =  groups_get_groupmeta( $chat_group_id, 'bp_group_chat_enabled' );
			if ( !isset( $settings['delete_enabled']  ) || $settings['delete_enabled'] == 1 ) { 
				$delete_time = 2592000;
			} else {
				$delete_time = $settings['delete_enabled'];
			}
			$sql = $wpdb->prepare( "DELETE FROM {$wpdb->base_prefix}bp_group_chat WHERE timestamp < %d", current_time( 'timestamp' ) - $delete_time );
			$wpdb->query($sql);
			//add new
			$sql = $wpdb->prepare( "INSERT INTO {$wpdb->base_prefix}bp_group_chat_online".
								   "( group_id, user_id, timestamp ) ".
								   "VALUES ( %d, %d, %s )", 
								   $chat_group_id, $bp->loggedin_user->id, current_time( 'timestamp' ) );
			$wpdb->query($sql);
			//get users viewing this page
			$rows = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->base_prefix}bp_group_chat_online ".
								   "WHERE group_id=%d", 
								   $chat_group_id ) );
			if( empty( $rows ) ) {
				echo 'nobody online - how are you even viewing this?';
				die;
			}
			// we have results - anyone that has checked in in last 15 seconds is shown as online
			foreach( $rows as $bp_group_chat_user ) {
				if ( time() - $bp_group_chat_user->timestamp < 25 ) {
					echo '<li id="' . $bp_group_chat_user->timestamp . '" class="item-list" style="display: flex; align-content: center;">';
					echo '<div class="user-list-item" >';
					echo '<div class="bp-chat-user-online-avatar">' . bp_core_fetch_avatar( 'item_id='.$bp_group_chat_user->user_id.'&object=user&type=thumb' )  . '</div>';
					echo '<div class="bp-chat-user-online-name">' . '<label>' . ' - ' . bp_core_get_userlink( $bp_group_chat_user->user_id ) . '</label>' . '</div>';
					if ( bp_is_active( 'friends' ) )
						echo bp_add_friend_button( $bp_group_chat_user->user_id );
					echo '</div>';
					echo '</li>';
				}
			}
			die;
		}
	}
}
add_action( 'wp_ajax_bp_chat_heartbeat', 'bp_group_chat_who_is_online' );

function bp_group_chat_new_message() {
	global $bp, $wpdb;
	
	if ( sanitize_text_field( $_POST['bp_group_chat_new_message'] ) == 1 ) {
		$chat_group_id = sanitize_text_field( $_POST['bp_group_chat_group_id'] );
		//die if nonce fail
		check_ajax_referer( 'bpgl-nonce', 'security' );
		// only do this if member of the group or super admin
		if ( groups_is_user_member( $bp->loggedin_user->id, $chat_group_id )
			 || groups_is_user_mod( $bp->loggedin_user->id, $chat_group_id ) 
			 || groups_is_user_admin( $bp->loggedin_user->id, $chat_group_id )
			 || is_super_admin() ) {
				 
			//format message
			$text_content = wp_filter_post_kses( $_POST['bp_group_chat_textbox'] );
			$text_content = nl2br( make_clickable( $text_content ) );
			global $wp_embed;
			//$test_content = $wp_embed->autoembed( $text_content );
			if ( function_exists( 'wp_encode_emoji' ) ) {
				$text_content = wp_encode_emoji( $text_content );
			}
			$text_content = bp_group_chatroom_send_mentions( $text_content, $bp->loggedin_user->id, $chat_group_id );

			// Check if the new message is part of a thread and if not start a new thread.
			$threads = $wpdb->get_results( $wpdb->prepare( "SELECT id, timestamp FROM {$wpdb->base_prefix}bp_group_chat_threads WHERE group_id=%d", $chat_group_id ) );
			if ( $threads ) {
				foreach( $threads as $thread ) {
					if ( $thread->timestamp > current_time( 'timestamp' ) - 1800 ) {
						$thread_id = $thread->id;
					}
				}
			}
			if ( !$threads || ! isset( $thread_id ) ) {
				$new_thread_sql = $wpdb->prepare( "INSERT INTO {$wpdb->base_prefix}bp_group_chat_threads".
									   "( group_id, user_id, timestamp ) ".
									   "VALUES ( %d, %d, %s )", 
									   $chat_group_id, $bp->loggedin_user->id, current_time( 'timestamp' ) );
				$wpdb->query($new_thread_sql);
				$thread_id_array = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM {$wpdb->base_prefix}bp_group_chat_threads WHERE group_id=%d AND user_id = %s", $chat_group_id, $bp->loggedin_user->id ) );
				$thread_id = $thread_id_array[0]->id;
			}
			
			// Add new message
			$sql = $wpdb->prepare( "INSERT INTO {$wpdb->base_prefix}bp_group_chat".
								   "( group_id, user_id, message_content, timestamp, thread_id ) ".
								   "VALUES ( %d, %d, %s, %s, %s )", 
								   $chat_group_id, $bp->loggedin_user->id, $text_content, current_time( 'timestamp' ), $thread_id );
			$wpdb->query($sql);
			die;
		}
	}
}
add_action( 'wp_ajax_bp_chat_new_message', 'bp_group_chat_new_message' );

function bp_group_chat_load_messages() {
	global $bp, $wpdb;
	$current_user_id = get_current_user_id();
	$group_chat_settings = groups_get_groupmeta( bp_get_current_group_id(), 'bp_group_chat_enabled' );

	// set color defaults
	if ( ! isset( $group_chat_settings['your_chat_color'] ) ) {
		$group_chat_settings['your_chat_color'] = 'ffffff';
	}
	if ( ! isset( $group_chat_settings['my_chat_color'] ) ) {
		$group_chat_settings['my_chat_color'] = 'ffffff';
	}

	if ( !isset( $group_chat_settings['group_chat_hide_time'] ) ) { 
		$hide_time = 2592000;
	} else {
		$hide_time = $group_chat_settings['group_chat_hide_time'];
	}
	$request = sanitize_text_field( $_POST['bp_group_chat_load_messages'] );
	if ( $request == 1 || $request == 2 ) {	
		$chat_group_id = sanitize_text_field( $_POST['bp_group_chat_group_id'] );
		//die if nonce fail
		check_ajax_referer( 'bpgl-nonce', 'security' );
		// only do this is member of the group or super admin
		$is_group_admin = groups_is_user_admin( $chat_group_id, $current_user_id );
		$is_site_admin = current_user_can( 'bp_moderate' );
		$is_group_mod = groups_is_user_mod( $chat_group_id, $current_user_id );
		if ( groups_is_user_member( $bp->loggedin_user->id, $chat_group_id ) || $is_group_admin || $is_group_mod || $is_site_admin ) {
			// Last updated = last time the user checked for an update.
			$update = $wpdb->get_results( $wpdb->prepare( "SELECT timestamp from {$wpdb->base_prefix}bp_group_chat_updates WHERE group_id=%d and user_id=%d", $chat_group_id, $current_user_id ) );
			if ( ! $update || $request == 2 ) {
				$last_update = current_time( 'timestamp' ) - $hide_time;
			} else {
				$last_update = $update[0]->timestamp;
			}
			// Set last updated time for the user
			$remove_updates_sql = $wpdb->prepare( "DELETE FROM {$wpdb->base_prefix}bp_group_chat_updates WHERE group_id = %d and user_id = %d", $chat_group_id, $bp->loggedin_user->id );
			$wpdb->query($remove_updates_sql);
			$sql = $wpdb->prepare( "INSERT INTO {$wpdb->base_prefix}bp_group_chat_updates".
								   "( group_id, user_id, timestamp ) ".
								   "VALUES ( %d, %d, %s )", 
								   $chat_group_id, $bp->loggedin_user->id, current_time( 'timestamp' ) );
			$wpdb->query($sql);
			
			//fetch new messages if any
			$rows = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->base_prefix}bp_group_chat WHERE group_id=%d AND timestamp >= %d ORDER BY timestamp ASC", $chat_group_id, $last_update ) );
			$message_rows = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM {$wpdb->base_prefix}bp_group_chat WHERE group_id=%d AND timestamp >= %d", $chat_group_id, current_time( 'timestamp' ) - $hide_time ) );
			
			//load last messages
			if( empty( $message_rows ) ) {
				echo sanitize_text_field( __( '', 'bp-group-chatroom' ) );
				die;
			}
			// we have results - format them and send to the message container.
			foreach( $rows as $bp_group_chat_message ) { // between last update time and latest update time.
				if ( $bp_group_chat_message->timestamp >= $last_update ) {
					$orientation = $bp_group_chat_message->user_id == $current_user_id ? 'right' : 'left';
					$background_color = $orientation == 'left' ? $group_chat_settings['your_chat_color'] : $group_chat_settings['my_chat_color'];
					echo '<div class="chat-bubble  ' . $orientation . '" style="background-color: ' . $background_color . ';" id="bp-group-chatroom-' . $bp_group_chat_message->id . '">
						<div class="bp-group-chatroom-avatar">' . bp_core_fetch_avatar( 'item_id='.$bp_group_chat_message->user_id.'&object=user&type=thumb' ) . '</div>
						<div class="chat-date">[' . date( 'm/d/Y H:i:s', $bp_group_chat_message->timestamp ) . '] </div>
						<div class="chat-name"><strong>' . bp_core_get_user_displayname( $bp_group_chat_message->user_id ) . '</strong>: </div>
						<div class="chat-message">' . stripslashes( $bp_group_chat_message->message_content ) . '</div>';
					if ( $is_site_admin || $is_group_admin || $is_group_mod ) {
						echo '<div class="chatroom-delete" id="bp-group-chatroom-delete-msg" onClick="bpChatroomDeleteMsg(' . $bp_group_chat_message->id . ');return false;">' . sanitize_text_field( __( 'del', 'bp-group-chatroom' ) ) . '</div>';
						if ( isset( $group_chat_settings['activity_enabled'] ) && $group_chat_settings['activity_enabled'] == 1 ) {
							echo '<div class="chatroom-close-thread" id="bp-group-close-thread" onClick="bpChatroomCloseThread();return false;">' . sanitize_text_field( __( 'close thread', 'bp-group-chatroom' ) ) . '</div>';
						}
						echo '</div>';
					} else {
						echo '</div>';
					}
				}
			}
		
			// Check threads for closing
			$threads = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->base_prefix}bp_group_chat_threads WHERE group_id=%d", $chat_group_id ) );
			if ( $threads ) {
				foreach ( $threads as $thread ) {
					if ( $thread->timestamp < current_time( 'timestamp' ) - 1800 ) {
						$remove_thread_sql = $wpdb->prepare( "DELETE FROM {$wpdb->base_prefix}bp_group_chat_threads WHERE id = %d", $thread->id );
						$wpdb->query($remove_thread_sql);
						if ( isset( $group_chat_settings['activity_enabled'] ) && $group_chat_settings['activity_enabled'] == 1 ) {
							bp_group_chatroom_send_thread_to_activity( $chat_group_id, $thread->id, $thread->user_id );
						}

					}
				}
			}
			die();
		}
	}
}
add_action( 'wp_ajax_bp_chat_load_messages', 'bp_group_chat_load_messages' );

function bp_group_chatroom_send_mentions( $content, $user_id, $group_id ) {

	if ( ! bp_is_active( 'activity' ) || ! bp_activity_do_mentions() ) {
		return $content;
	}

	$usernames = bp_activity_find_mentions( $content );
	
	if ( ! empty( $usernames ) ) {
		foreach ( $usernames as $username ) {
			$content = bp_group_chatroom_send_mention_notification( $username, $content, $user_id, $group_id );
		}
	}
	
	return $content;
}

function bp_group_chatroom_send_mention_notification( $username, $content, $user_id, $group_id ) {
	
	// Extract mentioned user_id from name
	$menton_user_id = bp_activity_get_userid_from_mentionname( $username );

	// Create link to the group forum
	$group = groups_get_group( $group_id );
	$group_name = bp_get_group_name( $group );
	$user = get_userdata( $menton_user_id );
	$sender_username = bp_core_get_user_displayname( $user_id );
	$group_chatroom = $group_name . ' ' . sanitize_text_field( __( 'Chatroom', 'bp-group-chatroom' ) );
	$chatroom_url = bp_get_group_permalink( $group ) . BP_GROUP_CHATROOM_SLUG;
	$chatroom_link = '<a href="' . $chatroom_url . '">' . $group_chatroom . '</a>';
	// translators: 1: Name of user who made a mention 2: Title of group
	$has_mentioned_you = printf( esc_html__( '%1$s has mentioned you in a discussion at  %2$s.', 'bp-group-chatroom' ), $sender_username, $group_chatroom );
	
	$mail_content = $has_mentioned_you . ': '. "\r\n" . $content;

	// Send email notification
	wp_mail( $user->user_email, $has_mentioned_you, $mail_content );
	//Return content
	$content = bp_activity_at_name_filter( $content );
	return $content;
	
}
/**
 * Notify a member when their nicename is mentioned in an chatroom stream item.
 *
 * @since 1.2.0
 *
 * @param object $activity           Activity object.
 * @param string $subject (not used) Notification subject.
 * @param string $message (not used) Notification message.
 * @param string $content (not used) Notification content.
 * @param int    $receiver_user_id   ID of user receiving notification.
 */
function bp_group_chatroom_at_mention_add_notification( $activity, $receiver_user_id ) {
	bp_notifications_add_notification( array(
			'user_id'           => $receiver_user_id,
			'item_id'           => $activity->id,
			'secondary_item_id' => $activity->user_id,
			'component_name'    => 'bp-group-chatroom',
			'component_action'  => 'chatroom_mention',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
	) );
}

function bp_group_chatroom_filter_notifications_get_registered_components( $component_names = array() ) {

	// Force $component_names to be an array
	if ( ! is_array( $component_names ) ) {
		$component_names = array();
	}
 
	// Add 'custom' component to registered components array
	array_push( $component_names, 'bp-group-chatroom' );
 
	// Return component's with 'custom' appended
	return $component_names;
	
}

add_filter( 'bp_notifications_get_registered_components', 'bp_group_chatroom_filter_notifications_get_registered_components' );

function bp_group_chatroom_post_format_buddypress_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string', $component_action_name, $component_name ) {

	// Prevent blank notifications
	if ( $component_action_name !== 'chatroom_mention' ) {

		return $component_action_name;

	}

	if ( 'chatroom_mention' === $component_action_name ) {
	
		$activity = new BP_Activity_Activity( $item_id );
		$group_id = $activity->item_id;
		$sender_name = bp_core_get_user_displayname( $activity->user_id );
		$group = groups_get_group( $group_id );
		$group_name = bp_get_group_name( $group );
		$group_chatroom = $group_name . ' ' . sanitize_text_field( __( 'Chatroom', 'bp-group-chatroom' ) );
		$chatroom_url = bp_get_group_permalink( $group ) . BP_GROUP_CHATROOM_SLUG;
		$chatroom_link = '<a href="' . $chatroom_url . '">' . $group_chatroom . '</a>';
		// translators: 1: Name of user who made a mention 2: Title of group
		$has_mentioned_you = sprintf( esc_html__( '%1$s has mentioned you in a discussion at  %2$s', 'bp-group-chatroom' ), $sender_name, $chatroom_link );

		// WordPress Toolbar
		if ( 'string' === $format ) {
			$return = apply_filters( 'bpgc_notification_filter', '<a href="' . $chatroom_url . '" title="' . $group_chatroom . '">' . $has_mentioned_you . '</a>', $group_chatroom,$has_mentioned_you, $sender_name, $group_name, $chatroom_url );
 
		} else {

			$return = apply_filters( 'bpgc_notification_filter', array(
				'text' => $has_mentioned_you,
				'link' => $chatroom_url
			), $chatroom_url, $sender_name, $group_name, $has_mentioned_you );
		}
		
		return $return;
		
	}	

}

add_filter( 'bp_notifications_get_notifications_for_user', 'bp_group_chatroom_post_format_buddypress_notifications', 11, 7 );

function bp_group_chatroom_send_thread_to_activity( $chat_group_id, $thread_id, $user_id ) {
	global $wpdb, $bp;
	
	$thread_messages = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->base_prefix}bp_group_chat WHERE group_id=%d AND thread_id = %s ORDER BY timestamp ASC LIMIT 100", $chat_group_id, $thread_id ) );
	$activity_content = '';
	if ( $thread_messages ) {
		foreach ( $thread_messages as $thread_message ) {
			// Remove @ for those already mentioned
			$usernames = bp_activity_find_mentions( $thread_message->message_content );
			if ( $usernames ) {
				foreach ( $usernames as $username ) {
					$thread_message->message_content = preg_replace( '/(@' . $username . '\b)/', $username, $thread_message->message_content );
				}
			}
			$activity_content .= "<p>[" . date( 'm/d/Y H:i:s', $thread_message->timestamp ) . "] <strong>" .  bp_core_get_user_displayname( $thread_message->user_id ) . "</strong><p> $thread_message->message_content</p></p>";
		}
		// Translators - User has added a chat thread to the group chatroom
		$message = sanitize_text_field( __( ' has added a new thread to ', 'bp-group-chatroom' ) );
		$group = groups_get_group( $chat_group_id );
		$group_name = bp_get_group_name( $group );
		$group_chatroom = $group_name . ' ' . sanitize_text_field( __( 'Chatroom', 'bp-group-chatroom' ) );
		$chatroom_url = bp_get_group_permalink( $group ) . BP_GROUP_CHATROOM_SLUG;

		// Record this in activity streams.
		if ( bp_is_active( 'activity' ) ) {
			$activity_id = groups_record_activity( array(
				'type'    			=> 'activity_update',
				'action'			=> '<a href="' . bp_core_get_user_displayname( $user_id ) . '" >' . bp_core_get_user_displayname($user_id) .'</a> ' .  $message . '<a href="'.	$chatroom_url . '" >' . $group_chatroom . '</a>',
				'content'			=> $activity_content,
				'item_id'			=> $chat_group_id,
				'user_id' 			=> $user_id,
				'hide_sitewide'     => true,
			) );
			$activity = new BP_Activity_Activity( $activity_id );
			if ( $usernames ) {
				foreach ( $usernames as $username ) {
					$receiver_user_id = bp_activity_get_userid_from_mentionname( $username );
					bp_group_chatroom_at_mention_add_notification( $activity, $receiver_user_id );
				}
			}
		}
		
	}
}

function bp_group_chatroom_allowed_tags( $allowed_tags ) {

	global $allowedtags;

	return array_merge_recursive( $allowed_tags, array(
		'p'	 		=> array(),
		'iframe' 	=> array(
			'src'		=> array()
		)
	) );
}

if ( bp_is_active( 'activity' ) ) {
	add_filter( 'bp_activity_allowed_tags', 'bp_group_chatroom_allowed_tags' );
	
}

function bp_group_chat_new_video() {
	global $bp, $wpdb;
	
	if ( sanitize_text_field( $_POST['bp_group_chat_new_video'] ) == 1 ) {
		$chat_group_id = sanitize_text_field( $_POST['bp_group_chat_group_id'] );
		//die if nonce fail
		check_ajax_referer( 'bpgl-nonce', 'security' );
		// only do this if member of the group or super admin
		if ( groups_is_user_member( $bp->loggedin_user->id, $chat_group_id )
			 || groups_is_user_mod( $bp->loggedin_user->id, $chat_group_id ) 
			 || groups_is_user_admin( $bp->loggedin_user->id, $chat_group_id )
			 || is_super_admin() ) {
				 
			//format video
			$video_content = esc_url_raw( $_POST['video_url'] );
			if ( ! bp_group_chatroom_check_url( $video_content ) ) {
				die();
			}
			$video_content = wp_oembed_get( $video_content, array( 'width' => 400, 'height' => 300 ) );

			// Check if the new message is part of a thread and if not start a new thread.
			$threads = $wpdb->get_results( $wpdb->prepare( "SELECT id, timestamp FROM {$wpdb->base_prefix}bp_group_chat_threads WHERE group_id=%d", $chat_group_id ) );
			if ( $threads ) {
				foreach( $threads as $thread ) {
					if ( $thread->timestamp > current_time( 'timestamp' ) - 1800 ) {
						$thread_id = $thread->id;
					}
				}
			}
			if ( !$threads || ! isset( $thread_id ) ) {
				$new_thread_sql = $wpdb->prepare( "INSERT INTO {$wpdb->base_prefix}bp_group_chat_threads".
									   "( group_id, user_id, timestamp ) ".
									   "VALUES ( %d, %d, %s )", 
									   $chat_group_id, $bp->loggedin_user->id, current_time( 'timestamp' ) );
				$wpdb->query($new_thread_sql);
				$thread_id_array = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM {$wpdb->base_prefix}bp_group_chat_threads WHERE group_id=%d AND user_id = %s", $chat_group_id, $bp->loggedin_user->id ) );
				$thread_id = $thread_id_array[0]->id;
			}
			
			// Add new video
			$sql = $wpdb->prepare( "INSERT INTO {$wpdb->base_prefix}bp_group_chat".
								   "( group_id, user_id, message_content, timestamp, thread_id ) ".
								   "VALUES ( %d, %d, %s, %s, %s )", 
								   $chat_group_id, $bp->loggedin_user->id, $video_content, current_time( 'timestamp' ), $thread_id );
			$wpdb->query($sql);
			die();
		}
	}
}
add_action( 'wp_ajax_bp_chat_new_video', 'bp_group_chat_new_video' );

//Check submitted URL for correct formatting
function bp_group_chatroom_check_url( $url ) {
	
    $path = parse_url($url, PHP_URL_PATH);
    $encoded_path = array_map('urlencode', explode('/', $path));
    $url = str_replace($path, implode('/', $encoded_path), $url);

    return filter_var($url, FILTER_VALIDATE_URL) ? true : false;
	
}

//Reset updates database for the user
function bp_group_chatroom_reset_updates_for_user( $group_id, $user_id ) {
	global $wpdb;

	$remove_updates_sql = $wpdb->prepare( "DELETE FROM {$wpdb->base_prefix}bp_group_chat_updates WHERE group_id = %d and user_id = %d", $group_id, $user_id );
	$wpdb->query($remove_updates_sql);

}

// Delete Chat Message

function bp_group_chatroom_delete_msg() {
	global $bp, $wpdb;
	
	if ( sanitize_text_field( $_POST['bp_group_chat_delete_msg'] ) == 1 ) {
		$chat_group_id = sanitize_text_field( $_POST['bp_group_chat_group_id'] );
		$msg_id = sanitize_text_field( $_POST['message_id'] );
		//die if nonce fail
		check_ajax_referer( 'bpgl-nonce', 'security' );
		// only do this if admin of the group or super admin
		if ( groups_is_user_mod( $bp->loggedin_user->id, $chat_group_id ) 
			 || groups_is_user_admin( $bp->loggedin_user->id, $chat_group_id )
			 || is_super_admin() ) {
			$remove_message_sql = $wpdb->prepare( "DELETE FROM {$wpdb->base_prefix}bp_group_chat WHERE id = %d", $msg_id );
			$wpdb->query($remove_message_sql);
			return true;
				 
		}
	}
	die();
	
}

add_action( 'wp_ajax_bp_chat_delete_msg', 'bp_group_chatroom_delete_msg' );

// Delete Chat Message

function bp_group_chatroom_close_thread() {
	global $bp, $wpdb;
	$group_chat_settings = groups_get_groupmeta( bp_get_current_group_id(), 'bp_group_chat_enabled' );
	
	if ( sanitize_text_field( $_POST['bp_group_chat_close_thread'] ) == 1 ) {
		$chat_group_id = sanitize_text_field( $_POST['bp_group_chat_group_id'] );
		//die if nonce fail
		check_ajax_referer( 'bpgl-nonce', 'security' );
		// only do this if admin of the group or super admin
		if ( groups_is_user_mod( $bp->loggedin_user->id, $chat_group_id ) 
			 || groups_is_user_admin( $bp->loggedin_user->id, $chat_group_id )
			 || is_super_admin() ) {
			
			$threads = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->base_prefix}bp_group_chat_threads WHERE group_id=%d", $chat_group_id ) );
			if ( $threads ) {
				foreach ( $threads as $thread ) {
					$remove_thread_sql = $wpdb->prepare( "DELETE FROM {$wpdb->base_prefix}bp_group_chat_threads WHERE id = %d", $thread->id );
					$wpdb->query($remove_thread_sql);
					if ( isset( $group_chat_settings['activity_enabled'] ) && $group_chat_settings['activity_enabled'] == 1 ) {
						bp_group_chatroom_send_thread_to_activity( $chat_group_id, $thread->id, $thread->user_id );
					}

				}
			}
				 
		}
	}
	die();
	
}

add_action( 'wp_ajax_bp_chat_close_thread', 'bp_group_chatroom_close_thread' );

// Delete Chat Message

function bp_group_chatroom_insert_image() {
	global $bp, $wpdb;
	
	if ( sanitize_text_field( $_POST['bp_group_chat_insert_image'] ) == 1 ) {
		$chat_group_id = bp_get_current_group_id();

		//die if nonce fail
		check_ajax_referer( 'bpgl-nonce', 'security' );
		// only do this if admin of the group or super admin
		if ( groups_is_user_member( $bp->loggedin_user->id, $chat_group_id )
			 || groups_is_user_mod( $bp->loggedin_user->id, $chat_group_id ) 
			 || groups_is_user_admin( $bp->loggedin_user->id, $chat_group_id )
			 || is_super_admin() ) {
				
			$image_url = esc_url( $_POST['imageURL'] );
			if ( function_exists( 'wp_featherlight' ) ) {
				$href = 'href="#" data-featherlight="' . $image_url . '"';
			} else {
				$href = 'href="' . $image_url . '"';
			}
			$image = '<a ' . $href . ' class="bp-group-chat-image" rel="group1" ><img src="' . $image_url . '"></a>';
			// Check if the new message is part of a thread and if not start a new thread.
			$threads = $wpdb->get_results( $wpdb->prepare( "SELECT id, timestamp FROM {$wpdb->base_prefix}bp_group_chat_threads WHERE group_id=%d", $chat_group_id ) );
			if ( $threads ) {
				foreach( $threads as $thread ) {
					if ( $thread->timestamp > current_time( 'timestamp' ) - 1800 ) {
						$thread_id = $thread->id;
					}
				}
			}
			if ( !$threads || ! isset( $thread_id ) ) {
				$new_thread_sql = $wpdb->prepare( "INSERT INTO {$wpdb->base_prefix}bp_group_chat_threads".
									   "( group_id, user_id, timestamp ) ".
									   "VALUES ( %d, %d, %s )", 
									   $chat_group_id, $bp->loggedin_user->id, current_time( 'timestamp' ) );
				$wpdb->query($new_thread_sql);
				$thread_id_array = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM {$wpdb->base_prefix}bp_group_chat_threads WHERE group_id=%d AND user_id = %s", $chat_group_id, $bp->loggedin_user->id ) );
				$thread_id = $thread_id_array[0]->id;
			}
			
			// Add new message
			$sql = $wpdb->prepare( "INSERT INTO {$wpdb->base_prefix}bp_group_chat".
								   "( group_id, user_id, message_content, timestamp, thread_id ) ".
								   "VALUES ( %d, %d, %s, %s, %s )", 
								   $chat_group_id, $bp->loggedin_user->id, $image, current_time( 'timestamp' ), $thread_id );
			$wpdb->query($sql);
			die;
		}
	}
	die();
	
}

add_action( 'wp_ajax_bp_chat_insert_image', 'bp_group_chatroom_insert_image' );
?>