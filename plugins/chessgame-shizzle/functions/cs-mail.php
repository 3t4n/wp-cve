<?php
/*
 * Mail Functions
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Send the Notification Mail to moderators that have subscribed.
 *
 * @param $post post that has been submitted and saved.
 *
 * @since 1.0.3
 */
function chessgame_shizzle_mail_moderators( $post_id ) {
	$post = get_post( $post_id );

	$subscribers = array();
	$recipients = get_option('chessgame_shizzle-notifybymail', array() );
	if ( is_string($recipients) && strlen($recipients) > 0 ) {
		$recipients = explode( ',', $recipients );
	}
	if ( is_array( $recipients ) && count( $recipients ) > 0 ) {
		foreach ( $recipients as $recipient ) {
			if ( is_numeric($recipient) ) {
				$userdata = get_userdata( (int) $recipient );
				$subscribers[] = $userdata->user_email;
			}
		}
	} else {
		return;
	}

	// Set the initial Mail Content.
	$mail_body = esc_html__('
Hello,

There is a new chessgame submitted at %blog_name%.
You can check it at %management_url%.

Have a nice day.
Your Chessgame mailer.


Submitter: %submitter%
Title: %title%
White player: %white%
Black player: %black%
PGN data:
%pgn_data%
', 'chessgame-shizzle');

	// Set the Mail Headers.
	$subject = '[' . chessgame_shizzle_format_values_for_mail(get_bloginfo('name')) . '] ' . esc_html__('New chessgame', 'chessgame-shizzle');
	$subject = apply_filters( 'chessgame_shizzle_mail_moderators_subject', $subject );

	$header = '';
	if ( get_option('chessgame_shizzle-mail-from', false) ) {
		$header .= 'From: ' . chessgame_shizzle_format_values_for_mail(get_bloginfo('name')) . ' <' . get_option('chessgame_shizzle-mail-from') . ">\r\n";
	} else {
		$header .= 'From: ' . chessgame_shizzle_format_values_for_mail(get_bloginfo('name')) . ' <' . get_bloginfo('admin_email') . ">\r\n";
	}
	$header .= "Content-Type: text/plain; charset=UTF-8\r\n"; // Encoding of the mail.

	$info = array();
	// Replace the tags from the mailtemplate with real data.
	$info['blog_name'] = chessgame_shizzle_format_values_for_mail( get_bloginfo('name') );

	$wpadmin = apply_filters( 'chessgame_shizzle_wpadmin_url', admin_url() );
	$info['management_url'] = $wpadmin . '/post.php?post=' . $post_id . '&action=edit';

	$info['title'] = get_the_title( $post );
	$info['white'] = get_post_meta($post_id, 'cs_chessgame_white_player', true);
	$info['black'] = get_post_meta($post_id, 'cs_chessgame_black_player', true);
	$info['pgn_data'] = get_post_meta($post_id, 'cs_chessgame_pgn', true);
	$info['submitter'] = get_post_meta($post_id, 'cs_chessgame_submitter', true);

	$mailtags = array( 'blog_name', 'management_url', 'submitter', 'title', 'white', 'black', 'pgn_data' );
	$mailtags_count = count($mailtags);
	for ($tagnum = 0; $tagnum < $mailtags_count; $tagnum++) {
		$tagname = $mailtags["$tagnum"];
		$mail_body = str_replace( '%' . $tagname . '%', $info["$tagname"], $mail_body );
	}
	$mail_body = chessgame_shizzle_format_values_for_mail( $mail_body );

	// Send the mail.
	if ( is_array($subscribers) && ! empty($subscribers) ) {
		foreach ( $subscribers as $subscriber ) {
			wp_mail($subscriber, $subject, $mail_body, $header);
		}
	}
}
add_action( 'chessgame_shizzle_save_frontend', 'chessgame_shizzle_mail_moderators' );
