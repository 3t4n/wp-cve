<?php

if ( ! defined( 'CANVAS_DIR' ) ) {
	die();
}

class CanvasPs {



	public static function init() {
		 // Unset option in case the plugin is deactivated
		if ( ! class_exists( 'PeepSo' ) ) {
			Canvas::set_option( 'ps_mentions_comments', '' );
			Canvas::set_option( 'ps_mentions_posts', '' );
		}
		if ( ! class_exists( 'PeepSoFriendsPlugin' ) ) {
			Canvas::set_option( 'ps_friends', '' );
		}
		if ( ! class_exists( 'PeepSoMessagesPlugin' ) ) {
			Canvas::set_option( 'ps_private_messages', '' );
		}

		if ( self::option_on( 'ps_private_messages' ) ) {
			add_action( 'peepso_messages_new_message', array( 'CanvasPs', 'new_message' ) );
		}
		if ( self::option_on( 'ps_friends' ) ) {
			add_action( 'peepso_friends_requests_after_add', array( 'CanvasPs', 'friends_requests' ), 10, 2 );
		}
		if ( self::option_on( 'ps_mentions_comments' ) ) {
			add_action( 'peepso_activity_after_add_comment', array( 'CanvasPs', 'mentions_comments' ), 10, 2 );
		}
		if ( self::option_on( 'ps_mentions_posts' ) ) {
			add_action( 'peepso_activity_after_add_post', array( 'CanvasPs', 'mentions_posts' ), 10, 2 );
		}
		// if (self::option_on('ps_reaction')) {
		// add_action('peepso_action_react_add', array('CanvasPs', 'reaction_set'), 10, 1);
		// }
		add_action( 'peepso_action_react_add', array( 'CanvasPs', 'reaction_set' ), 10, 1 );
		add_action( 'peepso_action_like_add', array( 'CanvasPs', 'comment_like' ), 10, 1 );
		add_action( 'peepso_action_create_notification_after', array( 'CanvasPs', 'new_comment' ) );
		add_action( 'peepso_action_create_notification_after', array( 'CanvasPs', 'profile_like' ) );
		add_action( 'peepso_action_create_notification_after', array( 'CanvasPs', 'wall_post' ) );
	}

	/**
	 * Fires notification to all participants belong to the conversation when it has any new message
	 *
	 * @param $parent_id
	 */
	public static function new_message( $parent_id ) {
		if ( PeepSoMessagesPlugin::CPT_MESSAGE !== get_post_type( $parent_id ) ) {
			return;
		}
		// Get the latest message
		$args          = array(
			'post_type'      => PeepSoMessagesPlugin::CPT_MESSAGE,
			'posts_per_page' => 1,
			'post_parent'    => $parent_id,
		);
		$latest_msg_id = get_posts( $args );
		if ( isset( $latest_msg_id[0] ) && $latest_msg_id[0] instanceof WP_Post ) {
			$message_obj = $latest_msg_id[0];
			// get all recipients of that message
			$msg_participants = new PeepSoMessageParticipants();
			$peepso_messages  = PeepSoMessages::get_instance();

			$participants = $msg_participants->get_participants( $parent_id );
			$participants = array_diff( array_map( 'intval', $participants ), array( get_current_user_id() ) );

			$current_user = wp_get_current_user();

			$push_api = self::get_push_api();
			$push_api->save_ps_log( 'notification', $parent_id );

			$title = $current_user->display_name . ' sent you a message';
			$text  = htmlspecialchars_decode( wp_trim_words( $message_obj->post_content, 20, '...' ) );
			$url   = $peepso_messages->get_message_url( $parent_id );

			$push_api->send_to_users( $title, $text, $participants, $url );
		}
	}

	/**
	 * Fires when a user get new friend request
	 *
	 * @param $from_id
	 * @param $to_id
	 */
	public static function friends_requests( $from_id, $to_id ) {
		$sender = PeepSoUser::get_instance( $from_id );

		$title = __( 'New Friend Request', 'canvas' );
		$text  = htmlspecialchars_decode( sprintf( __( 'You have got a friend request from %s', 'canvas' ), $sender->get_fullname() ) );
		$data  = array(
			'from' => $from_id,
			'to'   => $to_id,
		);
		$url   = PeepSoFriendsPlugin::get_url( get_current_user_id(), 'requests' );

		$push_api = self::get_push_api();
		$push_api->save_ps_log( 'notification', $data );
		$push_api->send_to_users( $title, $text, array( $to_id ), $url );
	}

	/**
	 * Fires when any users are mentioned in comment and its following user list
	 *
	 * @param $post_id
	 * @param $act_id
	 */
	public static function mentions_comments( $post_id, $act_id ) {
		$post_obj = get_post( $post_id );
		$match    = preg_match_all( '/' . self::get_tag_parser() . '/i', $post_obj->post_content, $matches );

		if ( $match ) {
			global $post;

			$PeepSoActivity        = PeepSoActivity::get_instance();
			$post_act              = $PeepSoActivity->get_activity( $act_id );
			$act_comment_object_id = $post_act->act_comment_object_id;
			$act_comment_module_id = $post_act->act_comment_module_id;

			$post = $post_obj;
			setup_postdata( $post );

			$not_activity    = $PeepSoActivity->get_activity_data( $post_id, PeepSoActivity::MODULE_ID );
			$parent_activity = $PeepSoActivity->get_activity_data( $not_activity->act_comment_object_id, $not_activity->act_comment_module_id );
			if ( is_object( $parent_activity ) ) {
				$not_post    = $PeepSoActivity->get_activity_post( $not_activity->act_id );
				$parent_post = $PeepSoActivity->get_activity_post( $parent_activity->act_id );

				// check if parent post is a comment
				if ( $parent_post->post_type == 'peepso-comment' ) {
					$comment_activity = $PeepSoActivity->get_activity_data( $not_activity->act_comment_object_id, $not_activity->act_comment_module_id );
					$post_activity    = $PeepSoActivity->get_activity_data( $comment_activity->act_comment_object_id, $comment_activity->act_comment_module_id );

					$parent_post    = $PeepSoActivity->get_activity_post( $post_activity->act_id );
					$parent_comment = $PeepSoActivity->get_activity_post( $comment_activity->act_id );

					$parent_link = PeepSo::get_page( 'activity_status' ) . $parent_post->post_title . '/?t=' . time() . '#comment.' . $post_activity->act_id . '.' . $parent_comment->ID . '.' . $comment_activity->act_id . '.' . $not_activity->act_external_id;
				} else {
					$parent_link = PeepSo::get_page( 'activity_status' ) . $parent_post->post_title . '/#comment.' . $parent_activity->act_id . '.' . $not_post->ID . '.' . $not_activity->act_external_id;
				}
			} else {
				$parent_link = $PeepSoActivity->post_link( false );
			}

			$user_ids = $matches[1];

			$title = __( 'Someone mentioned you in a comment', 'canvas' );
			$text  = wp_trim_words( self::remove_peepso_user( $post->post_content ), 20, '...' );
			self::normalize_title_and_text( $title, $text );

			$to_users = array();
			foreach ( $user_ids as $user_id ) {
				$user_id = intval( $user_id );

				// If self don't send the notification
				if ( intval( $post->post_author ) === $user_id ) {
					continue;
				}

				// Check access
				if ( ! PeepSo::check_permissions( $user_id, PeepSo::PERM_POST_VIEW, intval( $post->post_author ) ) ) {
					continue;
				}

				$users    = $PeepSoActivity->get_comment_users( $act_comment_object_id, $act_comment_module_id );
				$follower = array();
				while ( $users->have_posts() ) {
					$users->next_post();
					$follower[] = $users->post->post_author;
				}
				// if not following post send tagged notification
				if ( ( ! in_array( $user_id, $follower ) && ( $user_id != $post_act->act_owner_id ) ) || ( $post_act->act_owner_id == $user_id && intval( $post_act->act_comment_object_id ) > 0 ) ) {
					$to_users[] = $user_id;
				}
			}
			if ( ! empty( $to_users ) ) {
				$data     = array(
					'title'    => htmlspecialchars_decode( $title ),
					'text'     => htmlspecialchars_decode( $text ),
					'to_users' => $to_users,
					'url'      => $parent_link,
				);
				$push_api = self::get_push_api();
				$push_api->save_ps_log( 'notification', $data );
				$push_api->send_to_users( $data['title'], $data['text'], $data['to_users'], $data['url'] );
			}
		}
	}

	/**
	 * Fires when user are mentioned in a post
	 *
	 * @param $post_id
	 * @param $act_id
	 */
	public static function mentions_posts( $post_id, $act_id ) {
		$post_obj = get_post( $post_id );

		if ( $post_obj->post_status != 'publish' ) {
			return;
		}

		$match = preg_match_all( '/' . self::get_tag_parser() . '/i', $post_obj->post_content, $matches );
		if ( $match ) {
			global $post;

			$PeepSoActivity = PeepSoActivity::get_instance();

			$post_act = $PeepSoActivity->get_activity( $act_id );

			$post = $post_obj;
			setup_postdata( $post );

			$url = $PeepSoActivity->post_link( false );

			$user_ids = $matches[1];

			$title = __( 'Someone mentioned you in a post', 'canvas' );
			$text  = wp_trim_words( self::remove_peepso_user( $post->post_content ), 20, '...' );
			self::normalize_title_and_text( $title, $text );

			$to_users = array();
			foreach ( $user_ids as $user_id ) {
				$user_id = intval( $user_id );

				// If self don't send the notification
				if ( intval( $post->post_author ) === $user_id ) {
					continue;
				}

				// Check access
				if ( ! PeepSo::check_permissions( $user_id, PeepSo::PERM_POST_VIEW, intval( $post->post_author ) ) ) {
					continue;
				}

				// check act_owner is current user_id
				$post_ID        = $post_act->act_external_id;
				$post_author_id = get_post_field( 'post_author', $post_ID );
				if ( $user_id != $post_act->act_owner_id || $user_id != $post_author_id ) {
					$to_users[] = $user_id;
				}
			}
			if ( ! empty( $to_users ) ) {
				$data     = array(
					'title'    => htmlspecialchars_decode( $title ),
					'text'     => htmlspecialchars_decode( $text ),
					'to_users' => $to_users,
					'url'      => $url,
				);
				$push_api = self::get_push_api();
				$push_api->save_ps_log( 'notification', $data );
				$push_api->send_to_users( $data['title'], $data['text'], $data['to_users'], $data['url'] );
			}
		}
	}

	/**
	 * Send notification if someone likes a comment.
	 *
	 * @param object $args Comment "Like" object.
	 */
	public static function comment_like( $args ) {
		if ( empty( $args ) ) {
			return false;
		}

		$activity = new PeepSoActivity();
		$act_post = $activity->get_activity_data( $args->like_external_id );

		if ( ! $act_post ) {
			return;
		}

		$act_id          = $act_post->act_id;
		$user_id         = $args->like_user_id;

		$act_post        = $activity->get_activity_post( $act_id );
		$post_id         = $act_post->ID;
		$owner_id        = $activity->get_author_id( $post_id );

		if ( $owner_id === $user_id ) {
			return;
		}

		$user_owner      = PeepSoUser::get_instance( $owner_id );
		$user            = PeepSoUser::get_instance( $user_id );
		$url             = PeepSo::get_page( 'activity' ) . '?status/' . $act_post->post_title;
		$data            = array_merge( $user->get_template_fields('from'), $user_owner->get_template_fields('user') );

		$text = sprintf(
			__( '%s liked your comment.', 'canvas' ),
			$data['fromfullname']
		);

		$notif_data = array(
			'title'    => __( 'Comment liked', 'canvas' ),
			'text'     => $text,
			'to_users' => array( $owner_id ),
			'url'      => $url,
		);

		$push_api = self::get_push_api();
		$push_api->save_ps_log( 'notification', $notif_data );
		$push_api->send_to_users( $notif_data['title'], $notif_data['text'], $notif_data['to_users'], $notif_data['url'] );
	}

	public static function new_comment( $id ) {
		global $wpdb;

		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}peepso_notifications WHERE not_id=%s",
				array( $id )
			)
		);

		if ( empty( $results ) ) {
			return;
		}

		$row = $results[0];

		$from_user_id = $row->not_from_user_id;
		$to_user_id   = $row->not_user_id;

		if ( $from_user_id === $to_user_id ) {
			return;
		}

		if ( ! ( 'user_comment' === $row->not_type || 'stream_reply_comment' === $row->not_type ) ) {
			return;
		}

		$from_user   = PeepSoUser::get_instance( $from_user_id )->get_template_fields( 'from' );
		$message     = $from_user['fromfullname'] . ' ' . $row->not_message;
		$activity_id = $row->not_act_id;
		$activity    = new PeepSoActivity();

		$parent_post = $activity->get_activity_post( $activity_id );


		$not_activity    = $activity->get_activity_data( $row->not_external_id, PeepSoActivity::MODULE_ID );
		$parent_activity = $activity->get_activity_data( $not_activity->act_comment_object_id, $not_activity->act_comment_module_id );

		if ( is_object( $parent_activity ) ) {
			$not_post    = $activity->get_activity_post( $not_activity->act_id );
			$parent_post = $activity->get_activity_post( $parent_activity->act_id );

			// check if parent post is a comment
			if ( $parent_post->post_type == 'peepso-comment' ) {
				$comment_activity = $activity->get_activity_data( $not_activity->act_comment_object_id, $not_activity->act_comment_module_id );
				$post_activity    = $activity->get_activity_data( $comment_activity->act_comment_object_id, $comment_activity->act_comment_module_id );

				$parent_post    = $activity->get_activity_post( $post_activity->act_id );
				$parent_comment = $activity->get_activity_post( $comment_activity->act_id );

				$parent_link = PeepSo::get_page( 'activity_status' ) . $parent_post->post_title . '/?t=' . time() . '#comment.' . $post_activity->act_id . '.' . $parent_comment->ID . '.' . $comment_activity->act_id . '.' . $not_activity->act_external_id;
			} else {
				$parent_link = PeepSo::get_page( 'activity_status' ) . $parent_post->post_title . '/#comment.' . $parent_activity->act_id . '.' . $not_post->ID . '.' . $not_activity->act_external_id;
			}
		} else {
			$parent_link = $activity->post_link( false );
		}

		$notif_data = array(
			'title'    => __( 'New comment' ),
			'text'     => $message,
			'to_users' => array( $to_user_id ),
			'url'      => $parent_link,
		);

		$push_api = self::get_push_api();
		$push_api->save_ps_log( 'notification', $notif_data );
		$push_api->send_to_users( $notif_data['title'], $notif_data['text'], $notif_data['to_users'], $notif_data['url'] );
	}

	public static function profile_like( $id ) {
		global $wpdb;

		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}peepso_notifications WHERE not_id=%s",
				array( $id )
			)
		);

		if ( empty( $results ) ) {
			return;
		}

		$row = $results[0];

		$from_user_id = $row->not_from_user_id;
		$to_user_id   = $row->not_user_id;

		if ( $from_user_id === $to_user_id ) {
			return;
		}

		if ( 'profile_like' !== $row->not_type ) {
			return;
		}

		$from_user   = PeepSoUser::get_instance( $from_user_id )->get_template_fields( 'from' );
		$message     = $from_user['fromfullname'] . ' ' . $row->not_message;

		$notif_data = array(
			'title'    => __( 'New profile like' ),
			'text'     => $message,
			'to_users' => array( $to_user_id )
		);

		$push_api = self::get_push_api();
		$push_api->save_ps_log( 'notification', $notif_data );
		$push_api->send_to_users( $notif_data['title'], $notif_data['text'], $notif_data['to_users'], $notif_data['url'] );
	}

	public static function wall_post( $id ) {
		global $wpdb;

		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}peepso_notifications WHERE not_id=%s",
				array( $id )
			)
		);

		if ( empty( $results ) ) {
			return;
		}

		$row = $results[0];

		$from_user_id = $row->not_from_user_id;
		$to_user_id   = $row->not_user_id;

		if ( $from_user_id === $to_user_id ) {
			return;
		}

		if ( 'wall_post' !== $row->not_type ) {
			return;
		}

		$from_user   = PeepSoUser::get_instance( $from_user_id )->get_template_fields( 'from' );
		$message     = $from_user['fromfullname'] . ' ' . $row->not_message;

		$notif_data = array(
			'title'    => __( 'New wall post' ),
			'text'     => $message,
			'to_users' => array( $to_user_id )
		);

		$push_api = self::get_push_api();
		$push_api->save_ps_log( 'notification', $notif_data );
		$push_api->send_to_users( $notif_data['title'], $notif_data['text'], $notif_data['to_users'], $notif_data['url'] );
	}

	public static function reaction_set( $args ) {
		if ( empty( $args ) ) {
			return false;
		}
		/**
		 * $react_external_id
		 * $react_act_id
		 * $react_user_id
		 * $react_module_id
		 */
		$activity = new PeepSoActivity();

		$react_id = $args->react_act_id;
		if ( empty( $react_id ) ) {
			$react_id = $args->react_external_id;
		}
		$act_post = $activity->get_activity_post( $react_id );
		$post_id  = $act_post->ID;
		$owner_id = $activity->get_author_id( $post_id );

		$react_user_id   = $args->react_user_id;
		$react_user_data = get_userdata( $react_user_id );
		$react_user_name = empty( $react_user_data->first_name ) ? $react_user_data->display_name : $react_user_data->first_name;

		$post_type        = get_post_type( $post_id );
		$post_type_object = get_post_type_object( $post_type );

		if ( 'post' === $post_type_object->labels->activity_type ) {
			$react_post_id = filter_input( INPUT_POST, 'react_id', FILTER_SANITIZE_NUMBER_INT );
			$react_posts   = self::mobiloud_ps_reaction_post( $react_post_id );

			if ( $owner_id != $react_user_id ) {
				$push_api = self::get_push_api();
				// perform like
				if ( 0 == $react_post_id ) {
					// add LIKE notification
					$notification_message = $react_user_name . ' ' . sprintf( __( 'liked your %s', 'peepso-core' ), $post_type_object->labels->activity_type );
				} else {
					// add REACT notification
					$notification_message = $react_user_name . ' ' . $react_posts[ $react_post_id ]['content'] . ' ' . sprintf( __( 'your %s', 'peepso-core' ), $post_type_object->labels->activity_type );
				}
				$data = array(
					'title'    => __( 'You have got a new reaction', 'canvas' ),
					'text'     => htmlspecialchars_decode( $notification_message ),
					'to_users' => array( $owner_id ),
					'url'      => PeepSo::get_page( 'activity' ) . '?status/' . $act_post->post_title,
				);
				$push_api->save_ps_log( 'notification', $data );
				$push_api->send_to_users( $data['title'], $data['text'], $data['to_users'], $data['url'] );
			}
		}
	}

	public static function mobiloud_ps_reaction_post( $react_id ) {
		if ( empty( $react_id ) ) {
			return false;
		}
		$args  = array(
			'post_type'      => array( 'peepso_reaction', 'peepso_reaction_user' ),
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'posts_per_page' => -1,
			'post_status'    => 'any',
		);
		$posts = new WP_Query( $args );

		if ( ! $posts->have_posts() ) {
			require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '../install' . DIRECTORY_SEPARATOR . 'activate.php';
			$install = new PeepSoActivate();
			$install->plugin_activation();
			$posts = new WP_Query( $args );
		}

		$reactions = array();
		if ( $posts->have_posts() ) {
			foreach ( $posts->posts as $post ) {
				$reaction = array(
					'id'                => $post->post_parent,
					'post_id'           => $post->ID,
					'published'         => intval( ( 'publish' == $post->post_status ) ),
					'title'             => __( $post->post_title, 'peepso-core' ),
					'content'           => __( $post->post_content, 'peepso-core' ),
					'icon'              => $post->post_excerpt,
					'icon_url'          => plugin_dir_url( dirname( __FILE__ ) ) . 'assets/images/svg/' . $post->post_excerpt,
					'custom'            => intval( ( 'peepso_reaction_user' == $post->post_type ) ),
					'order'             => intval( $post->menu_order ),
					'has_default_title' => false,
				);

				if ( 1 == $reaction['custom'] ) {
					$reaction['id']                = $post->ID;
					$reaction['has_default_title'] = intval( ( 1 == get_post_meta( $post->ID, 'default_title', true ) ) );
				}

				if ( strstr( $post->post_excerpt, 'peepsocustom-' ) ) {
					$reaction['icon_url'] = str_replace( 'peepsocustom-', '', $post->post_excerpt );
				}

				$reaction['class']            = 'ps-reaction-emoticon-' . $reaction['id'];
				$reactions[ $reaction['id'] ] = $reaction;
			}
		}
		return $reactions;
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


	private static function option_on( $name ) {
		return Canvas::get_option( $name );
	}


	/**
	 * Returns the regular expression that matches the markup for the @ character.
	 *
	 * @return string
	 */
	static function get_tag_parser() {
		$old_tag = '\[peepso_tag id=(\d+)\]([^\]]+)\[\/peepso_tag\]';
		$new_tag = '@peepso_user_(\d+)(?:\(([^\)]+)\))?';
		return apply_filters( 'peepso_tags_parser', $new_tag );
	}

	static function remove_peepso_user( $string ) {
		return preg_replace( '/@peepso_user_(?:\d+)(\(([^\)]+)\))?/i', '$2', $string );
	}
}

if (
	Canvas::get_option( 'ps_private_messages' ) ||
	Canvas::get_option( 'ps_friends' ) ||
	Canvas::get_option( 'ps_mentions_comments' ) ||
	Canvas::get_option( 'ps_mentions_posts' )
) {
	add_action( 'peepso_init', array( 'CanvasPs', 'init' ) );
} else {
	Canvas::set_option( 'ps_private_messages', '' );
	Canvas::set_option( 'ps_friends', '' );
	Canvas::set_option( 'ps_mentions_comments', '' );
	Canvas::set_option( 'ps_mentions_posts', '' );
}
