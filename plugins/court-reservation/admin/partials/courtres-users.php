<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://webmuehle.at
 * @since      1.1.0
 *
 * @package    Settings
 * @subpackage Courtres/admin/settigns
 */
?>

<?php
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die();
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php
require 'courtres-notice-upgrade.php';
?>


<?php
/**
 * User administration panel
 *
 * @package WordPress
 * @subpackage Administration
 * @since 1.0.0
 */

/** WordPress Administration Bootstrap */

if ( ! current_user_can( 'list_users' ) ) {
	wp_die(
		'<h1>' . esc_html__( 'You need a higher level of permission.' ) . '</h1>' .
		'<p>' . esc_html__( 'Sorry, you are not allowed to list users.' ) . '</p>',
		403
	);
}

// $wp_list_table = _get_list_table('CR_Users_List_Table');
$wp_list_table = $this->get_users_table();
$pagenum       = $wp_list_table->get_pagenum();
$title         = __( 'Users' );
$parent_file   = 'users.php';

add_screen_option( 'per_page' );

// contextual help - choose Help on the top right of admin panel to preview this.
get_current_screen()->add_help_tab(
	array(
		'id'      => 'overview',
		'title'   => __( 'Overview' ),
		'content' => '<p>' . __( 'This screen lists all the existing users for your site. Each user has one of five defined roles as set by the site admin: Site Administrator, Editor, Author, Contributor, or Subscriber. Users with roles other than Administrator will see fewer options in the dashboard navigation when they are logged in, based on their role.' ) . '</p>' .
					 '<p>' . __( 'To add a new user for your site, click the Add New button at the top of the screen or Add New in the Users menu section.' ) . '</p>',
	)
);

get_current_screen()->add_help_tab(
	array(
		'id'      => 'screen-content',
		'title'   => __( 'Screen Content' ),
		'content' => '<p>' . __( 'You can customize the display of this screen in a number of ways:' ) . '</p>' .
						'<ul>' .
						'<li>' . __( 'You can hide/display columns based on your needs and decide how many users to list per screen using the Screen Options tab.' ) . '</li>' .
						'<li>' . __( 'You can filter the list of users by User Role using the text links above the users list to show All, Administrator, Editor, Author, Contributor, or Subscriber. The default view is to show all users. Unused User Roles are not listed.' ) . '</li>' .
						'<li>' . __( 'You can view all posts made by a user by clicking on the number under the Posts column.' ) . '</li>' .
						'</ul>',
	)
);

$help = '<p>' . __( 'Hovering over a row in the users list will display action links that allow you to manage users. You can perform the following actions:' ) . '</p>' .
	'<ul>' .
	'<li>' . __( '<strong>Edit</strong> takes you to the editable profile screen for that user. You can also reach that screen by clicking on the username.' ) . '</li>';

if ( is_multisite() ) {
	$help .= '<li>' . __( '<strong>Remove</strong> allows you to remove a user from your site. It does not delete their content. You can also remove multiple users at once by using Bulk Actions.' ) . '</li>';
} else {
	$help .= '<li>' . __( '<strong>Delete</strong> brings you to the Delete Users screen for confirmation, where you can permanently remove a user from your site and delete their content. You can also delete multiple users at once by using Bulk Actions.' ) . '</li>';
}

$help .= '</ul>';

get_current_screen()->add_help_tab(
	array(
		'id'      => 'action-links',
		'title'   => __( 'Available Actions' ),
		'content' => $help,
	)
);
unset( $help );

get_current_screen()->set_help_sidebar(
	'<p><strong>' . __( 'For more information:' ) . '</strong></p>' .
	'<p>' . __( '<a href="https://codex.wordpress.org/Users_Screen">Documentation on Managing Users</a>' ) . '</p>' .
	'<p>' . __( '<a href="https://codex.wordpress.org/Roles_and_Capabilities">Descriptions of Roles and Capabilities</a>' ) . '</p>' .
	'<p>' . __( '<a href="https://wordpress.org/support/">Support Forums</a>' ) . '</p>'
);

get_current_screen()->set_screen_reader_content(
	array(
		'heading_views'      => __( 'Filter users list' ),
		'heading_pagination' => __( 'Users list navigation' ),
		'heading_list'       => __( 'Users list' ),
	)
);

if ( empty( $_REQUEST ) ) {
	$referer = '<input type="hidden" name="wp_http_referer" value="' . esc_attr( wp_unslash( $_SERVER['REQUEST_URI'] ) ) . '" />';
} elseif ( isset( $_REQUEST['wp_http_referer'] ) ) {
	$redirect = remove_query_arg( array( 'wp_http_referer', 'updated', 'delete_count' ), wp_unslash( sanitize_text_field( $_REQUEST['wp_http_referer'] ) ) );
	$referer  = '<input type="hidden" name="wp_http_referer" value="' . esc_attr( $redirect ) . '" />';
} else {
	$redirect = 'users.php';
	$referer  = '';
}

$update = '';

switch ( $wp_list_table->current_action() ) {

	/* Bulk Dropdown menu Role changes */
	case 'promote':
		check_admin_referer( 'bulk-users' );

		if ( ! current_user_can( 'promote_users' ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to edit this user.' ), 403 );
		}

		if ( empty( $_REQUEST['users'] ) ) {
			wp_redirect( $redirect );
			exit();
		}

		$editable_roles = get_editable_roles();
		$role           = false;
		if ( ! empty( $_REQUEST['new_role2'] ) ) {
			$role = sanitize_text_field( $_REQUEST['new_role2'] );
		} elseif ( ! empty( $_REQUEST['new_role'] ) ) {
			$role = sanitize_text_field( $_REQUEST['new_role'] );
		}

		if ( ! $role || empty( $editable_roles[ $role ] ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to give users that role.' ), 403 );
		}

		$userids = sanitize_text_field( $_REQUEST['users'] );
		$update  = 'promote';
		foreach ( $userids as $id ) {
			$id = (int) $id;

			if ( ! current_user_can( 'promote_user', $id ) ) {
				wp_die( esc_html__( 'Sorry, you are not allowed to edit this user.' ), 403 );
			}
			// The new role of the current user must also have the promote_users cap or be a multisite super admin
			if ( $id == $current_user->ID && ! $wp_roles->role_objects[ $role ]->has_cap( 'promote_users' )
			&& ! ( is_multisite() && current_user_can( 'manage_network_users' ) ) ) {
				$update = 'err_admin_role';
				continue;
			}

			// If the user doesn't already belong to the blog, bail.
			if ( is_multisite() && ! is_user_member_of_blog( $id ) ) {
				wp_die(
					'<h1>' . esc_html__( 'Something went wrong.' ) . '</h1>' .
					'<p>' . esc_html__( 'One of the selected users is not a member of this site.' ) . '</p>',
					403
				);
			}

			$user = get_userdata( $id );
			$user->set_role( $role );
		}

		wp_redirect( add_query_arg( 'update', $update, $redirect ) );
		exit();

	case 'dodelete':
		if ( is_multisite() ) {
			wp_die( esc_html__( 'User deletion is not allowed from this screen.' ), 400 );
		}

		check_admin_referer( 'delete-users' );

		if ( empty( $_REQUEST['users'] ) ) {
			wp_redirect( $redirect );
			exit();
		}

		$userids = array_map( 'intval', (array) $_REQUEST['users'] );

		if ( empty( $_REQUEST['delete_option'] ) ) {
			$url = self_admin_url( 'users.php?action=delete&users[]=' . implode( '&users[]=', $userids ) . '&error=true' );
			$url = str_replace( '&amp;', '&', wp_nonce_url( $url, 'bulk-users' ) );
			wp_redirect( $url );
			exit;
		}

		if ( ! current_user_can( 'delete_users' ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to delete users.' ), 403 );
		}

		$update       = 'del';
		$delete_count = 0;

		foreach ( $userids as $id ) {
			if ( ! current_user_can( 'delete_user', $id ) ) {
				wp_die( esc_html__( 'Sorry, you are not allowed to delete that user.' ), 403 );
			}

			if ( $id == $current_user->ID ) {
				$update = 'err_admin_del';
				continue;
			}
			switch ( $_REQUEST['delete_option'] ) {
				case 'delete':
					wp_delete_user( $id );
					break;
				case 'reassign':
					wp_delete_user( $id, sanitize_text_field( $_REQUEST['reassign_user'] ) );
					break;
			}
			++$delete_count;
		}

		$redirect = add_query_arg(
			array(
				'delete_count' => $delete_count,
				'update'       => $update,
			),
			$redirect
		);
		wp_redirect( $redirect );
		exit();

	case 'delete':
		if ( is_multisite() ) {
			wp_die( esc_html__( 'User deletion is not allowed from this screen.' ), 400 );
		}

		check_admin_referer( 'bulk-users' );

		if ( empty( $_REQUEST['users'] ) && empty( $_REQUEST['user'] ) ) {
			wp_redirect( $redirect );
			exit();
		}

		if ( ! current_user_can( 'delete_users' ) ) {
			$errors = new WP_Error( 'edit_users', __( 'Sorry, you are not allowed to delete users.' ) );
		}

		if ( empty( $_REQUEST['users'] ) ) {
			$userids = array( intval( $_REQUEST['user'] ) );
		} else {
			$userids = array_map( 'intval', (array) $_REQUEST['users'] );
		}

		$users_have_content = false;
		if ( $wpdb->get_var( "SELECT ID FROM {$wpdb->posts} WHERE post_author IN( " . implode( ',', $userids ) . ' ) LIMIT 1' ) ) {
			$users_have_content = true;
		} elseif ( $wpdb->get_var( "SELECT link_id FROM {$wpdb->links} WHERE link_owner IN( " . implode( ',', $userids ) . ' ) LIMIT 1' ) ) {
			$users_have_content = true;
		}

		if ( $users_have_content ) {
			add_action( 'admin_head', 'delete_users_add_js' );
		}

		// include( ABSPATH . 'wp-admin/admin-header.php' );
		?>
<form method="post" name="updateusers" id="updateusers">
		<?php wp_nonce_field( 'delete-users' ); ?>
		<?php echo esc_html( $referer ); ?>

<div class="wrap">
<h1><?php esc_html_e( 'Delete Users' ); ?></h1>
		<?php if ( isset( $_REQUEST['error'] ) ) : ?>
	<div class="error">
		<p><strong><?php esc_html_e( 'ERROR:' ); ?></strong> <?php esc_html_e( 'Please select an option.' ); ?></p>
	</div>
<?php endif; ?>

		<?php if ( 1 == count( $userids ) ) : ?>
	<p><?php esc_html_e( 'You have specified this user for deletion:' ); ?></p>
<?php else : ?>
	<p><?php esc_html_e( 'You have specified these users for deletion:' ); ?></p>
<?php endif; ?>

<ul>
		<?php
		$go_delete = 0;
		foreach ( $userids as $id ) {
			$user = get_userdata( $id );
			if ( $id == $current_user->ID ) {
				/* translators: 1: user id, 2: user login */ ?>
					<li>ID <?php echo esc_html($id) . " " . esc_html($user->user_login); ?></li>\n <?php
			} else {
				/* translators: 1: user id, 2: user login */ ?>
					<li><input type="hidden" name="users[]" value="<?php echo esc_attr( $id ); ?>" />ID <?php echo esc_html($id) . " " . esc_html($user->user_login); ?></li>\n <?php
				$go_delete++;
			}
		}
		?>
	</ul>
		<?php
		if ( $go_delete ) :

			if ( ! $users_have_content ) :
				?>
		<input type="hidden" name="delete_option" value="delete" />
			<?php else : ?>
				<?php if ( 1 == $go_delete ) : ?>
			<fieldset><p><legend><?php esc_html_e( 'What should be done with content owned by this user?' ); ?></legend></p>
		<?php else : ?>
			<fieldset><p><legend><?php esc_html_e( 'What should be done with content owned by these users?' ); ?></legend></p>
		<?php endif; ?>
		<ul style="list-style:none;">
			<li><label><input type="radio" id="delete_option0" name="delete_option" value="delete" />
				<?php esc_html_e( 'Delete all content.' ); ?></label></li>
			<li><input type="radio" id="delete_option1" name="delete_option" value="reassign" />
				<?php
				echo '<label for="delete_option1">' . esc_html__( 'Attribute all content to:' ) . '</label> ';
				wp_dropdown_users(
					array(
						'name'    => 'reassign_user',
						'exclude' => array_diff( $userids, array( $current_user->ID ) ),
						'show'    => 'display_name_with_login',
					)
				);
				?>
			</li>
		</ul></fieldset>
				<?php
		endif;
			/**
			 * Fires at the end of the delete users form prior to the confirm button.
			 *
			 * @since 4.0.0
			 * @since 4.5.0 The `$userids` parameter was added.
			 *
			 * @param WP_User $current_user WP_User object for the current user.
			 * @param array   $userids      Array of IDs for users being deleted.
			 */
			do_action( 'delete_user_form', $current_user, $userids );
			?>
	<input type="hidden" name="action" value="dodelete" />
			<?php submit_button( __( 'Confirm Deletion' ), 'primary' ); ?>
		<?php else : ?>
	<p><?php esc_html_e( 'There are no valid users selected for deletion.' ); ?></p>
	<?php endif; ?>
</div>
</form>
		<?php

		break;

	case 'doremove':
		check_admin_referer( 'remove-users' );

		if ( ! is_multisite() ) {
			wp_die( esc_html__( 'You can&#8217;t remove users.' ), 400 );
		}

		if ( empty( $_REQUEST['users'] ) ) {
			wp_redirect( $redirect );
			exit;
		}

		if ( ! current_user_can( 'remove_users' ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to remove users.' ), 403 );
		}

		$userids = sanitize_text_field( $_REQUEST['users'] );

		$update = 'remove';
		foreach ( $userids as $id ) {
			$id = (int) $id;
			if ( ! current_user_can( 'remove_user', $id ) ) {
				$update = 'err_admin_remove';
				continue;
			}
			remove_user_from_blog( $id, $blog_id );
		}

		$redirect = add_query_arg( array( 'update' => $update ), $redirect );
		wp_redirect( $redirect );
		exit;

	case 'remove':
		check_admin_referer( 'bulk-users' );

		if ( ! is_multisite() ) {
			wp_die( esc_html__( 'You can&#8217;t remove users.' ), 400 );
		}

		if ( empty( $_REQUEST['users'] ) && empty( $_REQUEST['user'] ) ) {
			wp_redirect( $redirect );
			exit();
		}

		if ( ! current_user_can( 'remove_users' ) ) {
			$error = new WP_Error( 'edit_users', esc_html__( 'Sorry, you are not allowed to remove users.' ) );
		}

		if ( empty( $_REQUEST['users'] ) ) {
			$userids = array( intval( $_REQUEST['user'] ) );
		} else {
			$userids = sanitize_text_field( $_REQUEST['users'] );
		}

		?>
<form method="post" name="updateusers" id="updateusers">
		<?php wp_nonce_field( 'remove-users' ); ?>
		<?php echo esc_html($referer); ?>

<div class="wrap">
<h1><?php esc_html_e( 'Remove Users from Site' ); ?></h1>

		<?php if ( 1 == count( $userids ) ) : ?>
	<p><?php esc_html_e( 'You have specified this user for removal:' ); ?></p>
<?php else : ?>
	<p><?php esc_html_e( 'You have specified these users for removal:' ); ?></p>
<?php endif; ?>

<ul>
		<?php
		$go_remove = false;
		foreach ( $userids as $id ) {
			$id   = (int) $id;
			$user = get_userdata( $id );
			if ( ! current_user_can( 'remove_user', $id ) ) {
				/* translators: 1: user id, 2: user login */ ?>
				<li> <?php
					sprintf( esc_html__( 'ID #%1$s: %2$s <strong>Sorry, you are not allowed to remove this user.</strong>' ), $id, $user->user_login ); ?>
				</li>\n <?php
			} else {
				/* translators: 1: user id, 2: user login */ ?>
					<li><input type="hidden" name="users[]" value="<?php echo esc_html($id); ?>" /> <?php 
						echo sprintf( esc_html__( 'ID #%1$s: %2$s' ), esc_html($id), esc_html($user->user_login) ); ?>
					</li>\n <?php 
				$go_remove = true;
			}
		}
		?>
</ul>
		<?php if ( $go_remove ) : ?>
		<input type="hidden" name="action" value="doremove" />
			<?php submit_button( __( 'Confirm Removal' ), 'primary' ); ?>
	<?php else : ?>
	<p><?php esc_html_e( 'There are no valid users selected for removal.' ); ?></p>
	<?php endif; ?>
</div>
</form>
		<?php

		break;

	default:
		if ( ! empty( $_GET['_wp_http_referer'] ) ) {
			wp_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
			exit;
		}

		if ( $wp_list_table->current_action() && ! empty( $_REQUEST['users'] ) ) {
			$userids  = sanitize_text_field( $_REQUEST['users'] );
			$sendback = wp_get_referer();

			/** This action is documented in wp-admin/edit-comments.php */
			$sendback = apply_filters( 'handle_bulk_actions-' . get_current_screen()->id, $sendback, $wp_list_table->current_action(), $userids );

			wp_safe_redirect( $sendback );
			exit;
		}

		$wp_list_table->prepare_items();
		$total_pages = $wp_list_table->get_pagination_arg( 'total_pages' );
		if ( $pagenum > $total_pages && $total_pages > 0 ) {
			wp_redirect( add_query_arg( 'paged', $total_pages ) );
			exit;
		}

		// include( ABSPATH . 'wp-admin/admin-header.php' );

		$messages = array();
		if ( isset( $_GET['update'] ) ) :
			switch ( $_GET['update'] ) {
				case 'del':
				case 'del_many':
					$delete_count = isset( $_GET['delete_count'] ) ? (int) $_GET['delete_count'] : 0;
					if ( 1 == $delete_count ) {
						$message = __( 'User deleted.' );
					} else {
						$message = _n( '%s user deleted.', '%s users deleted.', $delete_count );
					}
					$messages[] = '<div id="message" class="updated notice is-dismissible"><p>' . sprintf( $message, number_format_i18n( $delete_count ) ) . '</p></div>';
					break;
				case 'add':
					if ( isset( $_GET['id'] ) && ( $user_id = $_GET['id'] ) && current_user_can( 'edit_user', $user_id ) ) {
						/* translators: %s: edit page url */
						$messages[] = '<div id="message" class="updated notice is-dismissible"><p>' . sprintf(
							__( 'New user created. <a href="%s">Edit user</a>' ),
							esc_url(
								add_query_arg(
									'wp_http_referer',
									urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ),
									self_admin_url( 'user-edit.php?user_id=' . $user_id )
								)
							)
						) . '</p></div>';
					} else {
						$messages[] = '<div id="message" class="updated notice is-dismissible"><p>' . __( 'New user created.' ) . '</p></div>';
					}
					break;
				case 'promote':
					$messages[] = '<div id="message" class="updated notice is-dismissible"><p>' . __( 'Changed roles.' ) . '</p></div>';
					break;
				case 'err_admin_role':
					$messages[] = '<div id="message" class="error notice is-dismissible"><p>' . __( 'The current user&#8217;s role must have user editing capabilities.' ) . '</p></div>';
					$messages[] = '<div id="message" class="updated notice is-dismissible"><p>' . __( 'Other user roles have been changed.' ) . '</p></div>';
					break;
				case 'err_admin_del':
					$messages[] = '<div id="message" class="error notice is-dismissible"><p>' . __( 'You can&#8217;t delete the current user.' ) . '</p></div>';
					$messages[] = '<div id="message" class="updated notice is-dismissible"><p>' . __( 'Other users have been deleted.' ) . '</p></div>';
					break;
				case 'remove':
					$messages[] = '<div id="message" class="updated notice is-dismissible fade"><p>' . __( 'User removed from this site.' ) . '</p></div>';
					break;
				case 'err_admin_remove':
					$messages[] = '<div id="message" class="error notice is-dismissible"><p>' . __( "You can't remove the current user." ) . '</p></div>';
					$messages[] = '<div id="message" class="updated notice is-dismissible fade"><p>' . __( 'Other users have been removed.' ) . '</p></div>';
					break;
			}
		endif;
		?>

		<?php if ( isset( $errors ) && is_wp_error( $errors ) ) : ?>
	<div class="error">
		<ul>
			<?php
		foreach ( $errors->get_error_messages() as $err ) { ?>
				<li> <?php
					echo esc_html($err); ?>
				</li>\n <?php
			}
			?>
		</ul>
	</div>
			<?php
	endif;

		if ( ! empty( $messages ) ) {
			foreach ( $messages as $msg ) {
				echo esc_html($msg);
			}
		}
		?>

<div class="wrap">
<h1 class="wp-heading-inline">
		<?php
		echo esc_html( $title );
		?>
</h1>

		<?php
		if ( current_user_can( 'create_users' ) ) {
			?>
	<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres-user' )); ?>" class="page-title-action"><?php echo esc_html_x( 'Add New', 'user' ); ?></a>
	<?php } elseif ( is_multisite() && current_user_can( 'promote_users' ) ) { ?>
	<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres-user' )); ?>" class="page-title-action"><?php echo esc_html_x( 'Add Existing', 'user' ); ?></a>
			<?php
	}

	if ( isset( $usersearch ) && strlen( $usersearch ) ) {
		/* translators: %s: search keywords */
		printf( '<span class="subtitle">' . esc_html__( 'Search results for &#8220;%s&#8221;' ) . '</span>', esc_html( $usersearch ) );
	}
	?>

<hr class="wp-header-end">

		<?php // $wp_list_table->views(); ?>

<form method="get">

		<?php // $wp_list_table->search_box( __( 'Search Users' ), 'user' ); ?>

		<?php if ( ! empty( $_REQUEST['role'] ) ) { ?>
<input type="hidden" name="role" value="<?php echo esc_attr( $_REQUEST['role'] ); ?>" />
<?php } ?>

		<?php $wp_list_table->display(); ?>
</form>

<br class="clear" />
</div>
		<?php
		break;

} // end of the $doaction switch

