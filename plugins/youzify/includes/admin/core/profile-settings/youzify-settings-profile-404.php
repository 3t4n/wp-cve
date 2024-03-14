<?php

/**
 * Porfile 404 Settings.
 */

function youzify_profile_404_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Error Message', 'youzify' ),
            'desc'  => __( 'Page error message', 'youzify' ),
            'id'    => 'youzify_profile_404_desc',
            'type'  => 'textarea'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Button Title', 'youzify' ),
            'desc'  => __( 'Page button title', 'youzify' ),
            'id'    => 'youzify_profile_404_button',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Photo', 'youzify' ),
            'desc'  => __( 'Upload 404 profile photo', 'youzify' ),
            'id'    => 'youzify_profile_404_photo',
            'type'  => 'upload'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Cover', 'youzify' ),
            'desc'  => __( 'Upload 404 profile cover', 'youzify' ),
            'id'    => 'youzify_profile_404_cover',
            'type'  => 'upload'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Styling Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Title', 'youzify' ),
            'desc'  => __( 'Title color', 'youzify' ),
            'id'    => 'youzify_profile_404_title_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Description', 'youzify' ),
            'desc'  => __( 'Description color', 'youzify' ),
            'id'    => 'youzify_profile_404_desc_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Button Text', 'youzify' ),
            'desc'  => __( 'Button text color', 'youzify' ),
            'id'    => 'youzify_profile_404_button_txt_color',
            'type'  => 'color'
        )
    );
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Button Background', 'youzify' ),
            'desc'  => __( 'Button background color', 'youzify' ),
            'id'    => 'youzify_profile_404_button_bg_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}