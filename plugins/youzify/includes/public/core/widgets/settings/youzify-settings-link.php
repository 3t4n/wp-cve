<?php

/**
 * Link Settings.
 */
function youzify_link_widget_settings() {

    global $Youzify_Settings;

    // Get Args
    $args = youzify_get_profile_widget_args( 'link' );

    $Youzify_Settings->get_field(
        array(
            'title' => youzify_option( 'youzify_wg_link_title', __( 'Link', 'youzify' ) ),
            'id'    => $args['id'],
            'icon'  => $args['icon'],
            'type'  => 'open'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Link Background Image', 'youzify' ),
            'id'    => 'youzify_wg_link_img',
            'source' => 'profile_link_widget',
            'desc'  => __( 'Upload link cover', 'youzify' ),
            'type'  => 'image'
        ), true
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Link Description', 'youzify' ),
            'id'    => 'youzify_wg_link_txt',
            'desc'  => __( 'Add link description', 'youzify' ),
            'type'  => 'textarea'
            ), true
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Link URL', 'youzify' ),
            'desc'  => __( 'Add your link', 'youzify' ),
            'id'    => 'youzify_wg_link_url',
            'type'  => 'text'
        ), true
    );

    $Youzify_Settings->get_field( array( 'type' => 'close' ) );

}