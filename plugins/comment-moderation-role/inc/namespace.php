<?php
namespace WPB\CommentModerationRole;

use WP_Ajax_Response;

/**
 * Register actions.
 *
 * This runs as the plugin is required by WordPress.
 */
function bootstrap() {
	RolesCaps\bootstrap();
	MetaCaps\bootstrap();
	AdminScreen\bootstrap();

	add_action( 'wp_ajax_replyto-comment', __NAMESPACE__ . '\\wp_ajax_replyto_comment', 0 );

	// Plugin compatibility
	add_filter( 'woocommerce_prevent_admin_access', __NAMESPACE__ . '\\filter_woocommerce_prevent_admin_access' );
}

/**
 * Allow moderators to access the dashboard.
 *
 * This allows moderators to access the dashboard without the `edit_posts`
 * capability when WooCommerce is installed.
 *
 * @param bool $block_access Whether access is blocked by WC.
 * @return bool Modified block rule taking in to account moderator role.
 */
function filter_woocommerce_prevent_admin_access( $block_access ) {
	if ( current_user_can( moderator_cap() ) ) {
		return false;
	}

	return $block_access;
}

/**
 * The capability used for checking moderation rights.
 *
 * @return string Comment moderation capability.
 */
function moderator_cap() {
	/**
	 * Modify the capability used for checking moderation rights.
	 *
	 * @param string $moderator_cap Comment moderation capability.
	 */
	return apply_filters( 'wpb.comment_moderation_role.moderator_cap', 'moderate_comments' );
}

/**
 * The slug used for comment moderation role
 *
 * @return string Comment moderation role's slug.
 */
function moderator_role_slug() {
	/**
	 * Modify the slug used for comment moderation role.
	 *
	 * @param string $moderator_role Comment moderation role's slug.
	 */
	return apply_filters( 'wpb.comment_moderation_role.moderator_role_slug', 'wpb_comment_moderator' );
}

/**
 * The display name used for comment moderation role
 *
 * @return string Comment moderation role's display name.
 */
function moderator_role_name() {
	/**
	 * Modify the display name used for comment moderation role.
	 *
	 * @param string $moderator_role Comment moderation role's name.
	 */
	return apply_filters( 'wpb.comment_moderation_role.moderator_role_name', __( 'WPB Comment Moderator', 'comment-moderation-role' ) );
}

/**
 * Ajax handler for replying to a comment.
 *
 * Replaces the core action to check for moderator caps rather
 * than edit post caps.
 *
 * @param string $action Action to perform.
 */
function wp_ajax_replyto_comment( $action ) {
	if ( empty( $action ) ) {
		$action = 'replyto-comment';
	}

	check_ajax_referer( $action, '_ajax_nonce-replyto-comment' );

	if ( empty( (int) $_POST['comment_post_ID'] ) && ! empty( (int) $_POST['comment_ID'] ) ) {
		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
		$comment_post_ID = get_comment( (int) $_POST['comment_ID'] )->comment_post_ID;
	} elseif ( empty( (int) $_POST['comment_post_ID'] ) ) {
		wp_die( esc_html__( 'Error: A valid post ID must be provided when commenting on a post', 'comment-moderation-role' ) );
	} else {
		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
		$comment_post_ID = (int) $_POST['comment_post_ID'];
	}

	//phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
	$post = get_post( $comment_post_ID );

	if ( ! $post ) {
		wp_die( esc_html__( 'Error: A valid post ID must be provided when commenting on a post', 'comment-moderation-role' ) );
	}

	if (
		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
		! current_user_can( 'edit_post', $comment_post_ID ) &&
		! is_post_publicly_viewable( $post )
	) {
		wp_die( esc_html__( 'Error: A valid post ID must be provided when commenting on a post', 'comment-moderation-role' ) );
	}

	if ( empty( $post->post_status ) ) {
		wp_die( 1 );
	} elseif ( in_array( $post->post_status, array( 'draft', 'pending', 'trash' ), true ) ) {
		wp_die( esc_html__( 'Error: You can&#8217;t reply to a comment on a draft post.', 'comment-moderation-role' ) );
	}

	$user = wp_get_current_user();

	if ( $user->exists() ) {
		$user_ID              = $user->ID;
		$comment_author       = wp_slash( $user->display_name );
		$comment_author_email = wp_slash( $user->user_email );
		$comment_author_url   = wp_slash( $user->user_url );
		$comment_content      = false; // default
		if ( ! empty( $_POST['content'] ) ) {
			//phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, sanitized with KSES as applicable.
			$comment_content = ! empty( trim( wp_unslash( $_POST['content'] ) ) ) ? trim( wp_unslash( $_POST['content'] ) ) : false;
		}
		if ( empty( $_POST['comment_type'] ) ) {
			$comment_type = 'comment';
		} else {
			$comment_type = trim( sanitize_text_field( wp_unslash( $_POST['comment_type'] ) ) );
		}

		if ( current_user_can( 'unfiltered_html' ) ) {
			if ( ! isset( $_POST['_wp_unfiltered_html_comment'] ) ) {
				$_POST['_wp_unfiltered_html_comment'] = '';
			}

			if ( wp_create_nonce( 'unfiltered-html-comment' ) !== $_POST['_wp_unfiltered_html_comment'] ) {
				kses_remove_filters(); // Start with a clean slate.
				kses_init_filters();   // Set up the filters.
				remove_filter( 'pre_comment_content', 'wp_filter_post_kses' );
				add_filter( 'pre_comment_content', 'wp_filter_kses' );
			}
		}
	} else {
		wp_die( esc_html__( 'Sorry, you must be logged in to reply to a comment.', 'comment-moderation-role' ) );
	}

	if ( '' === $comment_content ) {
		wp_die( esc_html__( 'Error: Please type your comment text.', 'comment-moderation-role' ) );
	}

	$comment_parent = 0;

	if ( isset( $_POST['comment_ID'] ) ) {
		$comment_parent = absint( $_POST['comment_ID'] );
	}

	$comment_auto_approved = false;
	$commentdata           = compact( 'comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID' );

	// Automatically approve parent comment.
	if ( ! empty( $_POST['approve_parent'] ) ) {
		$parent = get_comment( $comment_parent );

		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
		if ( $parent && '0' === $parent->comment_approved && (int) $parent->comment_post_ID === (int) $comment_post_ID ) {
			if ( ! current_user_can( 'edit_comment', $parent->comment_ID ) ) {
				wp_die( -3 );
			}

			if ( wp_set_comment_status( $parent, 'approve' ) ) {
				$comment_auto_approved = true;
			}
		}
	}

	$comment_id = wp_new_comment( $commentdata );

	if ( is_wp_error( $comment_id ) ) {
		wp_die( wp_kses_post( $comment_id->get_error_message() ) );
	}

	$comment = get_comment( $comment_id );

	if ( ! $comment ) {
		wp_die( esc_html__( 'Error: Unable to save comment', 'comment-moderation-role' ) );
	}

	$position = ( isset( $_POST['position'] ) && (int) $_POST['position'] ) ? (int) $_POST['position'] : '-1';

	ob_start();
	if ( isset( $_REQUEST['mode'] ) && 'dashboard' === $_REQUEST['mode'] ) {
		require_once ABSPATH . 'wp-admin/includes/dashboard.php';
		_wp_dashboard_recent_comments_row( $comment );
	} else {
		if ( isset( $_REQUEST['mode'] ) && 'single' === $_REQUEST['mode'] ) {
			$wp_list_table = _get_list_table( 'WP_Post_Comments_List_Table', array( 'screen' => 'edit-comments' ) );
		} else {
			$wp_list_table = _get_list_table( 'WP_Comments_List_Table', array( 'screen' => 'edit-comments' ) );
		}
		$wp_list_table->single_row( $comment );
	}
	$comment_list_item = ob_get_clean();

	$response = array(
		'what'     => 'comment',
		'id'       => $comment->comment_ID,
		'data'     => $comment_list_item,
		'position' => $position,
	);

	$counts                   = wp_count_comments();
	$response['supplemental'] = array(
		'in_moderation'        => $counts->moderated,
		'i18n_comments_text'   => sprintf(
			/* translators: %s: Number of comments. */
			_n( '%s Comment', '%s Comments', $counts->approved, 'comment-moderation-role' ),
			number_format_i18n( $counts->approved )
		),
		'i18n_moderation_text' => sprintf(
			/* translators: %s: Number of comments. */
			_n( '%s Comment in moderation', '%s Comments in moderation', $counts->moderated, 'comment-moderation-role' ),
			number_format_i18n( $counts->moderated )
		),
	);

	if ( $comment_auto_approved ) {
		$response['supplemental']['parent_approved'] = $parent->comment_ID;
		$response['supplemental']['parent_post_id']  = $parent->comment_post_ID;
	}

	$x = new WP_Ajax_Response();
	$x->add( $response );
	$x->send();

	exit;
}

/**
 * Determine whether a post status is considered "viewable".
 *
 * This is ported in from WP 5.7.
 *
 * @param string|stdClass $post_status Post status name or object.
 * @return bool Whether the post status should be considered viewable.
 */
function is_post_status_viewable( $post_status ) {
	// Use WP function once it's available.
	if ( function_exists( '\\is_post_status_viewable' ) ) {
		return \is_post_status_viewable( $post_status );
	}

	if ( is_scalar( $post_status ) ) {
		$post_status = get_post_status_object( $post_status );
		if ( ! $post_status ) {
			return false;
		}
	}

	if (
		! is_object( $post_status ) ||
		$post_status->internal ||
		$post_status->protected
	) {
		return false;
	}

	return $post_status->publicly_queryable || ( $post_status->_builtin && $post_status->public );
}

/**
 * Determine whether a post is publicly viewable.
 *
 * This is ported in from WP 5.7.
 *
 * @param int|WP_Post|null $post Optional. Post ID or post object. Defaults to global $post.
 * @return bool Whether the post is publicly viewable.
 */
function is_post_publicly_viewable( $post = null ) {
	// Use WP function once it's available.
	if ( function_exists( '\\is_post_publicly_viewable' ) ) {
		return \is_post_publicly_viewable( $post );
	}

	$post = get_post( $post );

	if ( ! $post ) {
		return false;
	}

	$post_type   = get_post_type( $post );
	$post_status = get_post_status( $post );

	return is_post_type_viewable( $post_type ) && is_post_status_viewable( $post_status );
}

/**
 * Validate string as MYSQL formatted date.
 *
 * @param string $date  Date to validate.
 * @return string|false Date if valid, false if not valid.
 */
function sanitize_mysql_date( $date ) {
	if ( preg_match( '/\d{4}(-\d{2}){2} \d{2}(:\d{2}){2}/', $date ) ) {
		return $date;
	}
	return false;
}
