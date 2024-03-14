<?php

/**
 * Account Verification Settings.
 */
function youzify_account_verification_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Verified Badges', 'youzify' ),
            'desc'  => __( 'Enable accounts verification', 'youzify' ),
            'id'    => 'youzify_enable_account_verification',
            'type'  => 'checkbox'
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
            'title' => __( 'Badge Background', 'youzify' ),
            'desc'  => __( 'Badge background color', 'youzify' ),
            'id'    => 'youzify_verified_badge_background_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Badge Icon', 'youzify' ),
            'desc'  => __( 'Badge icon color', 'youzify' ),
            'id'    => 'youzify_verified_badge_icon_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}