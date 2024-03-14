<?php

/**
 * Flickr Settings.
 */
function youzify_flickr_widget_settings() {

    global $Youzify_Settings;

    // Get Args
    $args = youzify_get_profile_widget_args( 'flickr' );

    $Youzify_Settings->get_field(
        array(
            'title' => youzify_option( 'youzify_wg_flickr_title', __( 'Flickr', 'youzify' ) ),
            'id'    => $args['id'],
            'icon'  => $args['icon'],
            'type'  => 'open'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Flickr ID', 'youzify' ),
            'id'    => 'youzify_wg_flickr_account_id',
            'desc'  => __( 'Flickr ID format example: 12345678@N07', 'youzify' ),
            'type'  => 'text'
        ), true
    );

    $Youzify_Settings->get_field( array( 'type' => 'close' ) );

}