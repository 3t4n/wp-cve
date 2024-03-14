<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if( !class_exists( 'WPPMCron' ) ) :

    final class WPPMCron {
        public static function init() {
            add_action( 'init', array( __CLASS__, 'wppm_schedule_events') );
        }

        public static function wppm_schedule_events(){
            // Schedule cron job for every minute events
            if ( !wp_next_scheduled('wppm_cron_one_minute') ) {
                wp_schedule_event( 
                    time(), 
                    'wppm_1min', 
                    'wppm_cron_one_minute' 
                );
                //include( WPPM_ABSPATH.'includes/class-wppm_import_emails.php' );
            }

            // Schedule cron job for every five minute events
            if ( !wp_next_scheduled('wppm_cron_five_minute') ) {
                wp_schedule_event( 
                    time(), 
                    'wppm_5min', 
                    'wppm_cron_five_minute' 
                );
            }

            // Schedule cron job for daily events
            if ( !wp_next_scheduled('wppm_cron_daily') ) {
                wp_schedule_event( 
                    time(), 
                    'daily', 
                    'wppm_cron_daily' 
                );
            }
        }

        public static function wppm_unschedule_events(){
            // Remove every minute cron
            $timestamp = wp_next_scheduled( 'wppm_cron_one_minute' );
            if( $timestamp ) {
                wp_unschedule_event( $timestamp, 'wppm_cron_one_minute' );
            }

            // Remove every five minute cron
            $timestamp = wp_next_scheduled( 'wppm_cron_five_minute' );
            if( $timestamp ) {
                wp_unschedule_event( $timestamp, 'wppm_cron_five_minute' );
            }

            // Remove daily cron
            $timestamp = wp_next_scheduled( 'wppm_cron_daily' );
            if( $timestamp ) {
                wp_unschedule_event( $timestamp, 'wppm_cron_daily' );
            }
        }
    }
endif;
WPPMCron::init();