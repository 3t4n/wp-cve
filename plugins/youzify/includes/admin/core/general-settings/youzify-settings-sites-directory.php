<?php

/**
 * Sites Directory Settings.
 */

function youzify_sites_directory_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Background Color', 'youzify' ),
            'desc'  => __( 'Select header background color', 'youzify' ),
            'id'    => 'youzify_sd_header_background',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Upload Header Cover', 'youzify' ),
            'desc'  => __( 'Upload header cover image', 'youzify' ),
            'id'    => 'youzify_sd_header_cover',
            'type'  => 'upload'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Title', 'youzify' ),
            'desc'  => __( 'Enter header title', 'youzify' ),
            'id'    => 'youzify_sd_header_title',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Subtitle', 'youzify' ),
            'desc'  => __( 'Enter header subtitle', 'youzify' ),
            'id'    => 'youzify_sd_header_subtitle',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}