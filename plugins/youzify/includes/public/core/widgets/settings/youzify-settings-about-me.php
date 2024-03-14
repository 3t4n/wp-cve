<?php

/**
 * About Me Settings.
 */
function youzify_about_me_widget_settings() {

    global $Youzify_Settings;

    $args = youzify_get_profile_widget_args( 'about_me' );

    $Youzify_Settings->get_field(
        array(
            'title' => youzify_option( 'youzify_wg_aboutme_title', __( 'About Me', 'youzify' ) ),
            'id'    => $args['id'],
            'icon'  => $args['icon'],
            'type'  => 'open'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'    => 'youzify_wg_about_me_photo',
            'title' => __( 'Upload Photo', 'youzify' ),
            'desc'  => __( 'Upload about me photo', 'youzify' ),
            'source' => 'profile_about_me_widget',
            'type'  => 'image'
        ), true
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Title', 'youzify' ),
            'id'    => 'youzify_wg_about_me_title',
            'desc'  => __( 'Type your full name', 'youzify' ),
            'type'  => 'text'
        ), true
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Description', 'youzify' ),
            'desc'  => __( 'Type your position', 'youzify' ),
            'id'    => 'youzify_wg_about_me_desc',
            'type'  => 'text'
        ), true
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Biography', 'youzify' ),
            'id'    => 'youzify_wg_about_me_bio',
            'desc'  => __( 'Add your biography', 'youzify' ),
            'type'  => 'wp_editor'
        ), true
    );

    do_action( 'youzify_after_about_me_widget_settings_fields' );

    $Youzify_Settings->get_field( array( 'type' => 'close' ) );

}