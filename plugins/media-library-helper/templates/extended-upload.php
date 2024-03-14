<?php
/**
 * Media Library administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
// require_once ABSPATH . 'wp-admin/admin.php';.
if ( ! current_user_can( 'upload_files' ) ) {
	wp_die( esc_html__( 'Sorry, you are not allowed to upload files.', 'media-library-helper' ) );
}
/**
 * Undocumented function
 *
 * @return void
 */
function footer_script(){ ?>
	<script>
		var list = document.querySelector('li.wp-has-current-submenu');
		var currentList = list.querySelector('li.current');
		if( ! currentList ){
			var listWrapper = list.querySelector('ul.wp-submenu-wrap');
			listWrapper.querySelector('li.wp-first-item').classList.add("current");
			listWrapper.querySelector('li.wp-first-item a').classList.add("current");
		}
	</script>
	<?php
}
$mode  = get_user_option( 'media_library_mode', get_current_user_id() ) ? get_user_option( 'media_library_mode', get_current_user_id() ) : 'grid';
$modes = array( 'grid', 'list' );

if ( isset( $_GET['mode'] ) ) {
	$mode = sanitize_text_field( wp_unslash( $_GET['mode'] ) );
	if ( in_array( $mode, $modes, true ) ) {
		update_user_option( get_current_user_id(), 'media_library_mode', $mode );
	}
}

if ( 'grid' === $mode ) {
	wp_enqueue_media();
	wp_enqueue_script( 'media-grid' );
	wp_enqueue_script( 'media' );

	remove_action( 'admin_head', 'wp_admin_canonical_url' );
	$vars   = wp_edit_attachments_query_vars();
	$ignore = array( 'mode', 'post_type', 'post_status', 'posts_per_page' );
	foreach ( $vars as $key => $value ) {
		if ( ! $value || in_array( $key, $ignore, true ) ) {
			unset( $vars[ $key ] );
		}
	}

	wp_localize_script(
		'media-grid',
		'_wpMediaGridSettings',
		array(
			'adminUrl'  => wp_parse_url( self_admin_url(), PHP_URL_PATH ),
			'queryVars' => (object) $vars,
		)
	);

	get_current_screen()->add_help_tab(
		array(
			'id'      => 'overview',
			'title'   => __( 'Overview', 'media-library-helper' ),
			'content' =>
				'<p>' . __( 'All the files you&#8217;ve uploaded are listed in the Media Library, with the most recent uploads listed first.', 'media-library-helper' ) . '</p>' .
				'<p>' . __( 'You can view your media in a simple visual grid or a list with columns. Switch between these views using the icons to the left above the media.', 'media-library-helper' ) . '</p>' .
				'<p>' . __( 'To delete media items, click the Bulk Select button at the top of the screen. Select any items you wish to delete, then click the Delete Selected button. Clicking the Cancel Selection button takes you back to viewing your media.', 'media-library-helper' ) . '</p>',
		)
	);

	get_current_screen()->add_help_tab(
		array(
			'id'      => 'attachment-details',
			'title'   => __( 'Attachment Details', 'media-library-helper' ),
			'content' =>
				'<p>' . __( 'Clicking an item will display an Attachment Details dialog, which allows you to preview media and make quick edits. Any changes you make to the attachment details will be automatically saved.', 'media-library-helper' ) . '</p>' .
				'<p>' . __( 'Use the arrow buttons at the top of the dialog, or the left and right arrow keys on your keyboard, to navigate between media items quickly.', 'media-library-helper' ) . '</p>' .
				'<p>' . __( 'You can also delete individual items and access the extended edit screen from the details dialog.', 'media-library-helper' ) . '</p>',
		)
	);

	get_current_screen()->set_help_sidebar(
		'<p><strong>' . __( 'For more information:', 'media-library-helper' ) . '</strong></p>' .
		'<p><a href="https://wordpress.org/support/article/media-library-screen/">' . __( 'Documentation on Media Library', 'media-library-helper' ) . '</a></p>' .
		'<p><a href="https://wordpress.org/support/">' . __( 'Support', 'media-library-helper' ) . '</a></p>'
	);

	$title       = __( 'Media Library' );
	$parent_file = 'upload.php';

	require_once ABSPATH . 'wp-admin/admin-header.php';
	?>
	<div class="wrap" id="wp-media-grid" data-search="<?php _admin_search_query(); ?>">
		<h1 class="wp-heading-inline"><?php echo esc_html( __( 'Media Library', 'media-library-helper' ) ); ?></h1>

		<?php
		if ( current_user_can( 'upload_files' ) ) {
			?>
			<a href="<?php echo esc_url( admin_url( 'media-new.php' ) ); ?>" class="page-title-action aria-button-if-js"><?php echo esc_html_x( 'Add New', 'file', 'media-library-helper' ); ?></a>
			<?php
		}
		?>

		<hr class="wp-header-end">

		<div class="error hide-if-js">
			<p>
			<?php
			printf(
				/* translators: %s: List view URL. */
				esc_html__( 'The grid view for the Media Library requires JavaScript. <a href="%s">Switch to the list view</a>.', 'media-library-helper' ),
				'upload.php?mode=list'
			);
			?>
			</p>
		</div>
	</div>
	<?php
	require_once ABSPATH . 'wp-admin/admin-footer.php';
	footer_script();
	exit;
}

$wp_list_table = new \Codexin\ImageMetadataSettings\Admin\Extended_Media_List_Table();
$pagenum       = $wp_list_table->get_pagenum();

// Handle bulk actions.
$doaction = $wp_list_table->current_action();

if ( $doaction ) {
	check_admin_referer( 'bulk-media' );

	if ( 'delete_all' === $doaction ) {
		$post_ids = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE post_type='attachment' AND post_status = 'trash'" );
		$doaction = 'delete';
	} elseif ( isset( $_REQUEST['media'] ) ) {
		$post_ids = array_map( 'intval', $_REQUEST['media'] );
	} elseif ( isset( $_REQUEST['ids'] ) ) {
		$ids      = sanitize_text_field( wp_unslash( $_REQUEST['ids'] ) );
		$post_ids = explode( ',', $ids );
	}

	$location = 'upload.php';
	$referer  = wp_get_referer();
	if ( $referer ) {
		if ( false !== strpos( $referer, 'upload.php' ) ) {
			$location = remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'message', 'ids', 'posted' ), $referer );
		}
	}

	switch ( $doaction ) {
		case 'detach':
			if ( isset( $_REQUEST['parent_post_id'] ) ) {
				wp_media_attach_action( intval( wp_unslash( $_REQUEST['parent_post_id'] ) ), 'detach' );
			}
			break;
		case 'attach':
			if ( isset( $_REQUEST['found_post_id'] ) ) {
				wp_media_attach_action( intval( wp_unslash( $_REQUEST['found_post_id'] ) ) );
			}
			break;
		case 'trash':
			if ( ! isset( $post_ids ) ) {
				break;
			}
			foreach ( (array) $post_ids as $post_id ) {
				if ( ! current_user_can( 'delete_post', $post_id ) ) {
					wp_die( esc_html__( 'Sorry, you are not allowed to move this item to the Trash.', 'media-library-helper' ) );
				}

				if ( ! wp_trash_post( $post_id ) ) {
					wp_die( esc_html__( 'Error in moving the item to Trash.', 'media-library-helper' ) );
				}
			}
			$location = add_query_arg(
				array(
					'trashed' => count( $post_ids ),
					'ids'     => implode( ',', $post_ids ),
				),
				$location
			);
			break;
		case 'untrash':
			if ( ! isset( $post_ids ) ) {
				break;
			}
			foreach ( (array) $post_ids as $post_id ) {
				if ( ! current_user_can( 'delete_post', $post_id ) ) {
					wp_die( esc_html__( 'Sorry, you are not allowed to restore this item from the Trash.', 'media-library-helper' ) );
				}

				if ( ! wp_untrash_post( $post_id ) ) {
					wp_die( esc_html__( 'Error in restoring the item from Trash.', 'media-library-helper' ) );
				}
			}
			$location = add_query_arg( 'untrashed', count( $post_ids ), $location );
			break;
		case 'delete':
			if ( ! isset( $post_ids ) ) {
				break;
			}

			foreach ( (array) $post_ids as $post_id_del ) {
				if ( ! current_user_can( 'delete_post', $post_id_del ) ) {
					wp_die( esc_html__( 'Sorry, you are not allowed to delete this item.', 'media-library-helper' ) );
				}

				if ( ! wp_delete_attachment( $post_id_del ) ) {
					wp_die( esc_html__( 'Error in deleting the attachment.', 'media-library-helper' ) );
				}
			}
			$location = add_query_arg( 'deleted', count( $post_ids ), $location );
			break;
		default:
			$screen = get_current_screen()->id;

			/** This action is documented in wp-admin/edit.php */
			$location = apply_filters( "handle_bulk_actions-{$screen}", $location, $doaction, $post_ids ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
	}

	wp_safe_redirect( $location );
	exit;
} elseif ( ! empty( $_GET['_wp_http_referer'] ) ) {
	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		if ( isset( $_GET['name_cdxn_media_field'] ) ) {
			$nonce_value = sanitize_text_field( wp_unslash( $_GET['name_cdxn_media_field'] ) );
			if ( current_user_can('manage_options') && wp_verify_nonce( $nonce_value, 'name_cdxn_action' ) ) {
				wp_safe_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) );
				exit;
			}
		}
	}
}

$wp_list_table->prepare_items();

$title       = __( 'Media Library' );
$parent_file = 'upload.php';

wp_enqueue_script( 'media' );

add_screen_option( 'per_page' );

get_current_screen()->add_help_tab(
	array(
		'id'      => 'overview',
		'title'   => __( 'Overview', 'media-library-helper' ),
		'content' =>
				'<p>' . __( 'All the files you&#8217;ve uploaded are listed in the Media Library, with the most recent uploads listed first. You can use the Screen Options tab to customize the display of this screen.', 'media-library-helper' ) . '</p>' .
				'<p>' . __( 'You can narrow the list by file type/status or by date using the dropdown menus above the media table.', 'media-library-helper' ) . '</p>' .
				'<p>' . __( 'You can view your media in a simple visual grid or a list with columns. Switch between these views using the icons to the left above the media.', 'media-library-helper' ) . '</p>',
	)
);
get_current_screen()->add_help_tab(
	array(
		'id'      => 'actions-links',
		'title'   => __( 'Available Actions', 'media-library-helper' ),
		'content' =>
				'<p>' . __( 'Hovering over a row reveals action links: Edit, Delete Permanently, and View. Clicking Edit or on the media file&#8217;s name displays a simple screen to edit that individual file&#8217;s metadata. Clicking Delete Permanently will delete the file from the media library (as well as from any posts to which it is currently attached). View will take you to the display page for that file.', 'media-library-helper' ) . '</p>',
	)
);
get_current_screen()->add_help_tab(
	array(
		'id'      => 'attaching-files',
		'title'   => __( 'Attaching Files', 'media-library-helper' ),
		'content' =>
				'<p>' . __( 'If a media file has not been attached to any content, you will see that in the Uploaded To column, and can click on Attach to launch a small popup that will allow you to search for existing content and attach the file.', 'media-library-helper' ) . '</p>',
	)
);

get_current_screen()->set_help_sidebar(
	'<p><strong>' . __( 'For more information:', 'media-library-helper' ) . '</strong></p>' .
	'<p><a href="https://wordpress.org/support/article/media-library-screen/">' . __( 'Documentation on Media Library', 'media-library-helper' ) . '</a></p>' .
	'<p><a href="https://wordpress.org/support/">' . __( 'Support', 'media-library-helper' ) . '</a></p>'
);

get_current_screen()->set_screen_reader_content(
	array(
		'heading_views'      => __( 'Filter media items list', 'media-library-helper' ),
		'heading_pagination' => __( 'Media items list navigation', 'media-library-helper' ),
		'heading_list'       => __( 'Media items list', 'media-library-helper' ),
	)
);

require_once ABSPATH . 'wp-admin/admin-header.php';
?>

<div class="wrap">
<h1 class="wp-heading-inline"><?php echo esc_html( __( 'Media Library', 'media-library-helper' ) ); ?></h1>

<?php
if ( current_user_can( 'upload_files' ) ) {
	?>
	<a href="<?php echo esc_url( admin_url( 'media-new.php' ) ); ?>" class="page-title-action"><?php echo esc_html_x( 'Add New', 'file', 'media-library-helper' ); ?></a>
						<?php
}

if ( isset( $_REQUEST['s'] ) ) {
	$requst_s = sanitize_text_field( wp_unslash( $_REQUEST['s'] ) );
	if ( strlen( $requst_s ) ) {
		echo '<span class="subtitle">';
		printf(
			/* translators: %s: Search query. */
			esc_html__( 'Search results for: %s', 'media-library-helper' ),
			'<strong>' . esc_html( $requst_s ) . '</strong>'
		);
		echo '</span>';

	}
}
?>

<hr class="wp-header-end">

<?php
$message = '';
if ( ! empty( $_GET['posted'] ) ) {
	$message                = __( 'Media file updated.', 'media-library-helper' );
	$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'posted' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
}

if ( ! empty( $_GET['attached'] ) && absint( $_GET['attached'] ) ) {
	$attached = absint( $_GET['attached'] );
	if ( 1 === absint( $attached ) ) {
		$message = __( 'Media file attached.', 'media-library-helper' );
	} else {
		/* translators: %s: Number of media files. */
		$message = _n( '%s media file attached.', '%s media files attached.', $attached, 'media-library-helper' );
	}
	$message                = sprintf( $message, number_format_i18n( $attached ) );
	$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'detach', 'attached' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
}

if ( ! empty( $_GET['detach'] ) && absint( $_GET['detach'] ) ) {
	$detached = absint( $_GET['detach'] );
	if ( 1 === absint( $detached ) ) {
		$message = __( 'Media file detached.', 'media-library-helper' );
	} else {
		/* translators: %s: Number of media files. */
		$message = _n( '%s media file detached.', '%s media files detached.', $detached, 'media-library-helper' );
	}
	$message                = sprintf( $message, number_format_i18n( $detached ) );
	$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'detach', 'attached' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
}

if ( ! empty( $_GET['deleted'] ) && absint( $_GET['deleted'] ) ) {
	$deleted = absint( $_GET['deleted'] );
	if ( 1 === $deleted ) {
		$message = __( 'Media file permanently deleted.', 'media-library-helper' );
	} else {
		/* translators: %s: Number of media files. */
		$message = _n( '%s media file permanently deleted.', '%s media files permanently deleted.', $deleted, 'media-library-helper' );
	}
	$message                = sprintf( $message, number_format_i18n( $deleted ) );
	$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'deleted' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
}

if ( ! empty( $_GET['trashed'] ) && absint( $_GET['trashed'] ) ) {
	$trashed = absint( $_GET['trashed'] );
	if ( 1 === $trashed ) {
		$message = __( 'Media file moved to the Trash.', 'media-library-helper' );
	} else {
		/* translators: %s: Number of media files. */
		$message = _n( '%s media file moved to the Trash.', '%s media files moved to the Trash.', $trashed, 'media-library-helper' );
	}
	$message                = sprintf( $message, number_format_i18n( $trashed ) );
	$message               .= ' <a href="' . esc_url( wp_nonce_url( 'upload.php?doaction=undo&action=untrash&ids=' . ( isset( $_GET['ids'] ) ? sanitize_text_field( wp_unslash( $_GET['ids'] ) ) : '' ), 'bulk-media' ) ) . '">' . __( 'Undo', 'media-library-helper' ) . '</a>';
	$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'trashed' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
}

if ( ! empty( $_GET['untrashed'] ) && absint( $_GET['untrashed'] ) ) {
	$untrashed = absint( $_GET['untrashed'] );
	if ( 1 === absint( $untrashed ) ) {
		$message = __( 'Media file restored from the Trash.', 'media-library-helper' );
	} else {
		/* translators: %s: Number of media files. */
		$message = _n( '%s media file restored from the Trash.', '%s media files restored from the Trash.', $untrashed, 'media-library-helper' );
	}
	$message                = sprintf( $message, number_format_i18n( $untrashed ) );
	$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'untrashed' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
}

$messages[1] = __( 'Media file updated.', 'media-library-helper' );
$messages[2] = __( 'Media file permanently deleted.', 'media-library-helper' );
$messages[3] = __( 'Error saving media file.', 'media-library-helper' );
$messages[4] = __( 'Media file moved to the Trash.', 'media-library-helper' ) . ' <a href="' . esc_url( wp_nonce_url( 'upload.php?doaction=undo&action=untrash&ids=' . ( isset( $_GET['ids'] ) ? sanitize_text_field( wp_unslash( $_GET['ids'] ) ) : '' ), 'bulk-media' ) ) . '">' . __( 'Undo', 'media-library-helper' ) . '</a>';
$messages[5] = __( 'Media file restored from the Trash.', 'media-library-helper' );

if ( ! empty( $_GET['message'] ) && isset( $messages[ $_GET['message'] ] ) ) {
	$get_message            = absint( $_GET['message'] );
	$message                = $messages[ $get_message ];
	$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'message' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
}

if ( ! empty( $message ) ) {
	?>
<div id="message" class="updated notice is-dismissible"><p><?php echo wp_kses_post( $message ); ?></p></div>
<?php } ?>
<script>
	function cancel_bulk_edit() {
		jQuery('.button.table-editable').removeClass('lock-hide');
	}
</script>
<form id="posts-filter" method="get">
<?php wp_nonce_field( 'name_cdxn_action', 'name_cdxn_media_field' ); ?>
<?php $wp_list_table->views(); ?>

<?php $wp_list_table->display(); ?>

<div id="ajax-response"></div>
<?php find_posts_div(); ?>
</form>

<?php
if ( $wp_list_table->has_items() ) {
	$wp_list_table->inline_edit();
}
?>
<div id="ajax-response"></div>
<div class="clear" /></div>
</div>

<?php
require_once ABSPATH . 'wp-admin/admin-footer.php';
footer_script();
