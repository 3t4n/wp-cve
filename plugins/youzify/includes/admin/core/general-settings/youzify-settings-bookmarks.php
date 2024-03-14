<?php

/**
 * Bookmarks Settings.
 */

function youzify_bookmarks_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox',
            'is_premium' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Bookmarks', 'youzify' ),
            'desc'  => __( 'Enable bookmarks', 'youzify' ),
            'id'    => 'youzify_enable_bookmarks',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Bookmarks Privacy', 'youzify' ),
            'desc'  => __( 'Who can see users bookmarks?', 'youzify' ),
            'id'    => 'youzify_enable_bookmarks_privacy',
            'opts'  => array(
                'public' => __( 'Public', 'youzify' ),
                'private' => __( 'Private', 'youzify' ),
                'friends' => __( 'Friends', 'youzify' ),
                'loggedin' => __( 'Logged-in Users', 'youzify' ),
            ),
            'type'  => 'select'
        )
    );

    do_action( 'youzify_after_bookmarks_settings' );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}