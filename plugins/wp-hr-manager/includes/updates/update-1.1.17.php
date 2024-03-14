<?php
/**
 * Update wphr Table
 *
 * @since 1.1.17
 *
 * @return void
 */
function wphr_crm_update_table_1_1_17() {
    global $wpdb;

    // Add hash column in `wphr_crm_contact_subscriber` table
    $table = $wpdb->prefix . 'wphr_crm_contact_subscriber';
    $cols  = $wpdb->get_col( "DESC $table");

    if ( ! in_array( 'hash', $cols ) ) {
        $wpdb->query( "ALTER TABLE $table ADD `hash` VARCHAR(40) NULL DEFAULT NULL AFTER `unsubscribe_at`;" );

        // insert default wphr subscription form settings
        $args = [
            'post_title' => __( 'wphr Subscription', 'wphr' ),
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'page',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
        ];

        $page_id = wp_insert_post( $args );

        $settings = [
            'is_enabled'            => 'yes',
            'email_subject'         => sprintf( __( 'Confirm your subscription to %s', 'wphr' ), get_bloginfo( 'name' ) ),
            'email_content'         => sprintf(
                __( "Hello!\n\nThanks so much for signing up for our newsletter.\nWe need you to activate your subscription to the list(s): [contact_groups_to_confirm] by clicking the link below: \n\n[activation_link]Click here to confirm your subscription.[/activation_link]\n\nThank you,\n\n%s", 'wphr' ),
                get_bloginfo( 'name' )
            ),
            'page_id'               => $page_id,
            'confirm_page_title'    => __( 'You are now subscribed!', 'wphr' ),
            'confirm_page_content'  => __( "We've added you to our email list. You'll hear from us shortly.", 'wphr' ),
            'unsubs_page_title'     => __( 'You are now unsubscribed', 'wphr' ),
            'unsubs_page_content'   => __( 'You are successfully unsubscribed from list(s):', 'wphr' ),
        ];

        update_option( 'wphr_settings_wphr-crm_subscription', $settings );

    }

    // Change `pay_rate` column's data type in `wphr_hr_employees` table
    $table = $wpdb->prefix . 'wphr_hr_employees';
    $cols  = $wpdb->get_col( "DESC $table");

    if ( in_array( 'pay_rate', $cols ) ) {
        $wpdb->query( "ALTER TABLE $table CHANGE `pay_rate` `pay_rate` DECIMAL(11,2) UNSIGNED NOT NULL DEFAULT '0.00';" );
    }
}

//wphr_crm_update_table_1_1_17();
