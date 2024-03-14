<?php
defined( 'ABSPATH' ) || exit;

class TOCHATBE_Admin_Export_CSV {

    public function __construct() {
        add_action( 'admin_init', array( $this, 'export_click_log_csv' ) );
    }

    public function export_click_log_csv() {
        if ( ! isset( $_GET['tochatbe_export_click_log'] ) || ! wp_verify_nonce( $_GET['tochatbe_export_click_log'], 'tochatbe_export_click_log' ) ) {
            return;
        }

        global $wpdb;

        $filename = 'click-log-' . date( 'Y-m-d' ) . '.csv';

        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=' . $filename );

        $output = fopen( 'php://output', 'w' );

        fputcsv( $output, array( 'IP Address', 'Message', 'Contacted To', 'User', 'Referral', 'Device Type', 'Timestamp' ) );

        // Get all logs.
        $logs = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}tochatbe_log ORDER BY ID DESC" );

        foreach ( $logs as $log ) {
            $ip           = wp_kses_post( $log->ip );
            $message      = wp_kses_post( $log->message );
            $contacted_to = wp_kses_post( $log->contacted_to );
            $user         = wp_kses_post( $log->user );
            $referral     = wp_kses_post( $log->referral );
            $timestamp    = wp_kses_post( $log->timestamp );

            fputcsv( $output, array( $ip, $message, $contacted_to, $user, $referral, $timestamp ) );
        }

        fclose( $output );

        exit;
    }

}

return new TOCHATBE_Admin_Export_CSV();