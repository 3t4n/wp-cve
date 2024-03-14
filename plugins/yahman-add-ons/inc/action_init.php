<?php
defined( 'ABSPATH' ) || exit;

add_action( 'init', function() {

	$option = get_option('yahman_addons');

	if( !isset($option['faster']['cache']))
		return;

	
	require_once YAHMAN_ADDONS_DIR . 'inc/remove_cache.php';

	
	add_action( 'save_post', 'yahman_addons_remove_cache' );

	
	add_action( 'comment_post', 'yahman_addons_comment_remove_cache', 10, 3 );
	function yahman_addons_comment_remove_cache( $comment_ID, $comment_approved, $commentdata ) {
		if($commentdata['comment_approved'])
			yahman_addons_remove_cache( $commentdata['comment_post_ID'] );
	}

	
	add_action( 'edit_comment', 'yahman_addons_comment_edit', 10, 2 );
	function yahman_addons_comment_edit( $comment_ID, $commentdata ) {
		yahman_addons_remove_cache( $commentdata['comment_post_ID'] );
	}

	
	add_action('comment_approved_to_unapproved', 'yahman_addons_comment_approved');
	add_action('comment_approved_to_trash', 'yahman_addons_comment_approved');
	add_action('comment_approved_to_spam', 'yahman_addons_comment_approved');

	add_action('comment_unapproved_to_approved', 'yahman_addons_comment_approved');
	add_action('comment_trash_to_approved', 'yahman_addons_comment_approved');
	add_action('comment_spam_to_approved', 'yahman_addons_comment_approved');


	function yahman_addons_comment_approved($comment) {
		yahman_addons_remove_cache( $comment->comment_post_ID );
	}


});

