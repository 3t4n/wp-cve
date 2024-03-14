<?php
/**
 * This file contains a class with notifications-related functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.4.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements notifications-related functions.
 */
class Nelio_Content_Notifications {

	protected static $instance;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	public function init() {

		add_action( 'plugins_loaded', array( $this, 'add_hooks_if_notifications_are_enabled' ) );
		add_action( 'delete_user', array( $this, 'delete_follower' ) );

	}//end init()

	public function add_hooks_if_notifications_are_enabled() {

		$settings = Nelio_Content_Settings::instance();
		if ( ! $settings->get( 'use_notifications' ) ) {
			return;
		}//end if

		// Post status change actions.
		add_action( 'nelio_content_notify_post_followers', array( $this, 'maybe_notify_post_followers' ), 10, 4 );

		// Editorial comments change actions.
		add_action( 'nelio_content_after_create_editorial_comment', array( $this, 'maybe_send_comment_creation_notification' ) );

		// Editorial tasks change actions.
		add_action( 'nelio_content_after_create_editorial_task', array( $this, 'maybe_send_task_creation_notification' ) );
		add_action( 'nelio_content_after_update_editorial_task', array( $this, 'maybe_send_task_update_notification' ) );

	}//end add_hooks_if_notifications_are_enabled()

	public function maybe_notify_post_followers( $post_id, $followers, $old_status, $old_followers ) {

		$post = get_post( $post_id );
		if ( empty( $post ) ) {
			return;
		}//end if

		$settings             = Nelio_Content_Settings::instance();
		$supported_post_types = $settings->get( 'calendar_post_types', array() );
		if ( ! in_array( $post->post_type, $supported_post_types, true ) ) {
			return;
		}//end if

		/**
		 * Filters the status that shouldn’t trigger a notification email.
		 *
		 * @param array $statuses Statuses that shouldn’t trigger a notification email. Default: [ `inherit`, `auto-draft` ].
		 *
		 * @since 1.4.2
		 */
		$ignored_statuses = apply_filters( 'nelio_content_notification_ignored_statuses', array( 'inherit', 'auto-draft' ), $post->post_type );
		$ignored_statuses = array_merge( $ignored_statuses, array( $old_status ) );

		// If the post status changed, let’s notify all current followers.
		$is_valid_status  = ! in_array( $post->post_status, $ignored_statuses, true );
		$is_status_change = $post->post_status !== $old_status;
		if ( $is_valid_status && $is_status_change ) {
			$email = $this->get_post_status_change_email_data( $post, $old_status );
			$this->send_email( $email, $followers, $post );
			return;
		}//end if

		// If it didn’t, but there are new followers, let’s them know.
		$new_followers = array_values( array_diff( $followers, $old_followers ) );
		if ( count( $new_followers ) ) {
			$email = $this->get_post_following_email_data( $post );
			$this->send_email( $email, $new_followers, $post );
			return;
		}//end if

	}//end maybe_notify_post_followers()

	public function maybe_send_comment_creation_notification( $comment ) {

		$post = get_post( $comment['post'] );
		if ( empty( $post ) ) {
			return;
		}//end if

		/**
		 *  Kill switch for comment creation notification.
		 *
		 *  @param array   $comment The comment.
		 *  @param WP_Post $post    The related post.
		 *
		 * @since 1.4.2
		 */
		if ( ! apply_filters( 'nelio_content_notification_editorial_comment', $comment, $post ) ) {
			return;
		}//end if

		$helper     = Nelio_Content_Post_Helper::instance();
		$followers  = $helper->get_post_followers( $comment['post'] );
		$recipients = array_values( array_unique( array_merge( $followers, array( $comment['author'] ) ) ) );

		$email = $this->get_comment_in_post_email_data( $post, $comment );
		$this->send_email( $email, $recipients, $comment );

	}//end maybe_send_comment_creation_notification()

	public function maybe_send_task_creation_notification( $task ) {

		$post    = null;
		$post_id = $task['postId'];
		if ( ! empty( $post_id ) ) {
			$post = get_post( $post_id );
			if ( empty( $post ) ) {
				return;
			}//end if
		}//end if

		/**
		 * Kill switch for task creation notification.
		 *
		 *  @param array        $task The task.
		 *  @param WP_Post|null $post The related post (if any).
		 *
		 * @since 1.4.2
		 */
		if ( ! apply_filters( 'nelio_content_notification_editorial_task', $task, $post ) ) {
			return;
		}//end if

		$helper     = Nelio_Content_Post_Helper::instance();
		$followers  = $helper->get_post_followers( $comment['post'] );
		$recipients = array_values( array_unique( array_merge( $followers, array( $task['assignerId'], $task['assigneeId'] ) ) ) );

		$email = $this->get_task_creation_email_data( $task, $post );
		$this->send_email( $email, $recipients, $task );

	}//end maybe_send_task_creation_notification()

	public function maybe_send_task_update_notification( $task ) {

		$post    = null;
		$post_id = $task['postId'];
		if ( ! empty( $post_id ) ) {
			$post = get_post( $post_id );
			if ( empty( $post ) ) {
				return;
			}//end if
		}//end if

		/**
		 * Kill switch for task update notification.
		 *
		 *  @param array        $task The task.
		 *  @param WP_Post|null $post The related post (if any).
		 *
		 * @since 1.4.2
		 */
		if ( ! apply_filters( 'nelio_content_notification_editorial_task', $task, $post ) ) {
			return;
		}//end if

		$helper     = Nelio_Content_Post_Helper::instance();
		$followers  = $helper->get_post_followers( $comment['post'] );
		$recipients = array_values( array_unique( array_merge( $followers, array( $task['assignerId'], $task['assigneeId'] ) ) ) );

		$email = $this->get_task_updated_email_data( $task, $post );
		$this->send_email( $email, $recipients, $task );

	}//end maybe_send_task_update_notification()

	public function delete_follower( $id ) {

		if ( ! $id ) {
			return;
		}//end if

		global $wpdb;
		return $wpdb->delete( // phpcs:ignore
			$wpdb->postmeta,
			array(
				'meta_key'   => '_nc_following_users', // phpcs:ignore
				'meta_value' => $id,                   // phpcs:ignore
			)
		);

	}//end delete_follower()

	private function send_email( $email, $recipients, $item ) {

		$recipients = $this->get_email_addresses( $recipients );

		/**
		 * Filters the recipients of the email.
		 *
		 * @param array         $recipients emails of the recipients
		 * @param string        $type       type of email we’re about to send. Values can be: `status-change`, `new-post-follower`, `comment`, `task-creation`, or `task-completed`.
		 * @param WP_Post|array $item       item that triggered the notification. Either a WordPress post, a task, or a comment.
		 *
		 * @since 2.0.0
		 */
		$recipients = apply_filters( 'nelio_content_notification_send_email_recipients', $recipients, $email['type'], $item );
		if ( empty( $recipients ) ) {
			return;
		}//end if

		/**
		 * Filters the subject of the email.
		 *
		 * @param string        $subject    the subject of the email.
		 * @param string        $type       type of email we’re about to send. Values can be: `status-change`, `new-post-follower`, `comment`, `task-creation`, or `task-completed`.
		 * @param WP_Post|array $item       item that triggered the notification. Either a WordPress post, a task, or a comment.
		 *
		 * @since 1.4.2
		 */
		$subject = apply_filters( 'nelio_content_notification_send_email_subject', $email['subject'], $email['type'], $item );

		/**
		 * Filters the message of the email.
		 *
		 * @param string        $message    the message of the email.
		 * @param string        $type       type of email we’re about to send. Values can be: `status-change`, `new-post-follower`, `comment`, `task-creation`, or `task-completed`.
		 * @param WP_Post|array $item       item that triggered the notification. Either a WordPress post, a task, or a comment.
		 *
		 * @since 1.4.2
		 */
		$message = apply_filters( 'nelio_content_notification_send_email_message', $email['message'], $email['type'], $item );

		/**
		 * Filters the headers of the email.
		 *
		 * @param string        $headers    the headers of the email.
		 * @param string        $type       type of email we’re about to send. Values can be: `status-change`, `new-post-follower`, `comment`, `task-creation`, or `task-completed`.
		 * @param WP_Post|array $item       item that triggered the notification. Either a WordPress post, a task, or a comment.
		 *
		 * @since 1.4.2
		 */
		$message_headers = apply_filters( 'nelio_content_notification_send_email_message_headers', '', $email['type'], $item );

		// phpcs:ignore
		return wp_mail( $recipients, $subject, $message, $message_headers );

	}//end send_email()

	private function get_email_addresses( $user_ids ) {

		if ( in_array( get_current_user_id(), $user_ids, true ) ) {
			/**
			 * Whether the current user should receive an email or not.
			 *
			 * @param boolean $receive_email whether the current user should receive an email or not. Default: `false`.
			 *
			 * @since      1.4.2
			 */
			if ( ! apply_filters( 'nelio_content_notification_email_current_user', false ) ) {
				$user_ids = array_values( array_diff( $user_ids, array( get_current_user_id() ) ) );
			}//end if
		}//end if

		$emails = array_map(
			function( $user_id ) {
				if ( ! is_user_member_of_blog( $user_id ) ) {
					return false;
				}//end if

				$info = get_userdata( $user_id );
				if ( empty( $info ) ) {
					return false;
				}//end if

				return $info->user_email;
			},
			$user_ids
		);

		return array_values( array_unique( array_filter( $emails ) ) );

	}//end get_email_addresses()

	private function get_post_status_change_email_data( $post, $old_status ) {

		$post_id     = $post->ID;
		$post_author = get_userdata( $post->post_author );
		$post_status = $post->post_status;
		$post_type   = get_post_type_object( $post->post_type )->labels->singular_name;
		$post_title  = ! empty( $post->post_title ) ? $post->post_title : _x( '(no title)', 'text', 'nelio-content' );

		$blog_name = get_option( 'blogname' );

		$current_user = wp_get_current_user();
		if ( 0 !== $current_user->ID ) {
			/* translators: 1: user name, 2. user email */
			$username_and_email = sprintf( _x( '%1$s (%2$s)', 'text', 'nelio-content' ), $current_user->display_name, $current_user->user_email );
		} else {
			$username_and_email = _x( 'WordPress Scheduler', 'text', 'nelio-content' );
		}//end if

		$message = '';

		// Email subject and first line of body.
		// Set message subjects according to what action is being taken on the Post.
		if ( 'new' === $old_status || 'auto-draft' === $old_status ) {

			$subject = sprintf(
				/* translators: 1: site name, 2: post type, 3. post title */
				_x( '[%1$s] New %2$s Created: “%3$s”', 'text', 'nelio-content' ),
				$blog_name,
				$post_type,
				$post_title
			);

			$message .= sprintf(
				/* translators: 1: post type, 2: post id, 3. post title, 4. user name */
				_x( 'A new %1$s (#%2$s “%3$s”) was created by %4$s', 'text', 'nelio-content' ),
				$post_type,
				$post_id,
				$post_title,
				$username_and_email
			) . "\r\n";

		} elseif ( 'trash' === $post_status ) {

			$subject = sprintf(
				/* translators: 1: site name, 2: post type, 3. post title */
				_x( '[%1$s] %2$s Trashed: “%3$s”', 'text', 'nelio-content' ),
				$blog_name,
				$post_type,
				$post_title
			);

			$message .= sprintf(
				/* translators: 1: post type, 2: post id, 3. post title, 4. user name */
				_x( '%1$s #%2$s “%3$s” was moved to the trash by %4$s', 'text', 'nelio-content' ),
				$post_type,
				$post_id,
				$post_title,
				$username_and_email
			) . "\r\n";

		} elseif ( 'trash' === $old_status ) {

			$subject = sprintf(
				/* translators: 1: site name, 2: post type, 3. post title */
				_x( '[%1$s] %2$s Restored (from Trash): “%3$s”', 'text', 'nelio-content' ),
				$blog_name,
				$post_type,
				$post_title
			);

			$message .= sprintf(
				/* translators: 1: post type, 2: post id, 3. post title, 4. user name */
				_x( '%1$s #%2$s “%3$s” was restored from trash by %4$s', 'text', 'nelio-content' ),
				$post_type,
				$post_id,
				$post_title,
				$username_and_email
			) . "\r\n";

		} elseif ( 'future' === $post_status ) {

			$subject = sprintf(
				/* translators: 1: site name, 2: post type, 3. post title */
				_x( '[%1$s] %2$s Scheduled: “%3$s”', 'text', 'nelio-content' ),
				$blog_name,
				$post_type,
				$post_title
			);

			$message .= sprintf(
				/* translators: 1: post type, 2: post id, 3. post title, 4. user name, 5. scheduled date  */
				_x( '%1$s #%2$s “%3$s” was scheduled by %4$s. It will be published on %5$s', 'text', 'nelio-content' ),
				$post_type,
				$post_id,
				$post_title,
				$username_and_email,
				$this->get_scheduled_datetime( $post )
			) . "\r\n";

		} elseif ( 'publish' === $post_status ) {

			$subject = sprintf(
				/* translators: 1: site name, 2: post type, 3. post title */
				_x( '[%1$s] %2$s Published: “%3$s”', 'text', 'nelio-content' ),
				$blog_name,
				$post_type,
				$post_title
			);

			$message .= sprintf(
				/* translators: 1: post type, 2: post id, 3. post title, 4. user name */
				_x( '%1$s #%2$s “%3$s” was published by %4$s', 'text', 'nelio-content' ),
				$post_type,
				$post_id,
				$post_title,
				$username_and_email
			) . "\r\n";

		} elseif ( 'publish' === $old_status ) {

			$subject = sprintf(
				/* translators: 1: site name, 2: post type, 3. post title */
				_x( '[%1$s] %2$s Unpublished: “%3$s”', 'text', 'nelio-content' ),
				$blog_name,
				$post_type,
				$post_title
			);

			$message .= sprintf(
				/* translators: 1: post type, 2: post id, 3. post title, 4. user name */
				_x( '%1$s #%2$s “%3$s” was unpublished by %4$s', 'text', 'nelio-content' ),
				$post_type,
				$post_id,
				$post_title,
				$username_and_email
			) . "\r\n";

		} else {

			$subject = sprintf(
				/* translators: 1: site name, 2: post type, 3. post title */
				_x( '[%1$s] %2$s Status Changed for “%3$s”', 'text', 'nelio-content' ),
				$blog_name,
				$post_type,
				$post_title
			);

			$message .= sprintf(
				/* translators: 1: post type, 2: post id, 3. post title, 4. user name */
				_x( 'Status was changed for %1$s #%2$s “%3$s” by %4$s', 'text', 'nelio-content' ),
				$post_type,
				$post_id,
				$post_title,
				$username_and_email
			) . "\r\n";

		}//end if

		$message .= sprintf(
			/* translators: 1: date, 2: time, 3: timezone */
			_x( 'This action was taken on %1$s at %2$s %3$s', 'text', 'nelio-content' ),
			date_i18n( get_option( 'date_format' ) ),
			date_i18n( get_option( 'time_format' ) ),
			get_option( 'timezone_string' )
		) . "\r\n";

		// Email body.
		$friendly_old_status  = $this->get_post_status_label( $old_status );
		$friendly_post_status = $this->get_post_status_label( $post_status );

		$message .= "\r\n";

		$message .= sprintf(
			/* translators: 1: old status, 2: new status */
			_x( '%1$s => %2$s', 'text', 'nelio-content' ),
			$friendly_old_status,
			$friendly_post_status
		);
		$message .= "\r\n\r\n";

		$message .= "--------------------\r\n\r\n";

		/* translators: post type */
		$message .= sprintf( _x( '== %s Details ==', 'title', 'nelio-content' ), $post_type ) . "\r\n";
		/* translators: post title */
		$message .= sprintf( _x( 'Title: %s', 'text', 'nelio-content' ), $post_title ) . "\r\n";

		if ( ! empty( $post_author ) ) {

			$message .= sprintf(
				/* translators: 1: author name, 2: author email */
				_x( 'Author: %1$s (%2$s)', 'text', 'nelio-content' ),
				$post_author->display_name,
				$post_author->user_email
			) . "\r\n";

		}//end if

		$message .= $this->get_email_footer( $post );
		return array(
			'type'    => 'status-change',
			'subject' => $subject,
			'message' => $message,
		);

	}//end get_post_status_change_email_data()

	private function get_post_following_email_data( $post ) {

		$post_id     = $post->ID;
		$post_type   = get_post_type_object( $post->post_type )->labels->singular_name;
		$post_title  = ! empty( $post->post_title ) ? $post->post_title : _x( '(no title)', 'text', 'nelio-content' );
		$post_author = get_userdata( $post->post_author );

		$blog_name = get_option( 'blogname' );

		$current_user = wp_get_current_user();
		if ( 0 !== $current_user->ID ) {
			/* translators: 1: user name, 2. user email */
			$username_and_email = sprintf( _x( '%1$s (%2$s)', 'text', 'nelio-content' ), $current_user->display_name, $current_user->user_email );
		} else {
			$username_and_email = _x( 'WordPress Scheduler', 'text', 'nelio-content' );
		}//end if

		$subject = sprintf(
			/* translators: 1: site name, 2: post type, 3. post title */
			_x( '[%1$s] You’re now watching %2$s “%3$s”', 'text', 'nelio-content' ),
			$blog_name,
			$post_type,
			$post_title
		);

		$message = sprintf(
			/* translators: 1: post type, 2: post title */
			_x( 'You’re now watching %1$s “%2$s”.', 'text', 'nelio-content' ),
			$post_type,
			$post_title
		) . "\r\n\r\n";

		$message .= sprintf(
			/* translators: 1: date, 2: time, 3: timezone */
			_x( 'This action was taken on %1$s at %2$s %3$s', 'text', 'nelio-content' ),
			date_i18n( get_option( 'date_format' ) ),
			date_i18n( get_option( 'time_format' ) ),
			get_option( 'timezone_string' )
		) . "\r\n\r\n";

		$message .= "--------------------\r\n\r\n";

		/* translators: post type */
		$message .= sprintf( _x( '== %s Details ==', 'title', 'nelio-content' ), $post_type ) . "\r\n";
		/* translators: post title */
		$message .= sprintf( _x( 'Title: %s', 'text', 'nelio-content' ), $post_title ) . "\r\n";

		if ( ! empty( $post_author ) ) {

			$message .= sprintf(
				/* translators: 1: author name, 2: author email */
				_x( 'Author: %1$s (%2$s)', 'text', 'nelio-content' ),
				$post_author->display_name,
				$post_author->user_email
			) . "\r\n";

		}//end if

		$message .= sprintf(
			/* translators: post status */
			_x( 'Status: %s', 'text', 'nelio-content' ),
			$this->get_post_status_label( $post->post_status )
		) . "\r\n";

		$message .= $this->get_email_footer( $post );

		return array(
			'type'    => 'new-post-follower',
			'subject' => $subject,
			'message' => $message,
		);

	}//end get_post_following_email_data()

	private function get_comment_in_post_email_data( $post, $comment ) {

		$post_id    = $post->ID;
		$post_type  = get_post_type_object( $post->post_type )->labels->singular_name;
		$post_title = ! empty( $post->post_title ) ? $post->post_title : _x( '(no title)', 'text', 'nelio-content' );

		$current_user       = wp_get_current_user();
		$current_user_name  = $current_user->display_name;
		$current_user_email = $current_user->user_email;

		$blog_name = get_option( 'blogname' );

		$current_date = mysql2date( get_option( 'date_format' ), $comment['date'] );
		$current_time = mysql2date( get_option( 'time_format' ), $comment['date'] );

		$subject = sprintf(
			/* translators: 1: blog name, 2: post title */
			_x( '[%1$s] New Editorial Comment: “%2$s”', 'text', 'nelio-content' ),
			$blog_name,
			$post_title
		);

		$message = sprintf(
			/* translators: 1: post id, 2: post title, 3. post type */
			_x( 'A new editorial comment was added to %3$s #%1$s “%2$s”', 'text', 'nelio-content' ),
			$post_id,
			$post_title,
			$post_type
		) . "\r\n\r\n";

		$message .= sprintf(
			/* translators: 1: comment author, 2: author email, 3: date, 4: time */
			_x( '%1$s (%2$s) said on %3$s at %4$s:', 'text', 'nelio-content' ),
			$current_user_name,
			$current_user_email,
			$current_date,
			$current_time
		) . "\r\n";

		$message .= "\r\n" . $comment['comment'] . "\r\n";
		$message .= $this->get_email_footer( $post );

		return array(
			'type'    => 'comment',
			'subject' => $subject,
			'message' => $message,
		);

	}//end get_comment_in_post_email_data()

	private function get_task_creation_email_data( $task, $post ) {

		if ( $post ) {
			$post_id    = $post->ID;
			$post_type  = get_post_type_object( $post->post_type )->labels->singular_name;
			$post_title = ! empty( $post->post_title ) ? $post->post_title : _x( '(no title)', 'text', 'nelio-content' );
		}//end if

		$current_user       = wp_get_current_user();
		$current_user_name  = $current_user->display_name;
		$current_user_email = $current_user->user_email;

		$blog_name = get_option( 'blogname' );

		if ( $post ) {

			$subject = sprintf(
				/* translators: 1: blog name, 2: post title */
				_x( '[%1$s] New Editorial Task in “%2$s”', 'text', 'nelio-content' ),
				$blog_name,
				$post_title
			);

			$message = sprintf(
				/* translators: 1: post id, 2: post title, 3. post type */
				_x( 'A new editorial task was added to %3$s #%1$s “%2$s”.', 'text', 'nelio-content' ),
				$post_id,
				$post_title,
				$post_type
			) . "\r\n\r\n";

		} else {

			/* translators: blog name */
			$subject = sprintf( _x( '[%s] New Editorial Task', 'text', 'nelio-content' ), $blog_name );
			$message = _x( 'A new editorial task was added.', 'text', 'nelio-content' ) . "\r\n\r\n";

		}//end if

		$message .= sprintf(
			/* translators: 1: task author, 2: task author email */
			_x( '%1$s (%2$s) created the following task:', 'text', 'nelio-content' ),
			$current_user_name,
			$current_user_email
		) . "\r\n\r\n";

		$message .= $this->get_task_information( $task );
		$message .= $this->get_email_footer( $post );

		return array(
			'type'    => 'task-creation',
			'subject' => $subject,
			'message' => $message,
		);

	}//end get_task_creation_email_data()

	private function get_task_updated_email_data( $task, $post ) {

		if ( $post ) {
			$post_id    = $post->ID;
			$post_type  = get_post_type_object( $post->post_type )->labels->singular_name;
			$post_title = ! empty( $post->post_title ) ? $post->post_title : _x( '(no title)', 'text', 'nelio-content' );
		}//end if

		$current_user       = wp_get_current_user();
		$current_user_name  = $current_user->display_name;
		$current_user_email = $current_user->user_email;

		$blog_name = get_option( 'blogname' );

		if ( $post ) {

			$subject = sprintf(
				! empty( $task['completed'] )
					/* translators: 1: blog name, 2: post title */
					? _x( '[%1$s] Editorial Task Completed in “%2$s”', 'text', 'nelio-content' )
					/* translators: 1: blog name, 2: post title */
					: _x( '[%1$s] Editorial Task Updated in “%2$s”', 'text', 'nelio-content' ),
				$blog_name,
				$post_title
			);

			$message = sprintf(
				! empty( $task['completed'] )
					/* translators: 1: post id, 2: post title, 3. post type */
					? _x( 'An editorial task was completed in %3$s #%1$s “%2$s”.', 'text', 'nelio-content' )
					/* translators: 1: post id, 2: post title, 3. post type */
					: _x( 'An editorial task was updated in %3$s #%1$s “%2$s”.', 'text', 'nelio-content' ),
				$post_id,
				$post_title,
				$post_type
			) . "\r\n\r\n";

		} else {

			$subject = sprintf(
				! empty( $task['completed'] )
					/* translators: blog name */
					? _x( '[%s] Editorial Task Completed', 'text', 'nelio-content' )
					/* translators: blog name */
					: _x( '[%s] Editorial Task Updated', 'text', 'nelio-content' ),
				$blog_name
			);
			$message = ! empty( $task['completed'] )
				? _x( 'An editorial task was completed.', 'text', 'nelio-content' ) . "\r\n\r\n"
				: _x( 'An editorial task was updated.', 'text', 'nelio-content' ) . "\r\n\r\n";

		}//end if

		$message .= sprintf(
			! empty( $task['completed'] )
				/* translators: 1: task author, 2: task author email */
				? _x( '%1$s (%2$s) completed the following task:', 'text', 'nelio-content' )
				/* translators: 1: task author, 2: task author email */
				: _x( '%1$s (%2$s) updated the following task:', 'text', 'nelio-content' ),
			$current_user_name,
			$current_user_email
		) . "\r\n\r\n";

		$message .= $this->get_task_information( $task );
		$message .= $this->get_email_footer( $post );

		return array(
			'type'    => ! empty( $task['completed'] ) ? 'task-completed' : 'task-updated',
			'subject' => $subject,
			'message' => $message,
		);

	}//end get_task_updated_email_data()

	private function get_task_information( $task ) {

		$assignee       = get_userdata( $task['assigneeId'] );
		$assignee_name  = _x( 'Unknown Assignee', 'text', 'nelio-content' );
		$assignee_email = '';
		if ( $assignee ) {
			$assignee_name  = $assignee->display_name;
			$assignee_email = $assignee->user_email;
		}//end if

		$assigner       = get_userdata( $task['assignerId'] );
		$assigner_name  = _x( 'Unknown Assignee', 'text', 'nelio-content' );
		$assigner_email = '';
		if ( $assigner ) {
			$assigner_name  = $assigner->display_name;
			$assigner_email = $assigner->user_email;
		}//end if

		/* translators: a task description */
		$info = ' - ' . sprintf( _x( 'Task: %s', 'text', 'nelio-content' ), $task['task'] ) . "\r\n";
		/* translators: 1: user name, 2: user email */
		$info .= ' - ' . sprintf( _x( 'Assignee: %1$s (%2$s)', 'text', 'nelio-content' ), $assignee_name, $assignee_email ) . "\r\n";
		/* translators: 1: user name, 2: user email */
		$info .= ' - ' . sprintf( _x( 'Assigner: %1$s (%2$s)', 'text', 'nelio-content' ), $assigner_name, $assigner_email ) . "\r\n";

		if ( $task['dateDue'] ) {
			$task_due_date = mysql2date( get_option( 'date_format' ), $task['dateDue'] );
			$task_due_time = mysql2date( get_option( 'time_format' ), $task['dateDue'] );
			/* translators: a date */
			$info .= ' - ' . sprintf( _x( 'Due Date: %s', 'text', 'nelio-content' ), $task_due_date ) . "\r\n";
		}//end if

		return $info;

	}//end get_task_information()

	private function get_email_footer( $post = false ) {

		$blog_name = get_option( 'blogname' );
		$blog_url  = get_bloginfo( 'url' );
		$admin_url = admin_url( '/' );

		$footer = '';

		if ( $post ) {

			$post_title       = ! empty( $post->post_title ) ? $post->post_title : _x( '(no title)', 'text', 'nelio-content' );
			$edit_link        = htmlspecialchars_decode( get_edit_post_link( $post->ID ) );
			$post_type_labels = get_post_type_object( $post->post_type )->labels;

			if ( 'publish' !== $post->post_status ) {
				$view_link = add_query_arg( array( 'preview' => 'true' ), wp_get_shortlink( $post->ID ) );
			} else {
				$view_link = htmlspecialchars_decode( get_permalink( $post->ID ) );
			}//end if

			$footer .= "\r\n";
			$footer .= _x( '== Actions ==', 'title', 'nelio-content' ) . "\r\n";
			/* translators: 1: the "Edit" command, as in "Edit Post", 2: the edit link */
			$footer .= sprintf( _x( '%1$s: %2$s', 'command (edit)', 'nelio-content' ), $post_type_labels->edit_item, $edit_link ) . "\r\n";
			/* translators: 1: the "View" command, as in "View Post", 2: the view link */
			$footer .= sprintf( _x( '%1$s: %2$s', 'command (view)', 'nelio-content' ), $post_type_labels->view_item, $view_link ) . "\r\n";

			$footer .= "\r\n--------------------\r\n";
			/* translators: a post title */
			$footer .= sprintf( _x( 'You are receiving this email because you are subscribed to “%s”.', 'user', 'nelio-content' ), $post_title );

		} else {

			$footer .= "\r\n--------------------\r\n";
			/* translators: a blog URL */
			$footer .= sprintf( _x( 'You are receiving this email because you are registered to %s.', 'user', 'nelio-content' ), $blog_url );

		}//end if

		$footer .= "\r\n\r\n";
		$footer .= $blog_name . ' | ' . $blog_url . ' | ' . $admin_url . "\r\n";

		return $footer;

	}//end get_email_footer()

	private function get_scheduled_datetime( $post ) {

		$scheduled_timestatmp = strtotime( $post->post_date );

		$date = date_i18n( get_option( 'date_format' ), $scheduled_timestatmp );
		$time = date_i18n( get_option( 'time_format' ), $scheduled_timestatmp );

		/* translators: 1: post scheduled date, 2: post scheduled time */
		return sprintf( _x( '%1$s at %2$s', 'text', 'nelio-content' ), $date, $time );

	}//end get_scheduled_datetime()

	private function get_post_status_label( $status ) {
		$status_object = get_post_status_object( $status );
		return empty( $status_object ) ? $status : $status_object->label;
	}//end get_post_status_label()

}//end class
