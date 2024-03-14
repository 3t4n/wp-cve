<?php
/**
 * Plugin Name: Simple Comment Notification
 * Plugin URI: https://beherit.pl/en/wordpress/simple-comment-notification/
 * Description: Sends an simply email notification to the comment author, when someone replies to his comment.
 * Version: 1.2.4
 * Requires at least: 4.6
 * Requires PHP: 7.0
 * Author: Krzysztof Grochocki
 * Author URI: https://beherit.pl/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: simple-comment-notification
 * Domain Path: /languages
 */

// Sends email notification when comment is inserted and is already approved
function scn_insert_comment_notify($comment_id, $comment_object) {
	// Comment approved and have parent comments
    if(($comment_object->comment_approved == 1) && ($comment_object->comment_parent > 0) && (apply_filters('scn_notify_parent_author', true) == true)) {		
		// Get status of subscription functionality
		$subscription = apply_filters('scn_enable_subscription', false);
		// Check subscribe status
		if(($subscription == false) || (($subscription == true) && (get_comment_meta($comment_object->comment_parent, 'scn_subscription', true) == 'scn_subscribe'))) {			
			// Get parent comment data
			$comment_parent = get_comment($comment_object->comment_parent);
			// Email data
			$subject = '['.get_bloginfo().'] '.__('New reply to your comment', 'simple-comment-notification');
			$body = sprintf(__('Hey %s,<br><br>%s replied to your comment on %s. Comment content:<br><br>%s<br><br>Reply to this comment: %s', 'simple-comment-notification'), $comment_parent->comment_author, $comment_object->comment_author, '<a href="'.get_permalink($comment_parent->comment_post_ID).'">'.get_the_title($comment_parent->comment_post_ID).'</a>', $comment_object->comment_content, '<a href="'.get_comment_link($comment_object->comment_ID).'">'.get_comment_link($comment_object->comment_ID).'</a>');
			$headers[] = 'From: '.get_bloginfo().' <'.get_option('admin_email').'>';
			$headers[] = 'Content-Type: text/html; charset=UTF-8';
			// Send email
			wp_mail($comment_parent->comment_author.' <'.$comment_parent->comment_author_email.'>', $subject, $body, $headers);
		}
    }
}
add_action('wp_insert_comment', 'scn_insert_comment_notify', 99, 2);

// Optional: sends autoresponder to the author of the comment
function scn_insert_comment_autoresponder($comment_id, $comment_object) {
	// Check user capability
	if(!current_user_can(apply_filters('scn_autoresponder_cap', 'edit_posts')) && (apply_filters('scn_autoresponder_to_author', false) == true)) {
		// Email data
		$subject = '['.get_bloginfo().'] '.sprintf(__('Thanks for your comment on %s', 'simple-comment-notification'), '"'.get_the_title($comment_object->comment_post_ID).'"');
		$subject = apply_filters('scn_autoresponder_subject', $subject, $comment_object);
		$body = sprintf(__('Hey %s,<br><br>Thank you for your comment on %s.<br><br>We will try to respond on it as quickly as possible.<br><br>Greetings,<br>%s', 'simple-comment-notification'), $comment_object->comment_author, '<a href="'.get_permalink($comment_object->comment_post_ID).'">'.get_the_title($comment_object->comment_post_ID).'</a>', get_bloginfo());
		$body = apply_filters('scn_autoresponder_body', $body, $comment_object);
		$headers[] = 'From: '.get_bloginfo().' <'.get_option('admin_email').'>';
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		// Send email
        wp_mail($comment_object->comment_author.' <'.$comment_object->comment_author_email.'>', $subject, $body, $headers);
	}
}
add_action('wp_insert_comment', 'scn_insert_comment_autoresponder', 99, 2);

// Sends email notification when comment gets approved
function scn_comment_status_changed($comment_id, $comment_status) {
	// Check comment status
	if($comment_status == 'approve') {
		// Run function for wp_insert_comment hook
		scn_insert_comment_notify($comment_id, get_comment($comment_id));
	}
}
add_action('wp_set_comment_status', 'scn_comment_status_changed', 99, 2);

// Optional: add subscribe checkbox
function scn_custom_comment_form_fields($default) {
	if(apply_filters('scn_enable_subscription', false) == true) {
		$scr = '<input name="scn_subscription" id="scn_subscription" type="checkbox" value="scn_subscribe" ' . checked(apply_filters('scn_subscribe_value', true), true, false) . '><label for="scn_subscription">' . __('Notify me of follow-up replies via email', 'simple-comment-notification') . '</label>';
		echo $scr;
	}
}
add_action('comment_form_after_fields', 'scn_custom_comment_form_fields');

// Optional: save subscribe status
function save_comment_meta_data($comment_id) {
	if(apply_filters('scn_enable_subscription', false) == true) {
		add_comment_meta($comment_id, 'scn_subscription', $_POST['scn_subscription']);
	}
}
add_action('comment_post', 'save_comment_meta_data');
