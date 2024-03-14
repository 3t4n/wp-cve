<?php
/**
 * @package           WpbCommentModeration
 * @subpackage        Admin Screen
 * @author            WPBeginner
 * @copyright         2021 WPBeginner
 * @license           GPL-2.0-or-later
 *
 * Creates a second comment moderation screen for people without the
 * edit_posts primitive capability.
 */

namespace WPB\CommentModerationRole\AdminScreen;

use WPB\CommentModerationRole;

/**
 * Kick off admin screen creation.
 *
 * Runs as the plugin is required by WP.
 */
function bootstrap() {
	add_action( 'admin_menu', __NAMESPACE__ . '\\admin_menu' );
}

/**
 * Bootstrap filters for the default screen for low privileged users.
 */
function bootstrap_default_comment_screen() {
	// Make no changes for users with high permissions.
	if ( current_user_can( 'edit_others_posts' ) ) {
		return;
	}

	add_filter( 'comments_list_table_query_args', __NAMESPACE__ . '\\comment_table_query_args' );
}

/**
 * Add second comment moderation screen if needed.
 *
 * Users with the edit_post capability don't need this.
 *
 * Runs on the `admin_menu` action.
 */
function admin_menu() {
	if (
		current_user_can( 'edit_posts' ) ||
		! current_user_can( CommentModerationRole\moderator_cap() )
	) {
		// Can not moderate or will see WP Core screen.
		bootstrap_default_comment_screen();
		return;
	}

	$awaiting_mod      = wp_count_comments();
	$awaiting_mod      = $awaiting_mod->moderated;
	$awaiting_mod_i18n = number_format_i18n( $awaiting_mod );
	/* translators: %s: Number of comments. */
	$awaiting_mod_text = sprintf( _n( '%s Comment in moderation', '%s Comments in moderation', $awaiting_mod, 'comment-moderation-role' ), $awaiting_mod_i18n );

	add_menu_page(
		__( 'Comments', 'comment-moderation-role' ),
		/* translators: %s: Number of comments. */
		sprintf( __( 'Comments %s', 'comment-moderation-role' ), '<span class="awaiting-mod count-' . absint( $awaiting_mod ) . '"><span class="pending-count" aria-hidden="true">' . $awaiting_mod_i18n . '</span><span class="comments-in-moderation-text screen-reader-text">' . $awaiting_mod_text . '</span></span>' ),
		CommentModerationRole\moderator_cap(),
		'am-comment-moderation',
		__NAMESPACE__ . '\\admin_page',
		'dashicons-admin-comments',
		25
	);
}

/**
 * Modify the comment's table query to display only public post types.
 *
 * Runs on the `comments_list_table_query_args` filter.
 *
 * @param array $args Comment table WP_Comment_Query query arguments.
 * @return array Modified query limiting post types available.
 */
function comment_table_query_args( $args ) {
	if ( ! current_user_can( 'moderate_comments' ) ) {
		/*
		 * For low privileged users, this will replace the author querystring
		 * parameter on the comment list table with the logged in users ID.
		 *
		 * As there isn't a UI for selecting the author and the parameter is
		 * only available via URL hacking, the imperfect behaviour here is
		 * simply ignored.
		 */
		$args['post_author'] = get_current_user_id();
	}

	$public_custom_post_types = get_post_types(
		array(
			'publicly_queryable' => true,
		)
	);

	$public_built_in_post_types = get_post_types(
		array(
			'public'   => true,
			'_builtin' => true,
		)
	);

	$public_post_types = array_unique( array_merge( $public_custom_post_types, $public_built_in_post_types ) );

	if ( empty( $args['post_type'] ) ) {
		$args['post_type'] = $public_post_types;
		return $args;
	}

	$queried_post_types = (array) $args['post_type'];

	$resolved_post_types = array_intersect( $queried_post_types, $public_post_types );

	if ( empty( $resolved_post_types ) ) {
		/*
		 * Do not display any posts if the resolved post types is an empty array,
		 * this is a nasty hack to prevent the query from returning all post types,
		 * both public and private.
		 */
		$resolved_post_types = 'unregistered_cpt_that_is_too_long_to_fit_in_the_post_type_field_of_the_post_table';
	}

	$args['post_type'] = $resolved_post_types;

	return $args;
}

/**
 * Duplicate admin page for comment moderation screen.
 *
 * This is a copy-paste of the relevant parts of the wp-admin/edit-comments.php
 * native WordPress admin screen.
 *
 * Rather than WordPress's comments table, it uses a custom class to check a user
 * has moderation capabilities rather than edit post capabilities.
 */
function admin_page() {
	add_filter( 'comments_list_table_query_args', __NAMESPACE__ . '\\comment_table_query_args' );

	global $post_id, $comment_status, $wpdb;

	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php';
	require_once __DIR__ . '/class-wpb-comments-list-table.php';

	$wp_list_table = new \WPB\CommentModerationRole\WPB_Comments_List_Table( array( 'screen' => get_current_screen() ) );
	$pagenum       = $wp_list_table->get_pagenum();

	$doaction = $wp_list_table->current_action();

	if ( $doaction ) {
		check_admin_referer( 'bulk-comments' );

		if (
			'delete_all' === $doaction
			&& ! empty( $_REQUEST['pagegen_timestamp'] )
			&& ! empty( $_REQUEST['comment_status'] )
		) {
			// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$comment_status = sanitize_key( wp_unslash( $_REQUEST['comment_status'] ) );
			// Custom validation sanitization functions fail with namespaces.
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$delete_time = CommentModerationRole\sanitize_mysql_date( wp_unslash( $_REQUEST['pagegen_timestamp'] ) );
			if ( ! $comment_status || ! $delete_time ) {
				wp_safe_redirect( wp_get_referer() );
				exit;
			}

			$comment_ids = $wpdb->get_col( $wpdb->prepare( "SELECT comment_ID FROM $wpdb->comments WHERE comment_approved = %s AND %s > comment_date_gmt", $comment_status, $delete_time ) );
			$doaction    = 'delete';
		} elseif ( isset( $_REQUEST['delete_comments'] ) ) {
			$comment_ids = array_map( 'absint', wp_unslash( $_REQUEST['delete_comments'] ) );
			$doaction    = isset( $_REQUEST['action'] ) ? sanitize_key( wp_unslash( $_REQUEST['action'] ) ) : '';
		} elseif ( isset( $_REQUEST['ids'] ) ) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, it is but after exploded.
			$comment_ids = array_map( 'absint', explode( ',', wp_unslash( $_REQUEST['ids'] ) ) );
		} elseif ( wp_get_referer() ) {
			wp_safe_redirect( wp_get_referer() );
			exit;
		}

		$approved   = 0;
		$unapproved = 0;
		$spammed    = 0;
		$unspammed  = 0;
		$trashed    = 0;
		$untrashed  = 0;
		$deleted    = 0;

		$redirect_to = remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'spammed', 'unspammed', 'approved', 'unapproved', 'ids' ), wp_get_referer() );
		$redirect_to = add_query_arg( 'paged', $pagenum, $redirect_to );

		wp_defer_comment_counting( true );

		foreach ( $comment_ids as $comment_id ) { // Check the permissions on each.
			if ( ! current_user_can( 'edit_comment', $comment_id ) ) {
				continue;
			}

			switch ( $doaction ) {
				case 'approve':
					wp_set_comment_status( $comment_id, 'approve' );
					$approved++;
					break;
				case 'unapprove':
					wp_set_comment_status( $comment_id, 'hold' );
					$unapproved++;
					break;
				case 'spam':
					wp_spam_comment( $comment_id );
					$spammed++;
					break;
				case 'unspam':
					wp_unspam_comment( $comment_id );
					$unspammed++;
					break;
				case 'trash':
					wp_trash_comment( $comment_id );
					$trashed++;
					break;
				case 'untrash':
					wp_untrash_comment( $comment_id );
					$untrashed++;
					break;
				case 'delete':
					wp_delete_comment( $comment_id );
					$deleted++;
					break;
			}
		}

		if ( ! in_array( $doaction, array( 'approve', 'unapprove', 'spam', 'unspam', 'trash', 'delete' ), true ) ) {
			$screen = get_current_screen()->id;

			/** This action is documented in wp-admin/edit.php */
			$redirect_to = apply_filters( "handle_bulk_actions-{$screen}", $redirect_to, $doaction, $comment_ids ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		}

		wp_defer_comment_counting( false );

		if ( $approved ) {
			$redirect_to = add_query_arg( 'approved', $approved, $redirect_to );
		}
		if ( $unapproved ) {
			$redirect_to = add_query_arg( 'unapproved', $unapproved, $redirect_to );
		}
		if ( $spammed ) {
			$redirect_to = add_query_arg( 'spammed', $spammed, $redirect_to );
		}
		if ( $unspammed ) {
			$redirect_to = add_query_arg( 'unspammed', $unspammed, $redirect_to );
		}
		if ( $trashed ) {
			$redirect_to = add_query_arg( 'trashed', $trashed, $redirect_to );
		}
		if ( $untrashed ) {
			$redirect_to = add_query_arg( 'untrashed', $untrashed, $redirect_to );
		}
		if ( $deleted ) {
			$redirect_to = add_query_arg( 'deleted', $deleted, $redirect_to );
		}
		if ( $trashed || $spammed ) {
			$redirect_to = add_query_arg( 'ids', implode( ',', $comment_ids ), $redirect_to );
		}

		wp_safe_redirect( $redirect_to );
		exit;
	} elseif ( ! empty( $_GET['_wp_http_referer'] ) ) {
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated, request URI exists.
		wp_safe_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) );
		exit;
	}

	$wp_list_table->prepare_items();

	wp_enqueue_script( 'admin-comments' );
	enqueue_comment_hotkeys_js();

	if ( $post_id ) {
		$comments_count      = wp_count_comments( $post_id );
		$draft_or_post_title = wp_html_excerpt( _draft_or_post_title( $post_id ), 50, '&hellip;' );
		if ( $comments_count->moderated > 0 ) {
			$title = sprintf(
				/* translators: 1: Comments count, 2: Post title. */
				__( 'Comments (%1$s) on &#8220;%2$s&#8221;', 'comment-moderation-role' ),
				number_format_i18n( $comments_count->moderated ),
				$draft_or_post_title
			);
		} else {
			$title = sprintf(
				/* translators: %s: Post title. */
				__( 'Comments on &#8220;%s&#8221;', 'comment-moderation-role' ),
				$draft_or_post_title
			);
		}
	} else {
		$comments_count = wp_count_comments();
		if ( $comments_count->moderated > 0 ) {
			$title = sprintf(
				/* translators: %s: Comments count. */
				__( 'Comments (%s)', 'comment-moderation-role' ),
				number_format_i18n( $comments_count->moderated )
			);
		} else {
			$title = __( 'Comments', 'comment-moderation-role' );
		}
	}

	add_screen_option( 'per_page' );

	get_current_screen()->add_help_tab(
		array(
			'id'      => 'overview',
			'title'   => __( 'Overview', 'comment-moderation-role' ),
			'content' =>
					'<p>' . __( 'You can manage comments made on your site similar to the way you manage posts and other content. This screen is customizable in the same ways as other management screens, and you can act on comments using the on-hover action links or the bulk actions.', 'comment-moderation-role' ) . '</p>',
		)
	);
	get_current_screen()->add_help_tab(
		array(
			'id'      => 'moderating-comments',
			'title'   => __( 'Moderating Comments', 'comment-moderation-role' ),
			'content' =>
						'<p>' . __( 'A red bar on the left means the comment is waiting for you to moderate it.', 'comment-moderation-role' ) . '</p>' .
						'<p>' . __( 'In the <strong>Author</strong> column, in addition to the author&#8217;s name, email address, and blog URL, the commenter&#8217;s IP address is shown. Clicking on this link will show you all the comments made from this IP address.', 'comment-moderation-role' ) . '</p>' .
						'<p>' . __( 'In the <strong>Comment</strong> column, hovering over any comment gives you options to approve, reply (and approve), quick edit, edit, spam mark, or trash that comment.', 'comment-moderation-role' ) . '</p>' .
						'<p>' . __( 'In the <strong>In response to</strong> column, there are three elements. The text is the name of the post that inspired the comment, and links to the post editor for that entry. The View Post link leads to that post on your live site. The small bubble with the number in it shows the number of approved comments that post has received. If there are pending comments, a red notification circle with the number of pending comments is displayed. Clicking the notification circle will filter the comments screen to show only pending comments on that post.', 'comment-moderation-role' ) . '</p>' .
						'<p>' . __( 'In the <strong>Submitted on</strong> column, the date and time the comment was left on your site appears. Clicking on the date/time link will take you to that comment on your live site.', 'comment-moderation-role' ) . '</p>' .
						'<p>' . __( 'Many people take advantage of keyboard shortcuts to moderate their comments more quickly. Use the link to the side to learn more.', 'comment-moderation-role' ) . '</p>',
		)
	);

	get_current_screen()->set_help_sidebar(
		'<p><strong>' . __( 'For more information:', 'comment-moderation-role' ) . '</strong></p>' .
		'<p>' . __( '<a href="https://wordpress.org/support/article/comments-screen/">Documentation on Comments</a>', 'comment-moderation-role' ) . '</p>' .
		'<p>' . __( '<a href="https://wordpress.org/support/article/comment-spam/">Documentation on Comment Spam</a>', 'comment-moderation-role' ) . '</p>' .
		'<p>' . __( '<a href="https://wordpress.org/support/article/keyboard-shortcuts/">Documentation on Keyboard Shortcuts</a>', 'comment-moderation-role' ) . '</p>' .
		'<p>' . __( '<a href="https://wordpress.org/support/">Support</a>', 'comment-moderation-role' ) . '</p>'
	);

	get_current_screen()->set_screen_reader_content(
		array(
			'heading_views'      => __( 'Filter comments list', 'comment-moderation-role' ),
			'heading_pagination' => __( 'Comments list navigation', 'comment-moderation-role' ),
			'heading_list'       => __( 'Comments list', 'comment-moderation-role' ),
		)
	);

	require_once ABSPATH . 'wp-admin/admin-header.php';
	?>

	<div class="wrap">
	<h1 class="wp-heading-inline">
	<?php
	if ( $post_id ) {
		printf(
			/* translators: %s: Link to post. */
			esc_html__( 'Comments on &#8220;%s&#8221;', 'comment-moderation-role' ),
			sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_url( get_edit_post_link( $post_id ) ),
				wp_kses_post( wp_html_excerpt( _draft_or_post_title( $post_id ), 50, '&hellip;' ) )
			)
		);
	} else {
		esc_html_e( 'Comments', 'comment-moderation-role' );
	}
	?>
	</h1>

	<?php
	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, doesn't need to be for length.
	if ( isset( $_REQUEST['s'] ) && strlen( wp_unslash( $_REQUEST['s'] ) ) ) {
		echo '<span class="subtitle">';
		printf(
			/* translators: %s: Search query. */
			esc_html__( 'Search results for: %s', 'comment-moderation-role' ),
			'<strong>' . wp_kses_post( wp_html_excerpt( esc_html( sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) ), 50, '&hellip;' ) ) . '</strong>'
		);
		echo '</span>';
	}
	?>

	<hr class="wp-header-end">

	<?php
	if ( isset( $_REQUEST['error'] ) ) {
		$error     = (int) $_REQUEST['error'];
		$error_msg = '';
		switch ( $error ) {
			case 1:
				$error_msg = __( 'Invalid comment ID.', 'comment-moderation-role' );
				break;
			case 2:
				$error_msg = __( 'Sorry, you are not allowed to edit comments on this post.', 'comment-moderation-role' );
				break;
		}
		if ( $error_msg ) {
			echo '<div id="moderated" class="error"><p>' . esc_html( $error_msg ) . '</p></div>';
		}
	}

	if ( isset( $_REQUEST['approved'] ) || isset( $_REQUEST['deleted'] ) || isset( $_REQUEST['trashed'] ) || isset( $_REQUEST['untrashed'] ) || isset( $_REQUEST['spammed'] ) || isset( $_REQUEST['unspammed'] ) || isset( $_REQUEST['same'] ) ) {
		$approved  = isset( $_REQUEST['approved'] ) ? (int) $_REQUEST['approved'] : 0;
		$deleted   = isset( $_REQUEST['deleted'] ) ? (int) $_REQUEST['deleted'] : 0;
		$trashed   = isset( $_REQUEST['trashed'] ) ? (int) $_REQUEST['trashed'] : 0;
		$untrashed = isset( $_REQUEST['untrashed'] ) ? (int) $_REQUEST['untrashed'] : 0;
		$spammed   = isset( $_REQUEST['spammed'] ) ? (int) $_REQUEST['spammed'] : 0;
		$unspammed = isset( $_REQUEST['unspammed'] ) ? (int) $_REQUEST['unspammed'] : 0;
		$same      = isset( $_REQUEST['same'] ) ? (int) $_REQUEST['same'] : 0;

		if ( $approved > 0 || $deleted > 0 || $trashed > 0 || $untrashed > 0 || $spammed > 0 || $unspammed > 0 || $same > 0 ) {
			if ( $approved > 0 ) {
				/* translators: %s: Number of comments. */
				$messages[] = sprintf( _n( '%s comment approved.', '%s comments approved.', $approved, 'comment-moderation-role' ), $approved );
			}

			if ( $spammed > 0 ) {
				$ids = isset( $_REQUEST['ids'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ids'] ) ) : 0;
				/* translators: %s: Number of comments. */
				$messages[] = sprintf( _n( '%s comment marked as spam.', '%s comments marked as spam.', $spammed, 'comment-moderation-role' ), $spammed ) . ' <a href="' . esc_url( wp_nonce_url( "edit-comments.php?doaction=undo&action=unspam&ids=$ids", 'bulk-comments' ) ) . '">' . __( 'Undo', 'comment-moderation-role' ) . '</a><br />';
			}

			if ( $unspammed > 0 ) {
				/* translators: %s: Number of comments. */
				$messages[] = sprintf( _n( '%s comment restored from the spam.', '%s comments restored from the spam.', $unspammed, 'comment-moderation-role' ), $unspammed );
			}

			if ( $trashed > 0 ) {
				$ids = isset( $_REQUEST['ids'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ids'] ) ) : 0;
				/* translators: %s: Number of comments. */
				$messages[] = sprintf( _n( '%s comment moved to the Trash.', '%s comments moved to the Trash.', $trashed, 'comment-moderation-role' ), $trashed ) . ' <a href="' . esc_url( wp_nonce_url( "edit-comments.php?doaction=undo&action=untrash&ids=$ids", 'bulk-comments' ) ) . '">' . __( 'Undo', 'comment-moderation-role' ) . '</a><br />';
			}

			if ( $untrashed > 0 ) {
				/* translators: %s: Number of comments. */
				$messages[] = sprintf( _n( '%s comment restored from the Trash.', '%s comments restored from the Trash.', $untrashed, 'comment-moderation-role' ), $untrashed );
			}

			if ( $deleted > 0 ) {
				/* translators: %s: Number of comments. */
				$messages[] = sprintf( _n( '%s comment permanently deleted.', '%s comments permanently deleted.', $deleted, 'comment-moderation-role' ), $deleted );
			}

			if ( $same > 0 ) {
				$comment = get_comment( $same );
				if ( $comment ) {
					switch ( $comment->comment_approved ) {
						case '1':
							$messages[] = __( 'This comment is already approved.', 'comment-moderation-role' ) . ' <a href="' . esc_url( admin_url( "comment.php?action=editcomment&c=$same" ) ) . '">' . __( 'Edit comment', 'comment-moderation-role' ) . '</a>';
							break;
						case 'trash':
							$messages[] = __( 'This comment is already in the Trash.', 'comment-moderation-role' ) . ' <a href="' . esc_url( admin_url( 'edit-comments.php?comment_status=trash' ) ) . '"> ' . __( 'View Trash', 'comment-moderation-role' ) . '</a>';
							break;
						case 'spam':
							$messages[] = __( 'This comment is already marked as spam.', 'comment-moderation-role' ) . ' <a href="' . esc_url( admin_url( "comment.php?action=editcomment&c=$same" ) ) . '">' . __( 'Edit comment', 'comment-moderation-role' ) . '</a>';
							break;
					}
				}
			}

			echo '<div id="moderated" class="updated notice is-dismissible"><p>' . wp_kses_post( implode( "<br/>\n", $messages ) ) . '</p></div>';
		}
	}
	?>

	<?php $wp_list_table->views(); ?>

	<form id="comments-form" method="get">

	<?php $wp_list_table->search_box( __( 'Search Comments', 'comment-moderation-role' ), 'comment' ); ?>

	<?php if ( $post_id ) : ?>
	<input type="hidden" name="p" value="<?php echo esc_attr( (int) $post_id ); ?>" />
	<?php endif; ?>
	<input type="hidden" name="comment_status" value="<?php echo esc_attr( $comment_status ); ?>" />
	<input type="hidden" name="pagegen_timestamp" value="<?php echo esc_attr( current_time( 'mysql', 1 ) ); ?>" />

	<input type="hidden" name="_total" value="<?php echo esc_attr( $wp_list_table->get_pagination_arg( 'total_items' ) ); ?>" />
	<input type="hidden" name="_per_page" value="<?php echo esc_attr( $wp_list_table->get_pagination_arg( 'per_page' ) ); ?>" />
	<input type="hidden" name="_page" value="<?php echo esc_attr( $wp_list_table->get_pagination_arg( 'page' ) ); ?>" />

	<?php if ( isset( $_REQUEST['paged'] ) ) { ?>
		<input type="hidden" name="paged" value="<?php echo esc_attr( absint( $_REQUEST['paged'] ) ); ?>" />
	<?php } ?>

	<?php $wp_list_table->display(); ?>
	</form>
	</div>

	<div id="ajax-response"></div>

	<?php
	wp_comment_reply( '-1', true, 'detail' );
	wp_comment_trashnotice();
}
