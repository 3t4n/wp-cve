<?php
/**
 * A controller handling incoming requests regarding comments on UpStream items.
 *
 * @package UpStream
 */

namespace UpStream;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use UpStream\Traits\Singleton;

/**
 * This class will act as a controller handling incoming requests regarding comments on UpStream items.
 *
 * @since   1.13.0
 */
class Comments {
	use Singleton;

	/**
	 * The current full namespace.
	 *
	 * @since   1.13.0
	 * @access  private
	 * @static
	 *
	 * @var     string $namespace
	 */
	private static $namespace;

	/**
	 * Class constructor.
	 *
	 * @since   1.13.0
	 */
	public function __construct() {
		self::$namespace = get_class(
			empty( self::$instance )
				? $this
				: self::$instance
		);

		$this->attach_hooks();

		self::remove_comment_type();
	}

	/**
	 * Attach all relevant actions to handle comments.
	 *
	 * @since   1.13.0
	 * @access  private
	 */
	private function attach_hooks() {
		add_action( 'wp_ajax_upstream:project.add_comment', array( self::$namespace, 'store_comment' ) );
		add_action( 'wp_ajax_upstream:project.add_comment_reply', array( self::$namespace, 'store_comment_reply' ) );
		add_action( 'wp_ajax_upstream:project.trash_comment', array( self::$namespace, 'trash_comment' ) );
		add_action( 'wp_ajax_upstream:project.unapprove_comment', array( self::$namespace, 'unapprove_comment' ) );
		add_action( 'wp_ajax_upstream:project.approve_comment', array( self::$namespace, 'approve_comment' ) );
		add_action( 'wp_ajax_upstream:project.fetch_comments', array( self::$namespace, 'fetch_comments' ) );

		add_filter( 'comment_notification_subject', array( self::$namespace, 'define_notification_header' ), 10, 2 );
		add_filter( 'comment_notification_recipients', array( self::$namespace, 'define_notification_recipients' ), 10, 2 );
		add_filter( 'comment_notification_text', array( self::$namespace, 'add_item_title_to_notification' ), 10, 2 );

		add_filter( 'upstream_allowed_tags_in_comments', array( self::$namespace, 'filter_allowed_tags' ) );
		add_filter(
			'comment_notification_headers',
			array( self::$namespace, 'filter_comment_notification_headers' ),
			10,
			2
		);

		add_filter( 'comment_notification_text', array( self::$namespace, 'filter_comment_notification_text' ), 10, 2 );
	}

	/**
	 * Empties the comment_type="comment" column from UpStream comments.
	 *
	 * @since   1.16.3
	 * @static
	 */
	public static function remove_comment_type() {
		$did_remove_comments_type = (bool) get_option( 'upstream:remove_comments_type' );

		if ( ! $did_remove_comments_type ) {
			global $wpdb;

			$wpdb->query(
				sprintf(
					'UPDATE `%s` AS `comment`
						LEFT JOIN `%s` AS `post`
							ON `post`.`ID` = `comment`.`comment_post_ID`
						SET `comment_type` = ""
						WHERE `comment_type` = "comment"
						AND `post_type` = "project"',
					$wpdb->prefix . 'comments',
					$wpdb->prefix . 'posts'
				)
			);

			update_option( 'upstream:remove_comments_type', 1 );
		}
	}

	/**
	 * Filter allowed tags.
	 *
	 * @param array $allowed_tags Allowed tags.
	 *
	 * @return array
	 */
	public static function filter_allowed_tags( $allowed_tags ) {
		global $allowedtags;

		// Add default allowed tags.
		$allowed_tags = array_merge( $allowed_tags, $allowedtags );

		// Add basic tags.
		if ( ! array_key_exists( 'p', $allowed_tags ) ) {
			$allowed_tags['p'] = array(
				'class' => true,
				'id'    => true,
			);
		}

		if ( ! array_key_exists( 'br', $allowed_tags ) ) {
			$allowed_tags['br'] = array();
		}

		if ( ! array_key_exists( 'strong', $allowed_tags ) ) {
			$allowed_tags['strong'] = array(
				'class' => true,
				'id'    => true,
			);
		}

		if ( ! array_key_exists( 'em', $allowed_tags ) ) {
			$allowed_tags['em'] = array(
				'class' => true,
				'id'    => true,
			);
		}

		if ( ! array_key_exists( 'span', $allowed_tags ) ) {
			$allowed_tags['span'] = array(
				'class' => true,
				'id'    => true,
				'style' => true,
			);
		}

		if ( ! array_key_exists( 'del', $allowed_tags ) ) {
			$allowed_tags['del'] = array(
				'class' => true,
				'id'    => true,
			);
		}

		if ( ! array_key_exists( 'ul', $allowed_tags ) ) {
			$allowed_tags['ul'] = array(
				'class' => true,
				'id'    => true,
			);
		}

		if ( ! array_key_exists( 'ol', $allowed_tags ) ) {
			$allowed_tags['ol'] = array(
				'class' => true,
				'id'    => true,
			);
		}

		if ( ! array_key_exists( 'li', $allowed_tags ) ) {
			$allowed_tags['li'] = array(
				'class' => true,
				'id'    => true,
			);
		}

		if ( ! array_key_exists( 'a', $allowed_tags ) ) {
			$allowed_tags['a'] = array(
				'class'   => true,
				'id'      => true,
				'href'    => true,
				'charset' => true,
				'name'    => true,
				'rel'     => true,
				'target'  => true,
				'type'    => true,
			);
		} else {
			$allowed_tags['a']['class']   = true;
			$allowed_tags['a']['id']      = true;
			$allowed_tags['a']['href']    = true;
			$allowed_tags['a']['charset'] = true;
			$allowed_tags['a']['name']    = true;
			$allowed_tags['a']['rel']     = true;
			$allowed_tags['a']['target']  = true;
			$allowed_tags['a']['type']    = true;
		}

		// If the current can't post images, we return current supported tags.
		if ( ! current_user_can( 'upstream_comment_images' ) ) {
			return $allowed_tags;
		}

		// The user can post images, so let's allow the img tag.
		if ( ! is_array( $allowed_tags ) ) {
			$allowed_tags = array();
		}

		$allowed_tags['img'] = array(
			'class'  => true,
			'src'    => true,
			'alt'    => true,
			'width'  => true,
			'height' => true,
		);

		return $allowed_tags;
	}

	/**
	 * AJAX endpoint that stores a new comment.
	 *
	 * @throws \Exception Set the error message.
	 * @since   1.13.0
	 * @static
	 */
	public static function store_comment() {
		header( 'Content-Type: application/json' );

		$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();
		$response  = array(
			'success' => false,
			'error'   => null,
		);

		try {
			// Check if the request payload is potentially invalid.
			if (
				! defined( 'DOING_AJAX' )
				|| ! DOING_AJAX
				|| empty( $post_data )
				|| ! isset( $post_data['nonce'] )
				|| ! isset( $post_data['project_id'] )
				|| ! isset( $post_data['item_type'] )
				|| ! self::is_item_type_valid( sanitize_text_field( $post_data['item_type'] ) )
				|| ! isset( $post_data['content'] )
			) {
				throw new \Exception( __( 'Invalid request.', 'upstream' ) );
			}

			// Prepare data to verify nonce.
			$comment_target_item_type = strtolower( sanitize_text_field( $post_data['item_type'] ) );
			if ( 'project' !== $comment_target_item_type ) {
				if (
					! isset( $post_data['item_id'] )
					|| empty( $post_data['item_id'] )
				) {
					throw new \Exception( __( 'Invalid item.', 'upstream' ) );
				}

				// non-numeric id.
				$item_id = sanitize_text_field( $post_data['item_id'] );

				$nonce_identifier = 'upstream:project.' . $comment_target_item_type . 's.add_comment';
			} else {
				$item_id          = absint( $post_data['project_id'] );
				$nonce_identifier = 'upstream:project.add_comment';
			}

			// Verify nonce.
			if ( ! check_ajax_referer( $nonce_identifier, 'nonce', false ) ) {
				throw new \Exception( __( 'Invalid nonce.', 'upstream' ) );
			}

			// Check if the project exists.
			$project_id = absint( $post_data['project_id'] );
			if ( $project_id <= 0 ) {
				throw new \Exception( __( 'Invalid Project.', 'upstream' ) );
			}

			// Check if commenting is disabled on the given project.
			if ( upstream_are_comments_disabled( $project_id ) ) {
				throw new \Exception( __( 'Commenting is disabled on this project.', 'upstream' ) );
			}

			// Check if the user has enough permissions to insert a new comment.
			if ( ! upstream_can_access_field( 'publish_project_discussion', $comment_target_item_type, $item_id, UPSTREAM_ITEM_TYPE_PROJECT, $project_id, 'comments', UPSTREAM_PERMISSIONS_ACTION_EDIT, true ) ) {
				throw new \Exception( __( "You're not allowed to do this.", 'upstream' ) );
			}

			$user_id = get_current_user_id();

			$comment_content = stripslashes( wp_kses_post( $post_data['content'] ) );

			$item_title = isset( $post_data['item_title'] ) ? sanitize_text_field( $post_data['item_title'] ) : '';

			$comment = new Comment( $comment_content, $project_id, $user_id );
			$server  = isset( $_SERVER ) ? wp_unslash( $_SERVER ) : array();

			$comment->created_by->ip    = preg_replace( '/[^0-9a-fA-F:., ]/', '', sanitize_text_field( $server['REMOTE_ADDR'] ) );
			$comment->created_by->agent = isset( $server['HTTP_USER_AGENT'] ) ? sanitize_text_field( $server['HTTP_USER_AGENT'] ) : null;

			$comment->save();

			update_comment_meta( $comment->id, 'type', $comment_target_item_type );

			if ( 'project' !== $comment_target_item_type ) {
				update_comment_meta( $comment->id, 'id', $item_id );
				// We store the item title here because of the project's data structure.
				// It is faster to retrieve from metadata then seek item by item from a project.
				update_comment_meta( $comment->id, 'title', $item_title );
			}

			wp_new_comment_notify_moderator( $comment->id );
			wp_notify_postauthor( $comment->id );

			$use_admin_layout = ! isset( $post_data['teeny'] ) ? true : boolval( $post_data['teeny'] ) === false;

			$response['comment_html'] = stripslashes( $comment->render( true, $use_admin_layout ) );

			$response['success'] = true;
		} catch ( \Exception $e ) {
			$response['error'] = $e->getMessage();
		}

		wp_send_json( $response );
	}


	/**
	 * Check if the item type is valid.
	 *
	 * @since   1.13.0
	 * @static
	 *
	 * @param   string $item_type Value to be validated.
	 * @throws  \Exception Set error message.
	 *
	 * @return  bool
	 */
	public static function is_item_type_valid( $item_type ) {
		$item_types = array( 'project', 'milestone', 'task', 'bug', 'file' );

		return in_array( $item_type, $item_types );
	}

	/**
	 * AJAX endpoint that adds a new comment reply.
	 *
	 * @throws \Exception Set the error message.
	 * @since   1.13.0
	 * @static
	 */
	public static function store_comment_reply() {
		header( 'Content-Type: application/json' );

		$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();
		$server    = isset( $_SERVER ) ? wp_unslash( $_SERVER ) : array();
		$response  = array(
			'success' => false,
			'error'   => null,
		);

		try {
			// Check if the request payload is potentially invalid.
			if (
				! defined( 'DOING_AJAX' )
				|| ! DOING_AJAX
				|| empty( $post_data )
				|| ! isset( $post_data['nonce'] )
				|| ! isset( $post_data['project_id'] )
				|| ! isset( $post_data['item_type'] )
				|| ! self::is_item_type_valid( sanitize_text_field( $post_data['item_type'] ) )
				|| ! isset( $post_data['content'] )
				|| ! isset( $post_data['parent_id'] )
				|| ! is_numeric( sanitize_text_field( $post_data['parent_id'] ) )
				|| ! check_ajax_referer( 'upstream:project.add_comment_reply:' . sanitize_text_field( $post_data['parent_id'] ), 'nonce', false )
			) {
				throw new \Exception( __( 'Invalid request.', 'upstream' ) );
			}

			// could be alnum ID.
			$item_id = sanitize_text_field( $post_data['item_id'] );

			// Check if the project exists.
			$project_id = absint( $post_data['project_id'] );
			if ( $project_id <= 0 ) {
				throw new \Exception( __( 'Invalid Project.', 'upstream' ) );
			}

			$comment_target_item_type = strtolower( sanitize_text_field( $post_data['item_type'] ) );
			if ( 'project' !== $comment_target_item_type ) {
				if (
					! isset( $post_data['item_id'] )
					|| empty( $post_data['item_id'] )
				) {
					throw new \Exception( __( 'Invalid request.', 'upstream' ) );
				}
			} else {
				$item_id = $project_id;
			}

			// Check if the user has enough permissions to insert a new comment.
			if ( ! upstream_can_access_field( 'publish_project_discussion', $comment_target_item_type, $item_id, UPSTREAM_ITEM_TYPE_PROJECT, $project_id, 'comments', UPSTREAM_PERMISSIONS_ACTION_EDIT, true ) ) {
				throw new \Exception( __( "You're not allowed to do this.", 'upstream' ) );
			}

			// Check if commenting is disabled on the given project.
			if ( upstream_are_comments_disabled( $project_id ) ) {
				throw new \Exception( __( 'Commenting is disabled on this project.', 'upstream' ) );
			}

			$user_id = get_current_user_id();

			$comment                    = new Comment( stripslashes( wp_kses_post( $post_data['content'] ) ), $project_id, $user_id );
			$comment->parent_id         = absint( $post_data['parent_id'] );
			$comment->created_by->ip    = preg_replace( '/[^0-9a-fA-F:., ]/', '', sanitize_text_field( $server['REMOTE_ADDR'] ) );
			$comment->created_by->agent = isset( $server['HTTP_USER_AGENT'] ) ? sanitize_textarea_field( $server['HTTP_USER_AGENT'] ) : null;

			$comment->save();

			update_comment_meta( $comment->id, 'type', $comment_target_item_type );

			if ( 'project' !== $comment_target_item_type ) {
				update_comment_meta( $comment->id, 'id', sanitize_text_field( $post_data['item_id'] ) );
			}

			$use_admin_layout = ! isset( $post_data['teeny'] ) ? true : boolval( $post_data['teeny'] ) === false;

			$parent = get_comment( $comment->parent_id );

			$comments_cache = array(
				$parent->comment_ID => json_decode(
					json_encode(
						array(
							'created_by' => array(
								'name' => $parent->comment_author,
							),
						)
					)
				),
			);

			$response['comment_html'] = stripslashes( $comment->render( true, $use_admin_layout, $comments_cache ) );

			wp_new_comment_notify_moderator( $comment->id );
			wp_notify_postauthor( $comment->id );

			$response['success'] = true;
		} catch ( \Exception $e ) {
			$response['error'] = $e->getMessage();
		}

		wp_send_json( $response );
	}

	/**
	 * AJAX endpoint that trashes a comment.
	 *
	 * @throws \Exception Set the error message.
	 * @since   1.13.0
	 * @static
	 */
	public static function trash_comment() {
		header( 'Content-Type: application/json' );

		$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();
		$response  = array(
			'success' => false,
			'error'   => null,
		);

		try {
			// Check if the request payload is potentially invalid.
			if (
				! defined( 'DOING_AJAX' )
				|| ! DOING_AJAX
				|| empty( $post_data )
				|| ! isset( $post_data['nonce'] )
				|| ! isset( $post_data['project_id'] )
				|| ! isset( $post_data['comment_id'] )
				|| ! check_ajax_referer( 'upstream:project.trash_comment:' . sanitize_textarea_field( $post_data['comment_id'] ), 'nonce', false )
			) {
				throw new \Exception( __( 'Invalid request.', 'upstream' ) );
			}

			// Check if the project exists.
			$project_id = absint( $post_data['project_id'] );
			if ( $project_id <= 0 ) {
				throw new \Exception( __( 'Invalid Project.', 'upstream' ) );
			}

			// Check if the Discussion/Comments section is disabled for the current project.
			if ( upstream_are_comments_disabled( $project_id ) ) {
				throw new \Exception( __( 'Comments are disabled for this project.', 'upstream' ) );
			}

			// Check if the parent comment exists.
			$comment_id = absint( $post_data['comment_id'] );
			$comment    = get_comment( $comment_id );

			if ( empty( $comment )
				// Check if the comment belongs to that project.
				|| (
					isset( $comment->comment_post_ID )
					&& (int) $comment->comment_post_ID !== $project_id
				)
			) {
				throw new \Exception( _x( 'Comment not found.', 'Removing a comment in projects', 'upstream' ) );
			}

			$user_id = (int) get_current_user_id();

			if ( ! upstream_admin_permissions( 'delete_project_discussion' )
					&& ! current_user_can( 'moderate_comments' )
					&& (int) $comment->user_id !== $user_id
			) {
				throw new \Exception( __( "You're not allowed to do this.", 'upstream' ) );
			}

			$success = wp_trash_comment( $comment );
			if ( ! $success ) {
				throw new \Exception( __( "It wasn't possible to delete this comment.", 'upstream' ) );
			}

			$response['success'] = true;
		} catch ( \Exception $e ) {
			$response['error'] = $e->getMessage();
		}

		wp_send_json( $response );
	}

	/**
	 * AJAX endpoint that unapproves a comment.
	 *
	 * @since   1.13.0
	 * @static
	 */
	public static function unapprove_comment() {
		header( 'Content-Type: application/json' );

		$post_data  = isset( $_POST ) ? wp_unslash( $_POST ) : array();
		$comment_id = isset( $post_data['comment_id'] ) ? absint( $post_data['comment_id'] ) : 0;

		check_ajax_referer( 'upstream:project.unapprove_comment:' . $comment_id, 'nonce' );

		$response = array(
			'success' => false,
			'error'   => null,
		);

		try {
			$comment = self::toggle_comment_approval_status( $comment_id, false );

			$comments = array();
			if ( $comment->parent_id > 0 ) {
				$parent_comment = get_comment( $comment->parent_id );
				if ( is_numeric( $parent_comment->comment_approved ) ) {
					if ( (bool) $parent_comment->comment_approved ) {
						$comments = array(
							$comment->parent_id => json_decode(
								json_encode(
									array(
										'created_by' => array(
											'name' => $parent_comment->comment_author,
										),
									)
								)
							),
						);
					} else {
						$user                        = wp_get_current_user();
						$user_has_admin_capabilities = upstream_is_user_either_manager_or_admin( $user );
						$user_can_moderate_comments  = ! $user_has_admin_capabilities ? user_can(
							$user,
							'moderate_comments'
						) : true;

						if ( $user_can_moderate_comments ) {
							$comments = array(
								$comment->parent_id => json_decode(
									json_encode(
										array(
											'created_by' => array(
												'name' => $parent_comment->comment_author,
											),
										)
									)
								),
							);
						}
					}
				}
				unset( $parent_comment );
			}

			$use_admin_layout = ! isset( $post_data['teeny'] ) ? true : boolval( $post_data['teeny'] ) === false;

			$response['comment_html'] = $comment->render( true, $use_admin_layout, $comments );

			wp_new_comment_notify_moderator( $comment->id );

			$response['success'] = true;
		} catch ( \Exception $e ) {
			$response['error'] = $e->getMessage();
		}

		wp_send_json( $response );
	}

	/**
	 * Either approves/unapproves a given comment.
	 * This method is called by the correspondent AJAX endpoints.
	 *
	 * @since   1.13.0
	 * @access  private
	 * @static
	 *
	 * @throws  \Exception When something went wrong or failed on validations.
	 *
	 * @param   int  $comment_id        Comment ID being edited.
	 * @param   bool $is_approved Either the comment will be approved or not.
	 */
	private static function toggle_comment_approval_status( $comment_id, $is_approved ) {
		$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();

		// Check if the request payload is potentially invalid.
		if (
			! defined( 'DOING_AJAX' )
			|| ! DOING_AJAX
			|| empty( $post_data )
			|| ! isset( $post_data['nonce'] )
			|| ! isset( $post_data['project_id'] )
			|| ! isset( $post_data['comment_id'] )
			|| ! check_ajax_referer(
				'upstream:project.' . ( $is_approved ? 'approve_comment' : 'unapprove_comment' ) . ':' . sanitize_textarea_field( $post_data['comment_id'] ),
				'nonce',
				false
			)
		) {
			throw new \Exception( __( 'Invalid request.', 'upstream' ) );
		}

		// Check if the user has enough permissions to do this.
		if ( ! current_user_can( 'moderate_comments' ) ) {
			throw new \Exception( __( "You're not allowed to do this.", 'upstream' ) );
		}

		// Check if the project potentially exists.
		$project_id = absint( $post_data['project_id'] );
		if ( $project_id <= 0 ) {
			// translators: %s: parameter name.
			throw new \Exception( sprintf( __( 'Invalid "%s" parameter.', 'upstream' ), 'project_id' ) );
		}

		// Check if the Discussion/Comments section is disabled for the current project.
		if ( upstream_are_comments_disabled( $project_id ) ) {
			throw new \Exception( __( 'Comments are disabled for this project.', 'upstream' ) );
		}

		$cid     = isset( $post_data['comment_id'] ) ? absint( $post_data['comment_id'] ) : 0;
		$comment = Comment::load( $cid );
		if ( ! ( $comment instanceof Comment ) ) {
			throw new \Exception( __( 'Comment not found.', 'upstream' ) );
		}

		$success = (bool) $is_approved ? $comment->approve() : $comment->unapprove();
		if ( ! $success ) {
			throw new \Exception( __( 'Unable to save the data into database.', 'upstream' ) );
		}

		return $comment;
	}

	/**
	 * AJAX endpoint that approves a comment.
	 *
	 * @since   1.13.0
	 * @static
	 */
	public static function approve_comment() {
		header( 'Content-Type: application/json' );

		$post_data  = isset( $_POST ) ? wp_unslash( $_POST ) : array();
		$comment_id = isset( $post_data['comment_id'] ) ? absint( $post_data['comment_id'] ) : 0;

		check_ajax_referer( 'upstream:project.approve_comment:' . $comment_id, 'nonce' );

		$response = array(
			'success' => false,
			'error'   => null,
		);

		try {
			$comment_id = isset( $post_data['comment_id'] ) ? absint( $post_data['comment_id'] ) : 0;
			$comment    = self::toggle_comment_approval_status( $comment_id, true );

			$comments = array();
			if ( $comment->parent_id > 0 ) {
				$parent_comment = get_comment( $comment->parent_id );
				if ( is_numeric( $parent_comment->comment_approved ) ) {
					if ( (bool) $parent_comment->comment_approved ) {
						$comments = array(
							$comment->parent_id => json_decode(
								wp_json_encode(
									array(
										'created_by' => array(
											'name' => $parent_comment->comment_author,
										),
									)
								)
							),
						);
					} else {
						$user                        = wp_get_current_user();
						$user_has_admin_capabilities = upstream_is_user_either_manager_or_admin( $user );
						$user_can_moderate_comments  = ! $user_has_admin_capabilities ? user_can(
							$user,
							'moderate_comments'
						) : true;

						if ( $user_can_moderate_comments ) {
							$comments = array(
								$comment->parent_id => json_decode(
									json_encode(
										array(
											'created_by' => array(
												'name' => $parent_comment->comment_author,
											),
										)
									)
								),
							);
						}
					}
				}
				unset( $parent_comment );
			}

			$use_admin_layout = ! isset( $post_data['teeny'] ) ? true : boolval( $post_data['teeny'] ) === false;

			$response['comment_html'] = $comment->render( true, $use_admin_layout, $comments );

			$response['success'] = true;
		} catch ( \Exception $e ) {
			$response['error'] = $e->getMessage();
		}

		wp_send_json( $response );
	}

	/**
	 * AJAX endpoint that fetches all comments from a given item/project.
	 *
	 * @throws  \Exception When something went wrong or failed on validations.
	 * @since   1.13.0
	 * @static
	 */
	public static function fetch_comments() {
		header( 'Content-Type: application/json' );

		$get_data = isset( $_GET ) ? wp_unslash( $_GET ) : array();
		$response = array(
			'success' => false,
			'data'    => array(),
			'error'   => null,
		);

		try {
			// Check if the request payload is potentially invalid.
			if (
				! defined( 'DOING_AJAX' )
				|| ! DOING_AJAX
				|| empty( $get_data )
				|| ! isset( $get_data['nonce'] )
				|| ! isset( $get_data['project_id'] )
				|| ! isset( $get_data['item_type'] )
				|| ! self::is_item_type_valid( sanitize_textarea_field( $get_data['item_type'] ) )
			) {
				throw new \Exception( __( 'Invalid request.', 'upstream' ) );
			}

			// Check if the project potentially exists.
			$project_id = absint( $get_data['project_id'] );
			if ( $project_id <= 0 ) {
				throw new \Exception( __( 'Invalid Project.', 'upstream' ) );
			}

			// Prepare data to verify nonce.
			$comment_target_item_type = strtolower( sanitize_text_field( $get_data['item_type'] ) );
			$item_id                  = null;
			if ( 'project' !== $comment_target_item_type ) {
				if (
					! isset( $get_data['item_id'] )
					|| empty( $get_data['item_id'] )
				) {
					throw new \Exception( __( 'Invalid request.', 'upstream' ) );
				}

				// non-numeric id.
				$item_id = sanitize_textarea_field( $get_data['item_id'] );

				$nonce_identifier = 'upstream:project.' . $comment_target_item_type . 's.fetch_comments';
			} else {
				$nonce_identifier = 'upstream:project.fetch_comments';
			}

			// Verify nonce.
			if ( ! check_ajax_referer( $nonce_identifier, 'nonce', false ) ) {
				throw new \Exception( __( 'Invalid nonce.', 'upstream' ) );
			}

			// Check if commenting is disabled on the given project.
			if ( upstream_are_comments_disabled( $project_id ) ) {
				throw new \Exception( __( 'Commenting is disabled on this project.', 'upstream' ) );
			}

			$use_admin_layout = ! isset( $get_data['teeny'] ) ? true : boolval( $get_data['teeny'] ) === false;

			$comments_cache = static::get_comments( $project_id, $comment_target_item_type, $item_id );

			foreach ( $comments_cache as $comment ) {
				if ( 0 === $comment->parent_id ) {
					ob_start();
					if ( $use_admin_layout ) {
						upstream_admin_display_message_item( $comment, array() );
					} else {
						upstream_display_message_item( $comment, array() );
					}

					$response['data'][] = trim( ob_get_contents() );
					ob_end_clean();
				}
			}

			$response['success'] = true;
		} catch ( \Exception $e ) {
			$response['error'] = $e->getMessage();
		}

		wp_send_json( $response );
	}

	/**
	 * Get comments.
	 *
	 * @param int    $project_id Project ID.
	 * @param string $item_type Item type.
	 * @param int    $item_id Item ID.
	 *
	 * @return array
	 */
	public static function get_comments( $project_id, $item_type, $item_id = null ) {
		$comments_cache = array();
		$users_cache    = array();
		$users_rowset   = get_users(
			array(
				'fields' => array(
					'ID',
					'display_name',
				),
			)
		);
		foreach ( $users_rowset as $user_row ) {
			$user_row->ID *= 1;

			$users_cache[ $user_row->ID ] = (object) array(
				'id'     => $user_row->ID,
				'name'   => $user_row->display_name,
				'avatar' => get_userAvatarURL( $user_row->ID ),
			);
		}
		unset( $user_row, $users_rowset );

		if ( 'project' === $item_type ) {
			$item_id = $project_id;
		}

		$date_format                 = get_option( 'date_format' );
		$time_format                 = get_option( 'time_format' );
		$the_date_time_format        = $date_format . ' ' . $time_format;
		$current_timestamp           = time();
		$user                        = wp_get_current_user();
		$user_has_admin_capabilities = upstream_is_user_either_manager_or_admin( $user );
		$user_can_reply              = ! $user_has_admin_capabilities ? user_can(
			$user,
			'publish_project_discussion'
		) : true;

		$user_can_reply = upstream_override_access_field( $user_can_reply, $item_type, $item_id, UPSTREAM_ITEM_TYPE_PROJECT, $project_id, 'comments', UPSTREAM_PERMISSIONS_ACTION_EDIT );

		$user_can_moderate = ! $user_has_admin_capabilities ? user_can( $user, 'moderate_comments' ) : true;
		$user_can_delete   = ! $user_has_admin_capabilities ? $user_can_moderate || user_can(
			$user,
			'delete_project_discussion'
		) : true;

		$user_can_delete = upstream_override_access_field( $user_can_delete, $item_type, $item_id, UPSTREAM_ITEM_TYPE_PROJECT, $project_id, 'comments', UPSTREAM_PERMISSIONS_ACTION_DELETE );

		$comments_statuses = array( 'approve' );
		if ( $user_has_admin_capabilities || $user_can_moderate ) {
			$comments_statuses[] = 'hold';
		}

		$items_rowset = (array) get_post_meta(
			$project_id,
			'_upstream_project_' . $item_type . 's',
			true
		);

		if ( count( $items_rowset ) > 0 ) {
			foreach ( $items_rowset as $row ) {
				if ( empty( $row ) ) {
					continue;
				}

				if ( ! empty( $item_id ) ) {
					if ( $item_id != $row['id'] ) {
						continue;
					}
				}

				$comments = (array) get_comments(
					array(
						'post_id'    => $project_id,
						'status'     => $comments_statuses,
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key'   => 'type',
								'value' => $item_type,
							),
							array(
								'key'   => 'id',
								'value' => $row['id'],
							),
						),
					)
				);

				if ( count( $comments ) > 0 ) {
					foreach ( $comments as $comment ) {
						$author = $users_cache[ (int) $comment->user_id ];

						$date = \DateTime::createFromFormat( 'Y-m-d H:i:s', $comment->comment_date_gmt );

						$comment_data = json_decode(
							json_encode(
								array(
									'id'               => (int) $comment->comment_ID,
									'parent_id'        => (int) $comment->comment_parent,
									'content'          => $comment->comment_content,
									'state'            => $comment->comment_approved,
									'created_by'       => $author,
									'created_at'       => array(
										'localized' => '',
										'humanized' => sprintf(
											// translators: %s: human-readable time difference.
											_x( '%s ago', '%s = human-readable time difference', 'upstream' ),
											human_time_diff( $date->getTimestamp(), $current_timestamp )
										),
									),
									'current_user_cap' => array(
										'can_reply'    => $user_can_reply,
										'can_moderate' => $user_can_moderate,
										'can_delete'   => $user_can_delete || $author->id === $user->ID,
									),
									'replies'          => array(),
								)
							)
						);

						$comment_data->created_at->localized = $date->format( $the_date_time_format );

						$comments_cache[ $comment_data->id ] = $comment_data;
					}

					foreach ( $comments_cache as $comment ) {
						if ( $comment->parent_id > 0 ) {
							if ( isset( $comments_cache[ $comment->parent_id ] ) ) {
								$comments_cache[ $comment->parent_id ]->replies[] = $comment;
							} else {
								unset( $comments_cache[ $comment->id ] );
							}
						}
					}
				}
			}
		}

		return $comments_cache;
	}

	/**
	 * Set additional notification recipients as needed for newly added comments.
	 *
	 * @since   1.15.0
	 * @static
	 *
	 * @param   array $recipients Recipients list.
	 * @param   int   $comment_id The new comment ID.
	 *
	 * @return  array
	 */
	public static function define_notification_recipients( $recipients, $comment_id ) {
		$should_send = upstream_send_notifications_for_new_comments();
		if ( ! $should_send ) {
			return array();
		}

		// 2 minutes.
		$transient_expiration = 60 * 2;

		$comment = get_comment( $comment_id );
		$comment = (object) array(
			'id'         => (int) $comment->comment_ID,
			'project_id' => (int) $comment->comment_post_ID,
			'parent'     => (int) $comment->comment_parent,
			'created_by' => (int) $comment->user_id,
			'target'     => get_comment_meta( $comment_id, 'type', true ),
			'target_id'  => (int) $comment->comment_post_ID,
		);

		// Check if we should disable all emaill notifications for this project.
		$meta = (array) get_post_meta( $comment->project_id, '_upstream_project_disable_all_notifications' );
		if ( count( $meta ) > 0 && 'on' === $meta[0] ) {
			return array();
		}

		// Check if we need to skip further data processing.
		if ( ! in_array( $comment->target, array( 'project', 'milestone', 'task', 'bug', 'file' ) ) ) {
			return $recipients;
		}

		$comment->target_label = call_user_func( 'upstream_' . $comment->target . '_label' );

		if ( 'project' !== $comment->target ) {
			$comment->target_id = get_comment_meta( $comment_id, 'id', true );
		}

		set_transient( 'upstream:comment_notification.comment:' . $comment_id, $comment, $transient_expiration );

		$get_user = function ( $user_id ) use ( $transient_expiration ) {
			if ( $user_id <= 0 ) {
				return null;
			}

			// Check if the user is cached.
			$user = get_transient( 'upstream:comment_notification.user:' . $user_id );
			if ( empty( $user ) ) {
				// Check if the user exists.
				$user = get_user_by( 'id', $user_id );
				if ( false === $user ) {
					return null;
				}

				// Prepare user data.
				$user = (object) array(
					'id'    => (int) $user->ID,
					'name'  => (string) $user->display_name,
					'email' => (string) $user->user_email,
				);

				// Cache user.
				set_transient( 'upstream:comment_notification.user:' . $user->id, $user, $transient_expiration );
			}

			return $user;
		};

		$fetch_project_meta_as_map = function ( $project_id, $key, &$map ) use ( $transient_expiration, $get_user ) {

			$rowset = array();
			if ( 'milestone' === $key ) {
				$rowset = (array) ( \UpStream\Milestones::getInstance()->get_milestones_as_rowset( $project_id ) );
			} else {
				$rowset = (array) get_post_meta( $project_id, '_upstream_project_' . $key . 's', true );
			}

			foreach ( $rowset as $row ) {
				$title_key = 'milestone' !== $key ? 'title' : 'milestone';

				if ( isset( $row['id'] )
					&& ! empty( $row['id'] )
					&& isset( $row[ $title_key ] )
					&& ! empty( $row[ $title_key ] )
				) {
					$item = (object) array(
						'id'          => $row['id'],
						'title'       => $row[ $title_key ],
						'assigned_to' => isset( $row['assigned_to'] ) ? $row['assigned_to'] : array(),
						'created_by'  => isset( $row['created_by'] ) ? (int) $row['created_by'] : 0,
						'type'        => $key,
					);

					if ( count( $item->assigned_to ) > 0 ) {
						foreach ( $item->assigned_to as $a ) {
							$user         = $get_user( $a );
							$recipients[] = $user->email;
						}
					}

					if ( $item->created_by > 0 ) {
						$user = $get_user( $item->created_by );
						if ( empty( $user ) ) {
							$item->created_by = 0;
						} else {
							$item->created_by = $user->id;
						}
					}

					$map[ $item->id ] = $item;
				}
			}

		};

		// RSD: this cache is causing issues
		// $project = get_transient('upstream:comment_notification.project:' . $comment->project_id).
		if ( empty( $project ) ) {
			$project = get_post( $comment->project_id );
			$project = (object) array(
				'id'          => (int) $project->ID,
				'title'       => $project->post_title,
				'created_by'  => (int) $project->post_author,
				'owner_id'    => (int) get_post_meta( $project->ID, '_upstream_project_owner', true ),
				'owner_email' => '',
				'milestones'  => array(),
				'tasks'       => array(),
				'bugs'        => array(),
				'files'       => array(),
			);

			if ( $project->owner_id > 0 ) {
				$owner = get_transient( 'upstream:comment_notification.user:' . $project->owner_id );
				if ( empty( $owner ) ) {
					$owner = get_user_by( 'id', $project->owner_id );
					$owner = (object) array(
						'id'    => $project->owner_id,
						'name'  => (string) $owner->display_name,
						'email' => (string) $owner->user_email,
					);

					set_transient( 'upstream:comment_notification.user:' . $owner->id, $owner, $transient_expiration );
				}

				$pms = upstream_project_members_ids( $comment->project_id );
				foreach ( $pms as $pm ) {
					$user_info    = get_userdata( $pm );
					$email        = $user_info->user_email;
					$recipients[] = $email;
				}
			}

			if ( 'project' !== $comment->target ) {

				$fetch_project_meta_as_map( $project->id, $comment->target, $project->{$comment->target . 's'} );

				foreach ( $project->{$comment->target . 's'} as $item ) {
					$r = $comment->target_id;
					if ( $item->id === $comment->target_id ) {
						if ( count( $item->assigned_to ) > 0 ) {
							foreach ( $item->assigned_to as $a ) {
								$user         = $get_user( $a );
								$recipients[] = $user->email;
							}
						}

						if ( $item->created_by > 0 ) {
							$user         = $get_user( $item->created_by );
							$recipients[] = $user->email;
						}
					}
				}
			}

			set_transient(
				'upstream:comment_notification.project:' . $comment->project_id,
				$project,
				$transient_expiration
			);
		} else {
			if ( 'project' !== $comment->target
				&& empty( $project->{$comment->target . 's'} )
			) {
				$fetch_project_meta_as_map( $project->id, $comment->target, $project->{$comment->target . 's'} );

				set_transient(
					'upstream:comment_notification.project:' . $comment->project_id,
					$project,
					$transient_expiration
				);
			}
		}

		if ( $comment->parent > 0 ) {
			$parent_id = $comment->parent;

			$users_cache = array();

			do {
				$parent_comment = get_comment( $parent_id );

				$parent_exists = ! empty( $parent_comment );
				if ( $parent_exists ) {
					if ( ! isset( $users_cache[ $parent_comment->user_id ] ) ) {
						$users_cache[ $parent_comment->user_id ]         = $get_user( $parent_comment->user_id );
						$users_cache[ $parent_comment->user_id ]->notify = upstream_user_can_receive_comment_replies_notification( $parent_comment->user_id );
					}

					$user = &$users_cache[ $parent_comment->user_id ];

					$parent_comment_author = $get_user( $parent_comment->user_id );

					if ( $user->notify ) {
						$recipients[] = $parent_comment_author->email;
					}

					$parent_id = (int) $parent_comment->comment_parent;
				}
			} while ( $parent_exists );
		}

		$recipients = array_unique( array_filter( $recipients ) );

		$recipients = apply_filters( 'upstream:comment_notification.recipients', $recipients, $comment );

		return $recipients;
	}

	/**
	 * Add additional info to comment notifications subject.
	 *
	 * @since   1.15.0
	 * @static
	 *
	 * @param   string $subject    The original subject.
	 * @param   int    $comment_id The new comment ID.
	 *
	 * @return  string
	 */
	public static function define_notification_header( $subject, $comment_id ) {
		$comment = get_transient( 'upstream:comment_notification.comment:' . $comment_id );
		// Check if we need to skip further data processing in case of comments written outside UpStream's scope.
		if ( empty( $comment )
			|| in_array( $comment->target, array( 'project', 'milestone', 'task', 'bug', 'file' ) )
		) {
			return $subject;
		}

		$project   = get_transient( 'upstream:comment_notification.project:' . $comment->project_id );
		$site_name = get_bloginfo( 'name' );

		$subject = sprintf(
			'[%s][%s] %s',
			$site_name,
			$project->title,
			sprintf(
				// translators: %s: Comment notification subject.
				_x( 'New comment on %s', 'Comment notification subject', 'upstream' ),
				$comment->target_label
			)
		);

		if ( 'project' !== $comment->target ) {
			$meta = (array) get_post_meta( $project->id, '_upstream_project_' . $comment->target . 's', true );
			foreach ( $meta as $item ) {
				if ( isset( $item['id'] ) && $item['id'] === $comment->target_id ) {
					$title_key = 'milestone' === $comment->target ? 'milestone' : 'title';

					if ( isset( $item[ $title_key ] ) ) {
						$subject .= sprintf( ': "%s"', $item[ $title_key ] );
					}

					break;
				}
			}
		}

		$subject = apply_filters( 'upstream:comment_notification.subject', $subject, $comment, $project );

		return $subject;
	}

	/**
	 * Add items title to notification.
	 *
	 * @param string $comment_text Comment text.
	 * @param int    $comment_id Comment ID.
	 *
	 * @return mixed
	 */
	public static function add_item_title_to_notification( $comment_text, $comment_id ) {
		// Check if the comment has item_title in the metadata.
		$item_title = get_comment_meta( $comment_id, 'title', true );
		$item_type  = get_comment_meta( $comment_id, 'type', true );

		if ( ! empty( $item_title ) ) {
			if ( 'milestone' === $item_type ) {
				// Get the milestone's title.
				$milestones = upstream_get_milestones_titles();

				if ( isset( $milestones[ $item_title ] ) ) {
					$item_title = $milestones[ $item_title ];
				}
			}

			$comment_text = __( 'Item Title: ', 'upstream' ) . $item_title . "\r\n\r\n" . $comment_text;
		}

		if ( ! empty( $item_type ) ) {
			$labels = upstream_get_default_labels();

			$item_type_label = $labels[ $item_type . 's' ]['singular'];

			$comment_text = __( 'Item Type: ', 'upstream' ) . $item_type_label . "\r\n" . $comment_text;
		}

		return $comment_text;
	}

	/**
	 * Convert notifications text for comments in projects into HTML.
	 *
	 * @param string $text Comment text.
	 * @param int    $comment_id Comment ID.
	 *
	 * @return string
	 */
	public static function filter_comment_notification_text( $text, $comment_id ) {
		if ( self::is_comment_from_project( $comment_id ) ) {
			// Convert from txt to html.
			$text = str_replace( "\n", '<br>', $text );
			$text = self::replace_email_with_html_link( $text );
			$text = self::replace_link_with_html_link( $text );
		}

		return $text;
	}

	/**
	 * Is comment from project.
	 *
	 * @param int $comment_id Comment ID.
	 *
	 * @return bool
	 */
	protected static function is_comment_from_project( $comment_id ) {
		// Check if the post is a project.
		$comment = get_comment( $comment_id );
		$post    = get_post( $comment->comment_post_ID );

		return 'project' === $post->post_type;
	}

	/**
	 * Replace email with HTML link.
	 *
	 * @param string $text Email text.
	 *
	 * @return string
	 */
	protected static function replace_email_with_html_link( $text ) {
		$text = preg_replace( '/([^\s@]+@[a-z\._\-0-9]+)/i', '<a href="mailto:${1}" target="_blank">${1}</a>', $text );

		return $text;
	}

	/**
	 * Replace link with HTML link.
	 *
	 * @param string $text Link.
	 *
	 * @return string
	 */
	protected static function replace_link_with_html_link( $text ) {
		$text = preg_replace( '~([a-z]+:\/\/\S+)~i', '<a href="${1}" target="_blank">${1}</a>', $text );

		return $text;
	}

	/**
	 * Convert notifications for comments in projects into HTML.
	 *
	 * @param string $headers Headers.
	 * @param int    $comment_id Comment ID.
	 *
	 * @return string
	 */
	public static function filter_comment_notification_headers( $headers, $comment_id ) {
		if ( self::is_comment_from_project( $comment_id ) ) {
			// Convert from txt to html.
			$headers = str_replace( 'text/plain;', 'text/html;', $headers );
		}

		return $headers;
	}
}
