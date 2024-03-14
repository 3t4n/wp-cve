<?php
/**
 * Update wphr Tables
 *
 * @since  1.1.6
 *
 * @return void
 */
function wphr_update_table_1_1_6() {
    global $wpdb;

    // Transaction item tables quantity column type change from tinyint(5) to int(11)
    $table_name = $wpdb->prefix . 'wphr_ac_transaction_items';
    $wpdb->query( "ALTER TABLE $table_name CHANGE `qty` `qty` int(11) unsigned NOT NULL DEFAULT '1';" );

    // Add email_status column in wphr_hr_announcement table
    $table_name   = $wpdb->prefix . 'wphr_hr_announcement';
    $columns = $wpdb->get_col( "DESC $table_name" );
    if ( ! in_array( 'email_status', $columns ) ) {
        $wpdb->query( "ALTER TABLE $table_name ADD `email_status` VARCHAR(30) NOT NULL AFTER `status`;" );
    }

    // Add data_id column in wphr_audit_log table
    $table_name = $wpdb->prefix . 'wphr_audit_log';
    $columns  = $wpdb->get_col( "DESC $table_name" );
    if( ! in_array( 'data_id', $columns ) ) {
        $wpdb->query( "ALTER TABLE $table_name ADD `data_id` BIGINT( 20 ) NULL DEFAULT NULL AFTER `sub_component`;" );
    }
}

wphr_update_table_1_1_6();
