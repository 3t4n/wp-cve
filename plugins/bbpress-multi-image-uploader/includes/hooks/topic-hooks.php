<?php

if( !defined('ABSPATH') ){
	exit;
}

/**
 * Injects uploader markup and area before submit button wrapper.
 */
add_action( 'bbp_theme_before_topic_form_submit_wrapper', 'bbp_topic_uploader_area' );

/**
 * Adds attachments to the topic once it is created.
 * 
 * @param int $topic_id ID of newly created topic
 */
add_action( 'bbp_new_topic_post_extras', 'bbp_uploader_topic_created' ); // When creating new topic
add_action( 'bbp_edit_topic_post_extras', 'bbp_uploader_topic_created' ); // When editing topic

/**
 * Previews already added attachments on edit topic screen
 */
add_action( 'bbp_uploader_topic_img_container', 'bbp_topic_img_container' );

/**
 * Adds images after content of topic and reply.
 */
add_action('bbp_theme_after_topic_content', 'bbp_uploader_attachments');