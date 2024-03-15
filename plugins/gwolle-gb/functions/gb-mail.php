<?php
/*
 * Mail Functions
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Send the Notification Mail to moderators that have subscribed (only when it is not Spam).
 *
 * @param object $entry instance of gwolle_gb_entry
 *
 * @since 1.4.9
 */
function gwolle_gb_mail_moderators( $entry ) {
	$isspam = $entry->get_isspam();
	if ( ! $isspam ) {
		$subscribers = array();
		$recipients = get_option('gwolle_gb-notifyByMail');
		if ( is_string( $recipients ) && strlen($recipients) > 0 ) {
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

		// Set the Mail Content
		$mailtags = array( 'user_email', 'user_name', 'status', 'entry_management_url', 'blog_name', 'blog_url', 'wp_admin_url', 'entry_content', 'author_ip', 'author_origin' );
		$mail_body = gwolle_gb_sanitize_output( get_option( 'gwolle_gb-adminMailContent', false ), 'setting_textarea' );
		if ( ! $mail_body) {
			$mail_body = esc_html__('
Hello,

There is a new guestbook entry at %blog_name%.
You can log in and check it at %entry_management_url%.

Have a nice day.
Your Gwolle-GB-Mailer


Website address: %blog_url%
User name: %user_name%
User email: %user_email%
Entry status: %status%
Entry content:
%entry_content%
', 'gwolle-gb');
		}
		$mail_body = apply_filters( 'gwolle_gb_mail_moderators_body', $mail_body, $entry );

		// Set the Mail Headers
		$subject = '[' . gwolle_gb_format_values_for_mail(get_bloginfo('name')) . '] ' . esc_html__('New Guestbook Entry', 'gwolle-gb');
		$subject = apply_filters( 'gwolle_gb_mail_moderators_subject', $subject );

		$header = "Content-Type: text/plain; charset=UTF-8\r\n"; // Encoding of the mail.
		if ( get_option('gwolle_gb-mail-from', false) ) {
			$header .= 'From: ' . gwolle_gb_format_values_for_mail(get_bloginfo('name')) . ' <' . get_option('gwolle_gb-mail-from') . ">\r\n";
		}
		$author_email = $entry->get_author_email();
		if ( $author_email ) {
			$header .= 'Reply-To: "' . gwolle_gb_format_values_for_mail($entry->get_author_name()) . '" <' . $author_email . ">\r\n"; // Set Reply-To for easy answering.
		}

		// Replace the tags from the mailtemplate with real data from the website and entry
		$info = array();
		$info['user_name'] = gwolle_gb_sanitize_output( $entry->get_author_name() );
		$info['user_email'] = $author_email;
		$info['blog_name'] = get_bloginfo('name');
		$postid = gwolle_gb_get_postid( (int) $entry->get_book_id() );
		if ( $postid ) {
			$permalink = gwolle_gb_get_permalink( $postid );
			if ( is_wp_error( $permalink ) ) {
				$info['blog_url'] = get_bloginfo('wpurl') . '?p=' . $postid;
			} else {
				$info['blog_url'] = $permalink;
			}
		} else {
			$info['blog_url'] = get_bloginfo('wpurl');
		}

		$wpadmin = apply_filters( 'gwolle_gb_wpadmin_url', admin_url( 'admin.php' ) );
		$info['wp_admin_url'] = $wpadmin;
		$info['entry_management_url'] = $wpadmin . '?page=' . GWOLLE_GB_FOLDER . '/editor.php&entry_id=' . $entry->get_id();

		$info['entry_content'] = gwolle_gb_format_values_for_mail(gwolle_gb_sanitize_output( $entry->get_content(), 'content' ));
		$info['author_ip'] = gwolle_gb_get_user_ip();
		$info['author_origin'] = $entry->get_author_origin();
		if ( $entry->get_ischecked() ) {
			$info['status'] = esc_html__('Checked', 'gwolle-gb');
		} else {
			$info['status'] = esc_html__('Unchecked', 'gwolle-gb');
		}

		// The last tags are bloginfo-based
		$mailtags_count = count($mailtags);
		for ($tagnum = 0; $tagnum < $mailtags_count; $tagnum++) {
			$tagname = $mailtags["$tagnum"];
			$mail_body = str_replace('%' . $tagname . '%', $info["$tagname"], $mail_body);
		}
		$mail_body = gwolle_gb_format_values_for_mail( $mail_body );

		// Add logging to mail
		$log_entries = gwolle_gb_get_log_entries( $entry->get_id() );
		if ( is_array($log_entries) && ! empty($log_entries) ) {
			$mail_body .= "\r\n" . esc_html__('Log messages:', 'gwolle-gb') . "\r\n";
			if ($entry->get_datetime() > 0) {
				$mail_body .= date_i18n( get_option('date_format'), $entry->get_datetime() ) . ', ';
				$mail_body .= date_i18n( get_option('time_format'), $entry->get_datetime() );
				$mail_body .= ': ' . esc_html__('Written', 'gwolle-gb') . "\r\n";
			}
			foreach ($log_entries as $log_entry) {
				$mail_body .= $log_entry['msg_txt'] . "\r\n";
			}
		}

		if ( is_array($subscribers) && ! empty($subscribers) ) {
			foreach ( $subscribers as $subscriber ) {
				wp_mail($subscriber, $subject, $mail_body, $header);
			}
		}
	}
}
add_action( 'gwolle_gb_save_entry_frontend', 'gwolle_gb_mail_moderators' );


/*
 * Send Notification Mail to the author if set to true in an option (only when it is not Spam).
 *
 * @param object $entry instance of gwolle_gb_entry
 *
 * @since 1.4.9
 */
function gwolle_gb_mail_author( $entry ) {
	$isspam = $entry->get_isspam();
	if ( ! $isspam ) {
		if ( get_option( 'gwolle_gb-mail_author', 'false' ) === 'true' ) {

			// Set the Mail Content
			$mailtags = array( 'user_email', 'user_name', 'blog_name', 'blog_url', 'entry_content' );
			$mail_body = gwolle_gb_sanitize_output( get_option( 'gwolle_gb-authorMailContent', false ), 'setting_textarea' );
			if ( ! $mail_body) {
				$mail_body = esc_html__('
Hello,

You have just posted a new guestbook entry at %blog_name%.

Have a nice day.
The editors at %blog_name%.


Website address: %blog_url%
User name: %user_name%
User email: %user_email%
Entry content:
%entry_content%
', 'gwolle-gb');
			}
			$mail_body = apply_filters( 'gwolle_gb_mail_author_body', $mail_body, $entry );

			// Set the Mail Headers
			$subject = '[' . gwolle_gb_format_values_for_mail(get_bloginfo('name')) . '] ' . esc_html__('New Guestbook Entry', 'gwolle-gb');
			$subject = apply_filters( 'gwolle_gb_mail_author_subject', $subject );

			$header = "Content-Type: text/plain; charset=UTF-8\r\n"; // Encoding of the mail
			if ( get_option('gwolle_gb-mail-from', false) ) {
				$header .= 'From: ' . gwolle_gb_format_values_for_mail(get_bloginfo('name')) . ' <' . gwolle_gb_sanitize_output( get_option('gwolle_gb-mail-from') ) . ">\r\n";
			}

			// Replace the tags from the mailtemplate with real data from the website and entry
			$info = array();
			$info['user_name'] = gwolle_gb_sanitize_output( $entry->get_author_name() );
			$info['user_email'] = $entry->get_author_email();
			$info['blog_name'] = get_bloginfo('name');
			$postid = gwolle_gb_get_postid( (int) $entry->get_book_id() );
			if ( $postid ) {
				$permalink = gwolle_gb_get_permalink( $postid );
				if ( is_wp_error( $permalink ) ) {
					$info['blog_url'] = get_bloginfo('wpurl') . '?p=' . $postid;
				} else {
					$info['blog_url'] = $permalink;
				}
			} else {
				$info['blog_url'] = get_bloginfo('wpurl');
			}
			$info['entry_content'] = gwolle_gb_format_values_for_mail(gwolle_gb_sanitize_output( $entry->get_content(), 'content' ));
			$mailtags_count = count($mailtags);
			for ($tagnum = 0; $tagnum < $mailtags_count; $tagnum++) {
				$tagname = $mailtags["$tagnum"];
				$mail_body = str_replace('%' . $tagname . '%', $info["$tagname"], $mail_body);
			}
			$mail_body = gwolle_gb_format_values_for_mail( $mail_body );

			wp_mail($entry->get_author_email(), $subject, $mail_body, $header);

		}
	}
}
add_action( 'gwolle_gb_save_entry_frontend', 'gwolle_gb_mail_author' );


/*
 * Send Notification Mail to the author that the entry is moderated and published.
 *
 * @param object $entry instance of gwolle_gb_entry
 *
 * @since 4.1.0
 */
function gwolle_gb_mail_author_on_moderation( $entry ) {

	if (get_option( 'gwolle_gb-mail_author_moderation', 'false') !== 'true') {
		return;
	}

	if ( $entry->get_ischecked() === 1 && $entry->get_isspam() === 0 && $entry->get_istrash() === 0 ) {

		// Set the Mail Content
		$mailtags = array( 'user_email', 'user_name', 'blog_name', 'blog_url', 'entry_content', 'date' );
		$mail_body = gwolle_gb_sanitize_output( get_option( 'gwolle_gb-authormoderationcontent', false ), 'setting_textarea' );
		if ( ! $mail_body) {
			$mail_body = esc_html__('
Hello,

An admin has just moderated your guestbook entry at %blog_name%.

Have a nice day.
The editors at %blog_name%.


Website address: %blog_url%


Original entry posted on %date%:
%entry_content%
', 'gwolle-gb');
		}
		$mail_body = apply_filters( 'gwolle_gb_mail_author_on_moderation_body', $mail_body, $entry );

		// Set the Mail Headers
		$subject = '[' . gwolle_gb_format_values_for_mail(get_bloginfo('name')) . '] ' . esc_html__('Moderated', 'gwolle-gb');
		$subject = apply_filters( 'gwolle_gb_mail_author_on_moderation_subject', $subject );

		$header = "Content-Type: text/plain; charset=UTF-8\r\n"; // Encoding of the mail
		if ( get_option('gwolle_gb-mail-from', false) ) {
			$header .= 'From: ' . gwolle_gb_format_values_for_mail(get_bloginfo('name')) . ' <' . gwolle_gb_sanitize_output( get_option('gwolle_gb-mail-from') ) . ">\r\n";
		}

		// Replace the tags from the mailtemplate with real data from the website and entry
		$info = array();
		$info['user_name'] = gwolle_gb_sanitize_output( $entry->get_author_name() );
		$info['user_email'] = $entry->get_author_email();
		$info['blog_name'] = get_bloginfo('name');
		$postid = gwolle_gb_get_postid( (int) $entry->get_book_id() );
		if ( $postid ) {
			$permalink = gwolle_gb_get_permalink( $postid );
			if ( is_wp_error( $permalink ) ) {
				$info['blog_url'] = get_bloginfo('wpurl') . '?p=' . $postid;
			} else {
				$info['blog_url'] = $permalink;
			}
		} else {
			$info['blog_url'] = get_bloginfo('wpurl');
		}
		$info['entry_content'] = gwolle_gb_format_values_for_mail(gwolle_gb_sanitize_output( $entry->get_content(), 'content' ));
		$info['date'] = date_i18n( get_option('date_format'), $entry->get_datetime() );
		$mailtags_count = count($mailtags);
		for ($tagnum = 0; $tagnum < $mailtags_count; $tagnum++) {
			$tagname = $mailtags["$tagnum"];
			$mail_body = str_replace('%' . $tagname . '%', $info["$tagname"], $mail_body);
		}
		$mail_body = gwolle_gb_format_values_for_mail( $mail_body );

		wp_mail($entry->get_author_email(), $subject, $mail_body, $header);

	}
}


/*
 * Send Notification Mail to the author that there is an admin_reply (only when it is not Spam).
 *
 * @param object $entry instance of gwolle_gb_entry
 *
 * @since 1.4.9
 */
function gwolle_gb_mail_author_on_admin_reply( $entry ) {
	$isspam = $entry->get_isspam();
	if ( ! $isspam ) {

		// Set the Mail Content
		$mailtags = array( 'user_email', 'user_name', 'blog_name', 'blog_url', 'admin_reply', 'entry_content', 'date' );
		$mail_body = gwolle_gb_sanitize_output( get_option( 'gwolle_gb-mail_admin_replyContent', false ), 'setting_textarea' );
		if ( ! $mail_body) {
			$mail_body = esc_html__('
Hello,

An admin has just added or changed a reply message to your guestbook entry at %blog_name%.

Have a nice day.
The editors at %blog_name%.


Website address: %blog_url%
Admin Reply:
%admin_reply%


Original entry posted on %date%:
%entry_content%
', 'gwolle-gb');
		}
		$mail_body = apply_filters( 'gwolle_gb_mail_author_on_admin_reply_body', $mail_body, $entry );

		// Set the Mail Headers
		$subject = '[' . gwolle_gb_format_values_for_mail(get_bloginfo('name')) . '] ' . esc_html__('Admin Reply', 'gwolle-gb');
		$subject = apply_filters( 'gwolle_gb_mail_author_on_admin_reply_subject', $subject );

		$header = "Content-Type: text/plain; charset=UTF-8\r\n"; // Encoding of the mail
		if ( get_option('gwolle_gb-mail-from', false) ) {
			$header .= 'From: ' . gwolle_gb_format_values_for_mail(get_bloginfo('name')) . ' <' . gwolle_gb_sanitize_output( get_option('gwolle_gb-mail-from') ) . ">\r\n";
		}

		// Replace the tags from the mailtemplate with real data from the website and entry
		$info = array();
		$info['user_name'] = gwolle_gb_sanitize_output( $entry->get_author_name() );
		$info['user_email'] = $entry->get_author_email();
		$info['blog_name'] = get_bloginfo('name');
		$postid = gwolle_gb_get_postid( (int) $entry->get_book_id() );
		if ( $postid ) {
			$permalink = gwolle_gb_get_permalink( $postid );
			if ( is_wp_error( $permalink ) ) {
				$info['blog_url'] = get_bloginfo('wpurl') . '?p=' . $postid;
			} else {
				$info['blog_url'] = $permalink;
			}
		} else {
			$info['blog_url'] = get_bloginfo('wpurl');
		}
		$info['admin_reply'] = gwolle_gb_format_values_for_mail(gwolle_gb_sanitize_output( $entry->get_admin_reply(), 'admin_reply' ));
		$info['entry_content'] = gwolle_gb_format_values_for_mail(gwolle_gb_sanitize_output( $entry->get_content(), 'content' ));
		$info['date'] = date_i18n( get_option('date_format'), $entry->get_datetime() );
		$mailtags_count = count($mailtags);
		for ($tagnum = 0; $tagnum < $mailtags_count; $tagnum++) {
			$tagname = $mailtags["$tagnum"];
			$mail_body = str_replace('%' . $tagname . '%', $info["$tagname"], $mail_body);
		}
		$mail_body = gwolle_gb_format_values_for_mail( $mail_body );

		wp_mail($entry->get_author_email(), $subject, $mail_body, $header);

	}
}

/*
 * Add moderation link to Notification Mail for direct checking.
 *
 * @param string $mail_body text with the email body text.
 * @param object $entry instance of gwolle_gb_entry.
 *
 * @return string $mail_body text with the email body text with added check link.
 *
 * @since 4.6.2
 */
function gwolle_gb_mail_moderators_body_add_check_link( $mail_body, $entry ) {

	global $wp_hasher;

	if ( is_object( $entry ) && is_a( $entry, 'gwolle_gb_entry' ) ) {

		$entry_id = $entry->get_id();
		$key = wp_generate_password( 20, false );
		if ( empty( $wp_hasher ) ) {
			require_once ABSPATH . WPINC . '/class-phpass.php';
			$wp_hasher = new PasswordHash( 8, true );
		}
		$key = time() . ':' . $wp_hasher->HashPassword( $key );
		$key = sanitize_key( $key );

		set_transient( 'gwolle_gb_check_key_' . $entry_id, $key, DAY_IN_SECONDS );

		$ajaxurl = admin_url('admin-ajax.php');
		$ajaxurl = add_query_arg( 'action', 'gwolle_gb_check_by_email', $ajaxurl );
		$ajaxurl = add_query_arg( 'entry_id', $entry_id, $ajaxurl );
		$ajaxurl = add_query_arg( 'key', $key, $ajaxurl );

		$mail_body .= "\r\n\r\n" . esc_html__('For quick checking this entry, click the next link.', 'gwolle-gb');
		$mail_body .= "\r\n" . $ajaxurl;

	}

	return $mail_body;

}
add_filter( 'gwolle_gb_mail_moderators_body', 'gwolle_gb_mail_moderators_body_add_check_link', 10, 2 );


/*
 * Callback function for handling the Ajax request for checking an entry directly, from the moderation email in gwolle_gb_mail_moderators_body_add_check_link().
 *
 * @since 4.6.2
 */
function gwolle_gb_gwolle_gb_check_by_email() {

	$entry_id = (int) $_GET['entry_id'];
	if ( isset( $entry_id ) && $entry_id > 0 ) {
		$entry = new gwolle_gb_entry();
		$result = $entry->load( $entry_id );
		if ( ! $result ) {
			esc_html_e('error, no such entry.', 'gwolle-gb');
			die();
		}
	}

	$email_key = sanitize_key( $_GET['key'] );
	$transient_key = get_transient( 'gwolle_gb_check_key_' . $entry_id );
	if ( false === $transient_key ) {
		esc_html_e('error, the key has expired. please log in and moderate manually.', 'gwolle-gb');
		die();
	}

	if ( $email_key !== $transient_key ) {
		esc_html_e('error, invalid key. please log in and moderate manually.', 'gwolle-gb');
		die();
	}

	if ( $entry->get_ischecked() === 0 ) {
		$entry->set_ischecked( true );
		$user_id = get_current_user_id(); // returns 0 if no current user
		$result = $entry->save();
		if ( $result ) {
			gwolle_gb_add_log_entry( $entry->get_id(), 'entry-checked-by-email' );
			gwolle_gb_mail_author_on_moderation( $entry );
		} else {
			esc_html_e('error, entry could not be saved. please log in and moderate manually.', 'gwolle-gb');
			die();
		}
	} else {
		esc_html_e('entry was already moderated.', 'gwolle-gb');
		die();
	}

	esc_html_e('entry is now moderated and visible.', 'gwolle-gb');
	die();

}
add_action( 'wp_ajax_gwolle_gb_check_by_email', 'gwolle_gb_gwolle_gb_check_by_email' );
add_action( 'wp_ajax_nopriv_gwolle_gb_check_by_email', 'gwolle_gb_gwolle_gb_check_by_email' );
