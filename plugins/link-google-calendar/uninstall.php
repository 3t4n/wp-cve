<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

delete_option( 'link_google_calendar_textarea' );
delete_option( 'link_google_calendar_textarea_1' );
delete_option( 'link_google_calendar_textarea_2' );
delete_option( 'link_google_calendar_textarea_3' );
delete_option( 'link_google_calendar_textarea_4' );
delete_option( 'link_google_calendar_textarea_5' );
delete_option( 'num_of_calendars' );

global $wpdb;
$wpdb->query( "DELETE meta FROM {$wpdb->usermeta} meta WHERE meta_key = 'link_google_calendar_ignore_notice';" );
