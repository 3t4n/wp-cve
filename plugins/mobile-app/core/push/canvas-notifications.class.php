<?php

if ( ! defined( 'CANVAS_DIR' ) ) {
	die();
}
require_once CANVAS_DIR . 'core/canvas-admin.class.php';
require_once dirname( __FILE__ ) . '/canvas-notifications-db.class.php';
require_once dirname( __FILE__ ) . '/canvas-notifications-view.class.php';

class CanvasNotifications {

	/**
	 * @var CanvasOnesignalApi
	 */
	protected $api;

	/**
	 * @var CanvasNotifications
	 */
	private static $instance;

	public static function get() {
		if ( empty( self::$instance ) ) {
			self::$instance = new CanvasNotifications();
		}
		return self::$instance;
	}

	public function __construct() {
		require_once dirname( __FILE__ ) . '/canvas-onesignal-api.class.php';
		$this->api = new CanvasOnesignalApi();
	}

	/**
	 * Using manual push notification require at least 'publish_posts' capability
	 *
	 * @param string $print Message with error
	 */
	private static function check_is_action_allowed( $print = 'Not allowed' ) {
		if ( ! current_user_can( 'publish_posts' ) ) {
			die( $print );
		}
	}

	public function send_notifications( $data, $tagNames = array() ) {
		return $this->api->send_batch_notification( $data, $tagNames );
	}

	public function send_to_users( $title, $message, $users_list, $url = '' ) {
		$users = $this->user_id_to_api_id( $users_list );
		if ( 0 === stripos( $title, 'bbp ' ) ) {
			$title = substr( $title, 4 );
		}

		/**
		* Allow to customize title of push notification for users.
		*
		* @since 3.2
		*
		* @param string $title Title.
		* @param string[] $users_list Array with canvas-username of each user.
		* @param string $url URL.
		* @param string $message Message text.
		*/
		$title = apply_filters( 'canvas_push_bp_title', $title, $users_list, $url, $message );

		/**
		* Allow to customize message of push notification for users.
		*
		* @since 3.2
		*
		* @param string $message Message text.
		* @param string[] $users_list Array with canvas-username of each user.
		* @param string $url URL.
		* @param string $title Title.
		*/
		$message = apply_filters( 'canvas_push_bp_msg', $message, $users_list, $url, $title );

		/**
		* Allow to customize url of push notification for users.
		*
		* @since 3.2
		*
		* @param string $url URL.
		* @param string[] $users_list Array with canvas-username of each user.
		* @param string $title Title.
		* @param string $message Message text.
		*/
		$url = apply_filters( 'canvas_push_bp_url', $url, $users_list, $title, $message );

		$this->api->save_log(
			'canvas-notifications:send_to_users',
			var_export(
				array(
					'title'      => $title,
					'message'    => $message,
					'url'        => $url,
					'users_list' => $users_list,
					'users'      => $users,
				),
				true
			),
			''
		);
		if ( ! empty( $users ) ) {
			$data = array(
				'title' => $title,
				'msg'   => $message,
				'users' => $users,
			);
			if ( ! empty( $url ) ) {
				$data['payload'] = array( 'url' => $url );
			}
			return $this->api->send_batch_notification( $data, array(), is_array( $users_list ) ? $users_list : null );
		}
		return false;
	}

	public function registered_devices_count() {
		return $this->api->registered_devices_count();
	}

	/**
	 * Callback for auto push notifications
	 *
	 * @param string  $new_status
	 * @param string  $old_status
	 * @param WP_Post $post
	 */
	public static function post_published_notification( $new_status, $old_status, $post ) {

		if ( CanvasNotificationsDb::is_notified( $post->ID ) || ! self::check_post_notification_required( $post->ID ) ) {
			return;
		}

		$push_types = Canvas::get_option( 'push_post_types', 'post' );
		if ( strlen( $push_types ) > 0 ) {
			$push_types = explode( ',', $push_types );

			if ( 'future' === $new_status ) {
				return;
			}

			if ( $new_status == 'publish' && $old_status != 'publish' && in_array( $post->post_type, $push_types ) ) {  // only send push if it's a new publish
				$payload = array(
					'post_id' => strval( $post->ID ),
				);

				if ( Canvas::get_option( 'push_include_image', true ) ) {
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium_large' );
					if ( is_array( $image ) ) {
						$payload['featured_image'] = $image[0];
					}
				}
				$tagNames = self::get_post_tags( $post->ID );
				$data     = array(
					'platform' => array( 0, 1 ),
					'msg'      => strip_tags( trim( $post->post_title ) ),
					'sound'    => 'default',
					'badge'    => '+1',
					'payload'  => $payload,
					'tags'     => Canvas::get_option( 'push_auto_tags', array() ),
				);
				/**
				* Allow to customize message of automated push notification.
				*
				* @since 3.2
				*
				* @param string $message Message text.
				* @param int $post_id Post ID.
				*/
				$data['msg'] = apply_filters( 'canvas_push_auto_msg', $data['msg'], $post->ID );

				/**
				* Allow to customize post tag names of automated push notification.
				*
				* @since 3.2
				*
				* @param string[] $tagNames Array of Post tags.
				* @param int $post_id Post ID.
				*/
				$tagNames = apply_filters( 'canvas_push_auto_tagnames', $tagNames, $post->ID );

				if ( '' == $data['msg'] ) { // do not send notifications if the post doesn't have a title.
					return;
				}

				if ( Canvas::get_option( 'push_auto_use_cat', false ) ) {
					$data['tags'] = array_merge( $data['tags'], $tagNames );
				} else {
					$tagNames = array();
				}

				/**
				* Allow to customize tags of automated push notification.
				*
				* @since 3.2
				*
				* @param string[] $tags Array of tags.
				* @param int $post_id Post ID.
				*/
				$data['tags'] = apply_filters( 'canvas_push_auto_tags', $data['tags'], $post->ID );

				$push_api = self::get();
				$result   = $push_api->send_notifications( $data, $tagNames );
				if ( true === $result ) {
					if ( ! CanvasNotificationsDb::is_notified( $post->ID ) ) {
						// this post must be set as notified at the send_notification method.
						// Let label it as notified again if something went wrong before.
						CanvasNotificationsDb::set_post_id_as_notified( $post->ID );
					}
				}
			}
		}
	}

	private static function check_post_notification_required( $postId ) {
		$notification_categories = CanvasAdmin::push_notification_taxonomies_get();
		$notification_taxonomies = CanvasAdmin::push_notification_taxonomies_get( 'taxonomy' );

		if ( empty( $notification_categories ) && empty( $notification_taxonomies ) ) {
			return true;
		}

		if ( is_array( $notification_categories ) && count( $notification_categories ) > 0 ) {
			$post_categories = wp_get_post_categories( $postId );

			$found = false;
			if ( is_array( $post_categories ) && count( $post_categories ) > 0 ) {
				foreach ( $post_categories as $post_category_id ) {
					foreach ( $notification_categories as $notification_category ) {
						if ( $notification_category == $post_category_id ) {
							return true;
						}
					}
				}
			}
		}

		if ( is_array( $notification_taxonomies ) && count( $notification_taxonomies ) > 0 ) {
			$taxonomies = get_taxonomies( array( '_builtin' => false ), 'objects' );
			$tax_list   = array();
			foreach ( $taxonomies as $tax ) {
				if ( $tax->query_var ) {
					$tax_list[] = $tax->query_var;
				}
			}

			$post_tax = wp_get_object_terms( $postId, $tax_list );
			if ( ! is_wp_error( $post_tax ) && is_array( $post_tax ) && count( $post_tax ) > 0 ) {
				foreach ( $post_tax as $tax ) {
					if ( in_array( $tax->term_id, $notification_taxonomies ) ) {
						return true;
					}
				}
			}
		}
		return false;
	}

	/**
	 * Get id of tags for post
	 *
	 * @param int $postId
	 * @return array $tags
	 */
	private static function get_post_tag_ids( $postId ) {
		$post_categories = wp_get_post_categories( $postId );
		$tags            = array();
		foreach ( $post_categories as $c ) {
			$tags[] = $c;
		}

		return $tags;
	}

	/**
	 * Get slugs of tags for post
	 *
	 * @param int $postId
	 * @return array $tags
	 */
	private static function get_post_tags( $postId ) {
		$post_categories = wp_get_post_categories( $postId );
		$tags            = array();

		foreach ( $post_categories as $c ) {
			$cat = get_category( $c );
			if ( $cat instanceof \WP_Term ) {
				$tags[ $cat->slug ] = $cat->slug;
			}
		}

		return $tags;
	}

	/**
	 * Ajax callback. Check notification for duplicates. Show empty string if no dupes exists, 'true' otherwise
	 */
	public static function notification_check_duplicate() {
		self::check_is_action_allowed();
		$postId  = null;
		$url     = null;
		$android = null;
		$ios     = null;
		$data_id = strlen( $_POST['data_id'] ) > 0 ? $_POST['data_id'] : false;

		if ( $data_id ) {
			if ( 'custom' == $data_id ) {
				$postId = $_POST['post_id'];
			} elseif ( 'url' == $data_id ) {
				$url = $_POST['url'];
			} else {
				$postId = substr( $_POST['data_id'], 8 );
			}
		}

		switch ( $_POST['os'] ) {
			case 'all':
				$android = 'Y';
				$ios     = 'Y';
				break;
			case 'android':
				$android = 'Y';
				$ios     = 'N';
				break;
			case 'ios':
				$android = 'N';
				$ios     = 'Y';
				break;
		}
		$notifications = CanvasNotificationsDb::get_notification_by(
			array(
				'msg'     => trim( $_POST['msg'] ),
				'post_id' => $postId,
				'url'     => $url,
				'android' => $android,
				'ios'     => $ios,
			)
		);
		CanvasNotificationsView::show_true_false( count( $notifications ) > 0 );
		exit;
	}

	/**
	 * Ajax callback. Send manual notification. Show result as json string
	 */
	public static function notification_manual_send() {
		self::check_is_action_allowed();
		$result = 'There was an error sending this notification';
		if ( isset( $_POST['msg'] ) ) {
			$platform = array();
			switch ( $_POST['os'] ) {
				case 'all':
					$platform = array( 0, 1 );
					break;
				case 'android':
					$platform = array( 1 );
					break;
				case 'ios':
					$platform = array( 0 );
					break;
			}
			$tags            = array();
			$postId          = null;
			$url             = null;
			$category_as_tag = ! empty( $_POST['category_as_tag'] );
			$tags_list       = $_POST['tags_list'];

			if ( ! isset( $_POST['post_id'] ) ) {
				wp_send_json_error( __( 'Post ID missing.' ) );
			}

			$postId = (int)$_POST['post_id'];

			if ( isset( $_POST['notification_type'] ) ) {
				if ( 'post' === $_POST['notification_type'] ) {
					$url = get_permalink( $postId );
				} else if ( 'url' === $_POST['notification_type'] ) {
					$url = $_POST['url'];
					$postId = null;
				}
			}

			if ( $postId != null && $category_as_tag ) {
				$tags = self::get_post_tags( $postId );
			}
			// append manual tags to both lists
			if ( $tags_list ) {
				foreach ( explode( ',', $tags_list ) as $manual_tag ) {
					$manual_tag = trim( $manual_tag );
					if ( strlen( $manual_tag ) ) {
						$tags[] = $manual_tag;
					}
				}
			}
			$payload = array();
			if ( $postId !== null ) {

				$payload = array(
					'post_id' => $postId,
				);

				if ( 'on' === $_POST['use_post_featured_image'] ) { 
					$image   = wp_get_attachment_image_src( get_post_thumbnail_id( $postId ), 'single-post-thumbnail' );
					if ( is_array( $image ) ) {
						$payload['featured_image'] = $image[0];
					}
				} else {
					$image_url = isset( $_POST['featured_image_url'] ) ? $_POST['featured_image_url'] : '';
					$payload['featured_image'] = $image_url;
				}
			} else {
				$image_url = isset( $_POST['featured_image_url'] ) ? $_POST['featured_image_url'] : '';
				$payload['featured_image'] = $image_url;
			}


			$payload['url'] = $url;


			$data = array(
				'platform' => $platform,
				'msg'      => trim( $_POST['msg'] ),
				'sound'    => 'default',
				'badge'    => '+1',
				'payload'  => $payload,
				'tags'     => $tags,
			);

			$data['title'] = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';

			/**
			* Allow to customize message of manual push notification.
			*
			* @since 3.2
			*
			* @param string $message Message text.
			* @param null|int|string $post_id Post ID or null.
			*/
			$data['msg'] = apply_filters( 'canvas_push_manual_msg', $data['msg'], $postId );

			/**
			* Allow to customize tags of manual push notification.
			*
			* @since 3.2
			*
			* @param string[] $tags Message tags.
			* @param null|int|string $post_id Post ID or null.
			*/
			$data['tags'] = apply_filters( 'canvas_push_manual_tags', $data['tags'], $postId );

			$push_api = self::get();
			$result   = $push_api->send_notifications( $data, $tags );
		}

		CanvasNotificationsView::show_json( $result );
		exit;
	}

	/**
	 * Show history chart
	 *
	 * @param array $notifications
	 */
	private static function notification_chart( $notifications ) {
		CanvasNotificationsView::show_chart( $notifications );
	}

	/**
	 * Ajax callback. Show history block: chart + table
	 */
	public static function notification_history() {
		self::check_is_action_allowed();
		$notifications = CanvasNotificationsDb::get_last_notifications( 100 );
		self::notification_chart( $notifications );
		CanvasNotificationsView::show_history( $notifications );
		exit;
	}

	/**
	 * Ajax callback. Show attach select content
	 */
	public static function attachment_content() {
		self::check_is_action_allowed( '<option>Not allowed</option>' );
		$posts = get_posts(
			array(
				'posts_per_page' => 10,
				'orderby'        => 'post_date',
				'order'          => 'DESC',
				'post_type'      => 'post',
			)
		);
		$pages = get_pages(
			array(
				'sort_order'  => 'ASC',
				'sort_column' => 'post_title',
				'post_type'   => 'page',
				'post_status' => 'publish',
			)
		);
		CanvasNotificationsView::show_attachment( $posts, $pages );
		exit;
	}

	/**
	 * Save message at fields to db
	 *
	 * @param array $fields
	 */
	public static function save_sent_message( $fields ) {
		CanvasNotificationsDb::insert_to_db( $fields );
	}

	private function user_id_to_api_id( $users_list ) {
		$result = array();
		if ( true === $users_list ) {
			// all users
			return $this->get_all_api_users();
		} else {
			foreach ( $users_list as $user_id ) {
				if ( 'user_id' === Canvas::get_option( 'user_profile' ) ) {
					$id = get_user_option( 'canvas-username', $user_id );
					if (
						! empty( $id ) &&
						/**
						* Custom filter for unsubscribed users.
						*
						* @since 3.2
						*
						* @param bool $is_unsubscribed True if user unsubscribed, default false.
						* @param string $canvas_username Canvas-username string.
						*/
						! apply_filters( 'canvas-user-unsubscribed', false, $user_id )
					) {
							$result[] = $id;
					}
				} else {
					$user     = get_user_by( 'id', $user_id );
					$result[] = $user->get( 'user_email' );
				}
			}
		}
		return $result;
	}

	private function get_all_api_users() {
		$result = array();
		$users  = get_users(
			array(
				'number' => 1000000,
				'fields' => array( 'ID' ),
			)
		);
		foreach ( $users as $user ) {
			if ( 'user_id' === Canvas::get_option( 'user_profile' ) ) {
				$id = get_user_option( 'canvas-username', $user->ID );
				if (
					! empty( $id ) &&
					/**
					* Custom filter for unsubscribed users.
					*
					* @since 3.2
					*
					* @param bool $is_unsubscribed True if user unsubscribed, default false.
					* @param string $canvas_username Canvas-username string.
					*/
					! apply_filters( 'canvas-user-unsubscribed', false, $id )
				) {
					$result[] = $id;
				}
			} else {
				$user     = get_user_by( 'id', $user->ID );
				$result[] = $user->get( 'user_email' );
			}
		}
		return $result;
	}

	/**
	 * Save BP event to log
	 *
	 * @param string $source
	 * @param mixed  $parameters
	 */
	public function save_bp_log( $source, $parameters ) {
		$this->api->save_log( "bp-log-$source", $parameters, '' );
	}

	/**
	 * Save bbPress event to log
	 *
	 * @param string $source
	 * @param mixed  $parameters
	 */
	public function save_bb_log( $source, $parameters ) {
		$this->api->save_log( "bbPress-log-$source", $parameters, '' );
	}

	/**
	 * Save PeepSo event to log
	 *
	 * @param string $source
	 * @param mixed  $parameters
	 */
	public function save_ps_log( $source, $parameters ) {
		$this->api->save_log( "ps-log-$source", $parameters, '' );
	}

	/**
	 * Save LD event to log
	 *
	 * @param string $source
	 * @param mixed  $parameters
	 */
	public function save_ld_log( $source, $parameters ) {
		$this->api->save_log( "ld-log-$source", $parameters, '' );
	}
}
