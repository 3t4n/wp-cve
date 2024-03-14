<?php
/**
 * Newsletter Settings
 */
function youzify_membership_newsletter_settings() {

    global $Youzify_Settings;

    // Get Tutorial Url
    $tutorial_url = 'http://www.bigpixels.com/where-can-i-find-my-mailchimp-api-key-and-list-id/';

    $Youzify_Settings->get_field(
        array(
            'title'     => __( 'How to get your MailChimp API key & list ID?', 'youzify' ),
            'msg_type'  => 'info',
            'type'      => 'msgBox',
            'id'        => 'youzify_msgbox_mailchimp',
            'msg'       => sprintf( __( 'To learn how to get your API key and list id Visit the tutorial <strong><a href="%s">How to get MailChimp API key and list ID.</a></strong>', 'youzify' ), $tutorial_url )
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Mailchimp Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable MailChimp', 'youzify' ),
            'desc'  => __( 'Enable MailChimp integration', 'youzify' ),
            'id'    => 'youzify_enable_mailchimp',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'MailChimp API Key', 'youzify' ),
            'desc'  => __( 'The MailChimp API key', 'youzify' ),
            'id'    => 'youzify_mailchimp_api_key',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'MailChimp List ID', 'youzify' ),
            'desc'  => __( 'The MailChimp list ID', 'youzify' ),
            'id'    => 'youzify_mailchimp_list_id',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Mailster Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Mailster', 'youzify' ),
            'desc'  => __( 'Enable Mailster integration', 'youzify' ),
            'id'    => 'youzify_enable_mailster',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( "Mailsters List ID's", 'youzify' ),
            'desc'  => __( "Type the Mailster list id, use ',' to separate ids example: 1,2", 'youzify' ),
            'id'    => 'youzify_mailster_list_ids',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}