<?php
/**
 * Add private column in wphr_crm_contact_group
 *
 * @since 1.2.2
 *
 * @return void
 */
function wphr_crm_contact_group_add_private_column_1_2_2() {
    global $wpdb;

    $table = $wpdb->prefix . 'wphr_crm_contact_group';
    $cols  = $wpdb->get_col( "DESC $table");
    if ( ! in_array( 'private', $cols ) ) {
        $wpdb->query( "ALTER TABLE $table ADD `private` TINYINT(1) DEFAULT NULL AFTER `description`" );
    }
}

wphr_crm_contact_group_add_private_column_1_2_2();

/**
 * Get hash from contact subscriber table and store them in meta tahble as `hash`
 *
 * @since 1.2.2
 *
 * @return void
 */
function wphr_crm_contact_subscriber_get_hashes_1_2_2() {
    global $wpdb;

    $table = $wpdb->prefix . 'wphr_crm_contact_subscriber';
    $cols  = $wpdb->get_col( "DESC $table");

    if ( in_array( 'hash', $cols ) ) {
        $subscribers = $wpdb->get_results( "SELECT user_id, hash from {$wpdb->prefix}wphr_crm_contact_subscriber GROUP BY user_id" );

        if ( ! empty( $subscribers ) ) {
            foreach ( $subscribers as $subscriber ) {
                if ( ! wphr_people_get_meta( $subscriber->user_id, 'hash', false ) ) {
                    $hash = $subscriber->hash ?
                                $subscriber->hash :
                                sha1( microtime() . 'wphr-contact-subscriber' . $subscriber->user_id );

                    wphr_people_update_meta( $subscriber->user_id, 'hash', $hash );
                }
            }
        }
    }
}

wphr_crm_contact_subscriber_get_hashes_1_2_2();
