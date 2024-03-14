<?php

/**
 * Instagram Settings.
 */
function youzify_instagram_widget_settings() {

    global $Youzify_Settings;

    // Get Args
    $args = youzify_get_profile_widget_args( 'instagram' );

    $Youzify_Settings->get_field(
        array(
            'title' => youzify_option( 'youzify_wg_instagram_title', __( 'Instagram', 'youzify' ) ),
            'id'    => $args['id'],
            'icon'  => $args['icon'],
            'type'  => 'open'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'icon'  => 'instagram',
            'provider' => 'Instagram',
            'id'    => 'youzify_wg_instagram_account_token',
            'title' => __( 'Instagram Username', 'youzify' ),
            'button'=> __( 'Connect With Instagram', 'youzify' ),
            'desc'  => __( 'Connect to your instagram account so we can get the permission to display your photos.', 'youzify' ),
            'type'  => 'connect'
        ), true
    );

    $Youzify_Settings->get_field( array( 'type' => 'close' ) );

}
