<?php

/**
 * Uninstalling MailerSend SMTP deletes options.
 *
 * @version 1.0.0
 */


// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// list of mailersend options
$options = array(
	'mailersend_smtp_user',
	'mailersend_smtp_pwd',
	'mailersend_config_mode',
	'mailersend_sender_name',
	'mailersend_sender_email',
	'mailersend_recipient_cc',
	'mailersend_recipient_bcc',
	'mailersend_reply_to'
);

// loop through list of options and delete
foreach ( $options as $option ) {
	if ( get_option( $option ) ) {
		delete_option( $option );
	}
}

// delete transients
delete_transient( 'mailersend_error' );
