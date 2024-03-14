<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// sending and saving form submission
if ($error == false) {
	if ( ( isset($banned_words) || isset($message_has_links) || isset($message_has_email) ) && ($ignore_submission == 'yes') ) {
		$sent = true;
	} else {
		// hook to support plugin Contact Form DB
		do_action( 'vscf_before_send_mail', $form_data );
		// email address
		$to = '';
		if ( !empty($email_attribute) ) {
			if (strpos($email_attribute, ',') !== false) {
				$email_list_clean = array();
				$email_list = explode(',', $email_attribute);
				foreach ( $email_list as $email_single ) {
					$email_clean = sanitize_email( $email_single );
					if ( is_email( $email_clean ) ) {
						$email_list_clean[] = $email_clean;
					}
				}
				if ( count($email_list_clean) < 6 ) {
					$to = implode(',', $email_list_clean);
				}
			} else {
				$email_clean = sanitize_email( $email_attribute );
				if ( is_email( $email_clean ) ) {
					$to = $email_clean;
				}
			}
		}
		if ( empty($to) ) {
			if ( is_email($email_settings_page) ) {
				$to = $email_settings_page;
			} else {
				$to = $email_address;
			}
		}
		// from email header
		if ( is_email($from_header_attribute) ) {
			$from = $from_header_attribute;
		} elseif ( is_email($from_header) ) {
			$from = $from_header;
		} elseif ( is_email($email_settings_page) ) {
			$from = $email_settings_page;
		} else {
			$from = $email_address;
		}
		// reply-to email address
		if ( is_email($email_settings_page) ) {
			$reply_to = $email_settings_page;
		} else {
			$reply_to = $email_address;
		}
		// subject for email
		if (!empty($subject_attribute)) {
			$subject = $subject_attribute;
		} elseif (!empty($subject_settings_page) ) {
			$subject = $subject_settings_page;
		} elseif ($disable_subject != 'yes') {
			$subject = $blog_name.' - '.$form_data['form_subject']."\r\n\r\n";
		} else {
			$subject = $blog_name.' - '.__( 'Form submission', 'very-simple-contact-form' );
		}
		// subject for auto-reply email
		if (!empty($subject_auto_reply_attribute)) {
			$subject_auto_reply = $subject_auto_reply_attribute;
		} elseif (!empty($subject_auto_reply_settings_page) ) {
			$subject_auto_reply = $subject_auto_reply_settings_page;
		} elseif ($disable_subject != 'yes') {
			$subject_auto_reply = $blog_name.' - '.$form_data['form_subject']."\r\n\r\n";
		} else {
			$subject_auto_reply = $blog_name.' - '.__( 'Form submission', 'very-simple-contact-form' );
		}
		// subject from sender
		if ($disable_subject != 'yes') {
			$subject_from_sender = $form_data['form_subject']."\r\n\r\n";
		} else {
			$subject_from_sender = '';
		}
		// auto-reply message
		$message_auto_reply = htmlspecialchars_decode($auto_reply_message, ENT_QUOTES);
		// show or hide privacy consent
		if ($disable_privacy != 'yes') {
			$privacy_consent = "\r\n\r\n".sprintf( __( 'Privacy consent: %s', 'very-simple-contact-form' ), $privacy_label );
		} else {
			$privacy_consent = '';
		}
		// show or hide ip address
		if ($disable_ip_address == 'yes') {
			$ip_address = '';
		} else {
			$ip_address = "\r\n\r\n".sprintf( __( 'IP: %s', 'very-simple-contact-form' ), vscf_get_the_ip() );
		}
		// save form submission in database
		if ( $list_submissions == 'yes' ) {
			$vscf_post_information = array(
				'post_title' => wp_strip_all_tags($subject),
				'post_content' => $form_data['form_name']."\r\n\r\n".$form_data['form_email']."\r\n\r\n".$subject_from_sender.$form_data['form_message'].$privacy_consent.$ip_address,
				'post_type' => 'submission',
				'post_status' => 'pending',
				'meta_input' => array( "name_sub" => $form_data['form_name'], "email_sub" => $form_data['form_email'] )
			);
			$post_id = wp_insert_post($vscf_post_information);
		}
		// email
		$content = $form_data['form_name']."\r\n\r\n".$form_data['form_email']."\r\n\r\n".$subject_from_sender.$form_data['form_message'].$privacy_consent.$ip_address;
		$headers = "Content-Type: text/plain; charset=UTF-8" . "\r\n";
		$headers .= "From: ".$form_data['form_name']." <".$from.">" . "\r\n";
		$headers .= "Reply-To: <".$form_data['form_email'].">" . "\r\n";
		$auto_reply_content = $message_auto_reply."\r\n\r\n".$form_data['form_name']."\r\n\r\n".$form_data['form_email']."\r\n\r\n".$subject_from_sender.$form_data['form_message'];
		$auto_reply_headers = "Content-Type: text/plain; charset=UTF-8" . "\r\n";
		$auto_reply_headers .= "From: ".$blog_name." <".$from.">" . "\r\n";
		$auto_reply_headers .= "Reply-To: <".$reply_to.">" . "\r\n";
		if ($disable_mail == 'yes') {
			$mail_sends = true;
		} else {
			if ( wp_mail($to, wp_strip_all_tags($subject), $content, $headers) ) {
				$mail_sends = true;
			} else {
				$mail_fails = true;
			}
		}
		if ($auto_reply_mail == 'yes') {
			if ( wp_mail($form_data['form_email'], wp_strip_all_tags($subject_auto_reply), $auto_reply_content, $auto_reply_headers) ) {
				$mail_sends = true;
			} else {
				$mail_fails = true;
			}
		}
		if ($mail_fails == true) {
			$fail = true;
		} elseif ($mail_sends == true) {
			$sent = true;
		}
	}
}
