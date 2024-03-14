<?php

/**
 * Check Is Mail Chimp Enabled.
 */
function youzify_is_mailchimp_active() {

    // Check if MailChimp Sync is Enabled.
    if ( youzify_option( 'youzify_enable_mailchimp', 'off' ) == 'off' ) {
        return false;
    }

    // Get Mailchimp API Key.
    if ( empty( youzify_option( 'youzify_mailchimp_api_key' ) ) ) {
        return false;
    }

    // Check Mailchimp List ID.
    if ( empty( youzify_option( 'youzify_mailchimp_list_id' ) ) ) {
        return false;
    }

    return true;

}

/**
 * Subscribe Registered User to MailChimp.
 */
function youzify_subscribe_user_to_mailchimp( $user_id) {

    // Check if Mail Chimp is active.
    if ( ! youzify_is_mailchimp_active() ) {
        return false;
    }

    // Get User Infos.
    $user_info = get_userdata( $user_id );

    if ( ! is_object( $user_info ) ) {
        return false;
    }

    // Get User Data
    $user_data = array(
        'status'    => 'subscribed',
        'email'     => $user_info->user_email,
        'firstname' => $user_info->first_name,
        'lastname'  => $user_info->last_name
    );

    // Get List ID.
    $list_id = youzify_option( 'youzify_mailchimp_list_id' );

    // Add User To Mailchimp List.
    youzify_syncMailchimp( $list_id, $user_data );

};

add_action( 'bp_core_activated_user', 'youzify_subscribe_user_to_mailchimp', 10 );

/**
 * Add User To Mailchimp List
 */
function youzify_syncMailchimp( $list_id, $data ) {

    // Get API Key.
    $apiKey = youzify_option( 'youzify_mailchimp_api_key' );

    // Get Member ID.
    $memberId = md5( strtolower( $data['email'] ) );

    // Get Data Center
    $dataCenter = substr( $apiKey, strpos( $apiKey, '-' ) + 1 );

    $args = array(
        'method' => 'PUT',
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode( 'user:' . $apiKey )
        ),
        'body' => json_encode(
            array(
                'email_address' => $data['email'],
                'status'        => $data['status'],
                'merge_fields'  => array(
                    'FNAME'     => $data['firstname'],
                    'LNAME'     => $data['lastname']
                )
            )
        )
    );

    // Get Response.
    $response = wp_remote_post( 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . $memberId, $args );

    return wp_remote_retrieve_response_code( $response );

}