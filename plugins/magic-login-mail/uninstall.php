<?php
/**
 * Uninstall
 *
 * @package Magic Login Mail
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

global $wpdb;

/* For Single site */
if ( ! is_multisite() ) {
	delete_option( 'magic_login_mail_valid_errors' );
	delete_option( 'magic_login_mail_email_errors' );
	delete_option( 'magic_login_mail_email_success' );
	$blogusers = get_users( array( 'fields' => array( 'ID' ) ) );
	foreach ( $blogusers as $user ) {
		delete_user_option( $user->ID, 'magic_login_mail_' . $user->ID, false );
		delete_user_option( $user->ID, 'magic_login_mail_' . $user->ID . '_expiration', false );
	}
} else {
	/* For Multisite */
	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->prefix}blogs" );
	$original_blog_id = get_current_blog_id();
	foreach ( $blog_ids as $blogid ) {
		switch_to_blog( $blogid );
		delete_option( 'magic_login_mail_valid_errors' );
		delete_option( 'magic_login_mail_email_errors' );
		delete_option( 'magic_login_mail_email_success' );
		$blogusers = get_users(
			array(
				'blog_id' => $blogid,
				'fields' => array( 'ID' ),
			)
		);
		foreach ( $blogusers as $user ) {
			delete_user_option( $user->ID, 'magic_login_mail_' . $user->ID, false );
			delete_user_option( $user->ID, 'magic_login_mail_' . $user->ID . '_expiration', false );
		}
	}
	switch_to_blog( $original_blog_id );
}
