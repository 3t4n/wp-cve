<?php

if( !defined('ABSPATH') ){
	exit;
}

/**
 * Injects uploader markup and area before submit button wrapper.
 */
add_action( 'bbp_theme_before_reply_form_submit_wrapper', 'bbp_reply_uploader_area' );

/**
 * Adds attachment to the reply once it is created.
 * 
 * @param int $reply_id Reply ID that has been created 
 */
add_action( 'bbp_new_reply_post_extras', 'bbp_uploader_reply_created' ); // When creating new reply
add_action( 'bbp_edit_reply_post_extras', 'bbp_uploader_reply_created' ); // When editing reply

/**
 * Previews already added attachments on edit reply screen
 */
add_action( 'bbp_uploader_reply_img_container', 'bbp_reply_img_container' );

/**
 * Adds images after content of topic and reply
 */
add_action('bbp_theme_after_reply_content', 'bbp_uploader_attachments');