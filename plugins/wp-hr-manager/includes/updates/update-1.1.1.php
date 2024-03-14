<?php
/**
 * Update existing tables
 *
 * @package WPHR/Accounting
 *
 * @since 1.1.1
 *
 * @return void
 */
function wphr_ac_table_update_1_1_1() {
    global $wpdb;

    $table = $wpdb->prefix . 'wphr_ac_transactions';
    $cols = $wpdb->get_col( "DESC " . $table );

    if ( ! in_array( 'invoice_number', $cols ) ) {
        $wpdb->query( "ALTER TABLE $table ADD `invoice_number` varchar(200) NOT NULL AFTER `trans_total`" );
    }
}


/**
 * Update existing tables
 *
 * @package WPHR/CRM
 *
 * @since 1.1.1
 *
 * @return void
 */
function wphr_crm_update_1_1_1_table_column() {
    global $wpdb;

    $activity_tb        = $wpdb->prefix . 'wphr_crm_customer_activities';
    $activity_tb_col    = $wpdb->get_col( "DESC " . $activity_tb );

    if ( ! in_array( 'sent_notification', $activity_tb_col ) ) {
        $wpdb->query( "ALTER TABLE {$wpdb->prefix}wphr_crm_customer_activities ADD `sent_notification` TINYINT(4) DEFAULT '0' AFTER `extra`" );
    }
}

wphr_ac_table_update_1_1_1();
wphr_crm_update_1_1_1_table_column();

