<?php
/*
Plugin Name: BAW Moderator Role
Plugin URI: http://boiteaweb.fr/plugin-moderator-role-3331.html
Description: Creates a new user "Moderator" role who can moderate comments only
Version: 1.6
Author: Juliobox
Author URI: http://wp-rocket.me
*/

add_action( 'admin_init', 'bawmro_redirect_users' );
function bawmro_redirect_users() {
	global $pagenow;
	// Pages forbidden by the plugin but autorized by WordPress
	$pagesnow = array( 'edit-comments.php', 'comment.php', 'profile.php', 'admin-ajax.php' );
	// If the user id a Moderator, if not, let's continue
	if ( current_user_can( 'moderator' ) && $pagenow ) {
		global $menu; 
		if ( $menu ) {
			// Keep only these 2 menus, a filter is used if a plugin adds its own menu relating to comments.
			$menu_ok = apply_filters( 'allowed_moderator_menus', array( __( 'Comments' ), __( 'Profile' ) ) );
			foreach ( $menu as $menu_key => $menu_val ) {
				$menu_value = explode( ' ', $menu[ $menu_key ][0] );
				if ( ! in_array( $menu_value[0] != NULL ? $menu_value[0] : '', $menu_ok ) !== false ) {
					// Delete all others menus entries
					unset( $menu[ $menu_key ] );
				}
			}
		}
		// Is the user is trying to access to a forbidden page, redirect him on his job : moderate comment !
		if ( ! in_array( $pagenow, $pagesnow ) ) {
			wp_redirect( admin_url( 'edit-comments.php' ) );
			die();
		}
	}
}

register_activation_hook( __FILE__, 'bawmro_add_role' );
function bawmro_add_role() {
	// The new role.
	add_role(
		'moderator',
		_x( 'Moderator', 'User role' ), // translators: user role
		array(
			'read' => true,
			'edit_posts' => true,
			'edit_other_posts' => true,
			'edit_published_posts' => true,
			'moderate_comments' => true,
		)
	);
}

add_action( 'wp_before_admin_bar_render', 'bawmro_edit_admin_bar' );
function bawmro_edit_admin_bar()
{	
	global $wp_admin_bar;
	if ( current_user_can( 'moderator' ) ) {
		// If the user is Moderator, remove the "New post" menu in admin bar
		$wp_admin_bar->remove_menu( 'new-content' );
	}
	// This filter is used to add more admin bar menu deletion
	apply_filters( 'baw_before_admin_bar_render', $wp_admin_bar );
}

add_filter('map_meta_cap', 'bawmro_map_meta_cap', 10, 4 );
function bawmro_map_meta_cap( $caps, $cap, $user_id, $args ) {
	// Force comments to be autorized for moderation for "moderator" role
	if ( apply_filters( 'allow_moderate_all_comments', true ) && in_array( $cap, array( 'edit_comment', 'edit_post' ) ) && $caps && current_user_can( 'moderator' ) ) {
		return array();
	}
	return $caps;
}

register_deactivation_hook( __FILE__, 'bawmro_deactivation' );
function bawmro_deactivation() {
	$users = get_users( array( 'role' => 'moderator' ) );
	// If at least 1 user got the Moderator role, do not deactivate the plugin, you have to change all Moderators role before.
	if ( ! count( $users ) ) {
		remove_role( 'moderator' );
	} else {
		// Light L10N
		if ( 'fr_FR' != get_locale() ) {
			$msg = 'You have to remove the Moderator role from all users before deactivate/uninstall the plugin.';
		} else {
			$msg = 'Vous devez supprimer le role Moderator de tous les utilisateurs avant de d&eacute;sactiver/supprimer le plugin.';
		}
		wp_die( $msg );
	}
}

add_filter( 'notify_moderator_email_to', 'bawmro_notify_moderator_email_to' );
function bawmro_notify_moderator_email_to( $email_to ) {
	$modos = get_users( array( 'role' => 'moderator', 'fields' => array( 'user_email' ) ) );
	foreach ( $modos as $k => $modo ) {
		$modos[ $k ] = $modo->user_email;
	}
	$email_to = array_merge( $email_to, $modos );
	return $email_to;
}

if ( ! function_exists( 'wp_notify_moderator' ) ) :

function wp_notify_moderator( $comment_id ) {
	global $wpdb;

	if ( 0 == get_option( 'moderation_notify' ) ) {
		return true;
	}

	$comment = get_comment($comment_id);
	$post = get_post( $comment->comment_post_ID );
	$user = get_userdata( $post->post_author );
	// Send to the administration and to the post author if the author can modify the comment.
	$email_to = apply_filters( 'notify_moderator_email_to', array( get_option('admin_email') ) ); // MRO adds ths filter
	if ( user_can( $user->ID, 'edit_comment', $comment_id ) && !empty( $user->user_email ) && ( get_option( 'admin_email' ) != $user->user_email) ) {
		$email_to[] = $user->user_email;
	}

	$comment_author_domain = @gethostbyaddr( $comment->comment_author_IP );
	$comments_waiting = $wpdb->get_var( "SELECT count(comment_ID) FROM $wpdb->comments WHERE comment_approved = '0'" );

	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

	switch ( $comment->comment_type ) {
		case 'trackback':
			$notify_message  = sprintf( __( 'A new trackback on the post "%s" is waiting for your approval' ), $post->post_title ) . "\r\n";
			$notify_message .= get_permalink( $comment->comment_post_ID ) . "\r\n\r\n";
			$notify_message .= sprintf( __( 'Website : %1$s (IP: %2$s , %3$s)' ), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
			$notify_message .= sprintf( __( 'URL    : %s' ), $comment->comment_author_url ) . "\r\n";
			$notify_message .= __( 'Trackback excerpt: ' ) . "\r\n" . $comment->comment_content . "\r\n\r\n";
			break;
		case 'pingback':
			$notify_message  = sprintf( __( 'A new pingback on the post "%s" is waiting for your approval' ), $post->post_title ) . "\r\n";
			$notify_message .= get_permalink( $comment->comment_post_ID ) . "\r\n\r\n";
			$notify_message .= sprintf( __( 'Website : %1$s (IP: %2$s , %3$s)' ), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
			$notify_message .= sprintf( __( 'URL    : %s' ), $comment->comment_author_url ) . "\r\n";
			$notify_message .= __( 'Pingback excerpt: ' ) . "\r\n" . $comment->comment_content . "\r\n\r\n";
			break;
		default: //Comments
			$notify_message  = sprintf( __( 'A new comment on the post "%s" is waiting for your approval' ), $post->post_title ) . "\r\n";
			$notify_message .= get_permalink( $comment->comment_post_ID ) . "\r\n\r\n";
			$notify_message .= sprintf( __( 'Author : %1$s (IP: %2$s , %3$s)' ), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
			$notify_message .= sprintf( __( 'E-mail : %s' ), $comment->comment_author_email ) . "\r\n";
			$notify_message .= sprintf( __( 'URL    : %s' ), $comment->comment_author_url ) . "\r\n";
			$notify_message .= sprintf( __( 'Whois  : http://whois.arin.net/rest/ip/%s' ), $comment->comment_author_IP ) . "\r\n";
			$notify_message .= __( 'Comment: ' ) . "\r\n" . $comment->comment_content . "\r\n\r\n";
			break;
	}

	$notify_message .= sprintf( __( 'Approve it: %s' ),  admin_url( "comment.php?action=approve&c=$comment_id" ) ) . "\r\n";
	if ( EMPTY_TRASH_DAYS ) {
		$notify_message .= sprintf( __( 'Trash it: %s' ), admin_url( "comment.php?action=trash&c=$comment_id" ) ) . "\r\n";
	} else {
		$notify_message .= sprintf( __( 'Delete it: %s' ), admin_url( "comment.php?action=delete&c=$comment_id" ) ) . "\r\n";
	}
	$notify_message .= sprintf( __( 'Spam it: %s' ), admin_url( "comment.php?action=spam&c=$comment_id" ) ) . "\r\n";

	$notify_message .= sprintf( _n( 'Currently %s comment is waiting for approval. Please visit the moderation panel:',
 		'Currently %s comments are waiting for approval. Please visit the moderation panel:', $comments_waiting ), number_format_i18n( $comments_waiting ) ) . "\r\n";
	$notify_message .= admin_url( "edit-comments.php?comment_status=moderated" ) . "\r\n";

	$subject = sprintf( __( '[%1$s] Please moderate: "%2$s"' ), $blogname, $post->post_title );
	$message_headers = '';

	$notify_message = apply_filters( 'comment_moderation_text', $notify_message, $comment_id );
	$subject = apply_filters( 'comment_moderation_subject', $subject, $comment_id );
	$message_headers = apply_filters( 'comment_moderation_headers', $message_headers );

	foreach ( $email_to as $email ) {
		@wp_mail( $email, $subject, $notify_message, $message_headers );
	}

	return true;
}
endif; 