<?php

/**
 * Quote Settings.
 */
function youzify_quote_widget_settings() {

    global $Youzify_Settings;

    // Get Args
    $args = youzify_get_profile_widget_args( 'quote' );

    $Youzify_Settings->get_field(
        array(
            'title' => youzify_option( 'youzify_wg_quote_title', __( 'Quote', 'youzify' ) ),
            'id'    => $args['id'],
            'icon'  => $args['icon'],
            'type'  => 'open'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'  => __( 'Quote Background Image', 'youzify' ),
            'id'     => 'youzify_wg_quote_img',
            'desc'   => __( 'Upload quote cover', 'youzify' ),
            'source' => 'profile_quote_widget',
            'type'   => 'image'
        ), true
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Quote Text', 'youzify' ),
            'id'    => 'youzify_wg_quote_txt',
            'desc'  => __( 'Type quote text', 'youzify' ),
            'type'  => 'textarea'
        ), true
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Quote Owner', 'youzify' ),
            'desc'  => __( 'Type quote owner', 'youzify' ),
            'id'    => 'youzify_wg_quote_owner',
            'type'  => 'text'
        ), true
    );

    $Youzify_Settings->get_field( array( 'type' => 'close' ) );

}