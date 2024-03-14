<?php

// If uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

// Delete plugin settings
delete_option( 'wceb_booking_mode' );
delete_option( 'wceb_all_bookable' );
delete_option( 'wceb_number_of_dates' );
delete_option( 'wceb_booking_duration' );
delete_option( 'wceb_custom_booking_duration' );
delete_option( 'wceb_booking_min' );
delete_option( 'wceb_booking_max' );
delete_option( 'wceb_first_available_date' );
delete_option( 'wceb_last_available_date' );
delete_option( 'wceb_calendar_theme' );
delete_option( 'wceb_background_color' );
delete_option( 'wceb_main_color' );
delete_option( 'wceb_text_color' );
delete_option( 'wceb_set_start_booking_status' );
delete_option( 'wceb_keep_start_status_for' );
delete_option( 'wceb_set_processing_booking_status' );
delete_option( 'wceb_set_end_status' );
delete_option( 'wceb_keep_end_status_for' );
delete_option( 'wceb_set_completed_booking_status' );
delete_option( 'easy_booking_db_version' );

// Delete db entries
global $wpdb;

$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_key = %s", "_bookable" ) );
$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_key = %s", "_number_of_dates" ) );
$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_key = %s", "_booking_min" ) );
$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_key = %s", "_booking_max" ) );
$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_key = %s", "_first_available_date" ) );
$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_key = %s", "_booking_duration" ) );