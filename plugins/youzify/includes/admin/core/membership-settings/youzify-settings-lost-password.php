<?php

/**
 * Lost Password Settings
 */

function youzify_membership_lost_password_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Form Title', 'youzify' ),
            'desc'  => __( 'Lost password form title', 'youzify' ),
            'id'    => 'youzify_lostpswd_form_title',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Form Subtitle', 'youzify' ),
            'desc'  => __( 'Lost password subtitle', 'youzify' ),
            'id'    => 'youzify_lostpswd_form_subtitle',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Reset Button Title', 'youzify' ),
            'desc'  => __( 'Reset password button title', 'youzify' ),
            'id'    => 'youzify_lostpswd_submit_btn_title',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Cover Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Form Cover', 'youzify' ),
            'desc'  => __( 'Enable form header cover?', 'youzify' ),
            'id'    => 'youzify_lostpswd_form_enable_header',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Form Cover', 'youzify' ),
            'desc'  => __( 'Upload login form cover', 'youzify' ),
            'id'    => 'youzify_lostpswd_cover',
            'type'  => 'upload'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}