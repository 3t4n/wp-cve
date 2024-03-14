<?php

// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) 
{
    die;
}

$timestamp = wp_next_scheduled( 'houzezpropertyfeedcronhook' );
wp_unschedule_event($timestamp, 'houzezpropertyfeedcronhook' );
wp_clear_scheduled_hook('houzezpropertyfeedcronhook');

$timestamp = wp_next_scheduled( 'houzezpropertyfeedreconcilecronhook' );
wp_unschedule_event($timestamp, 'houzezpropertyfeedreconcilecronhook' );
wp_clear_scheduled_hook('houzezpropertyfeedreconcilecronhook');

delete_option( 'houzez_property_feed' );

global $wpdb;
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}houzez_property_feed_logs_instance" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}houzez_property_feed_logs_instance_log" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}houzez_property_feed_media_queue" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}houzez_property_feed_export_logs_instance" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}houzez_property_feed_export_logs_instance_log" );