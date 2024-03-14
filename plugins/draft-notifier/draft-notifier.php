<?php
/*
Plugin Name: Draft Notifier
Plugin URI: http://blogwaffe.com/2005/04/22/275/
Description: Draft Notifier sends a notification email to your blog's admin address when a post written by a Contributor is Submitted for Review.
Version: 1.2.1
Author: Michael D. Adams
Author URI: http://blogwaffe.com/
*/

/*
Released under the GPL license
http://www.gnu.org/licenses/gpl.txt
*/

if ( !defined( 'ABSPATH' ) ) exit;

function mda_draft_notifier( $post_ID ) {
	global $current_user;

	if ( !$draft = get_post( $post_ID ) )
		return false;
	// Don't send the notification if it's not a draft
	if ( 'pending' != $draft->post_status )
		return $draft->ID;

	// Only posts and pages.  No attachments, revisions, ...
	if ( !in_array( $draft->post_type, array( 'post', 'page' ) ) )
		return $draft->ID;

	$author = new WP_User( $draft->post_author );
	if ( $author->has_cap( 'publish_posts' ) )
		return $draft->ID;

	//the editor is probably the original author, we already have that info
	//if the editor is not the original author, we need to grab new info
	if ( $current_user->ID == $author->id )
		$editor =& $author;
	else
		$editor = $current_user;

	$blogname = get_settings( 'blogname' );
	$blog_url = get_settings( 'siteurl' );
	if ( $editor->id == $author->id ) {
		$draft_note_mess = sprintf(
			__( 'Draft written by %s <%s>.' ),
			$author->data->user_login,
			$author->data->user_email
		);
	} else {
		$draft_note_mess = sprintf(
			__( 'Draft written by %s <%s> and submitted for review by %s <%s>.' ),
			$author->data->user_login,
			$author->data->user_email,
			$editor->data->user_login,
			$editor->data->user_email
		);
	}

	$from_func = create_function( '', "return '{$author->data->user_email}';" );
	$from_name_func = create_function( '', "return '{$author->data->user_login}';" );
	add_filter( 'wp_mail_from', $from_func );
	add_filter( 'wp_mail_from_name', $from_name_func );

	$draft_note_subj = sprintf( __( '[%s] Draft Submitted: %s' ), get_bloginfo( 'title' ), $draft->post_title );

	$draft_note_mess .= __( '

TITLE: %s

%s

To edit or approve this draft visit %s' );

	@wp_mail( get_settings( 'admin_email' ), $draft_note_subj, sprintf(
		$draft_note_mess,
		$draft->post_title,
		$draft->post_content,
		clean_url( get_settings( 'siteurl' ) . "/wp-admin/post.php?action=edit&post=$draft->ID", null, 'url' )
	) );

	remove_filter( 'wp_mail_from', $from_func );
	remove_filter( 'wp_mail_from_name', $from_name_func );

	return $draft->ID;
}

function mda_dn_transition_post_status( $new_status, $old_status, $post ) {
	if ( 'pending' == $new_status && !empty( $post->ID ) && in_array( $post->post_type, array( 'post', 'page' ) ) )
		mda_draft_notifier( $post->ID );
}

add_action( 'transition_post_status', 'mda_dn_transition_post_status', 10, 3 );
