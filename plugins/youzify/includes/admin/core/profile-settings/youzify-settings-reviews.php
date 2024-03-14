<?php

/**
 * Reviews Settings.
 */

function youzify_reviews_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox',
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Reviews', 'youzify' ),
            'desc'  => __( 'Enable reviews', 'youzify' ),
            'id'    => 'youzify_enable_reviews',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Allow Reviews Edition', 'youzify' ),
            'desc'  => __( 'Allow users to edit their reviews?', 'youzify' ),
            'id'    => 'youzify_allow_users_reviews_edition',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Reviews Privacy', 'youzify' ),
            'desc'  => __( 'Who can see users reviews?', 'youzify' ),
            'id'    => 'youzify_user_reviews_privacy',
            'opts'  => array(
                'public' => __( 'Public', 'youzify' ),
                'private' => __( 'Private', 'youzify' ),
                'friends' => __( 'Friends', 'youzify' ),
                'loggedin' => __( 'Logged-in Users', 'youzify' ),
            ),
            'type'  => 'select'
        )
    );
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Reviews Per Page', 'youzify' ),
            'desc'  => __( 'Number of reviews per page?', 'youzify' ),
            'id'    => 'youzify_profile_reviews_per_page',
            'type'  => 'number'
        )
    );

    do_action( 'youzify_after_reviews_settings' );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}