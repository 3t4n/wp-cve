<?php
/**
 * Captcha Settings
 */
function youzify_membership_limit_login_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Limit Login', 'youzify' ),
            'desc'  => __( 'Enable limit login attempts', 'youzify' ),
            'id'    => 'youzify_enable_limit_login',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Allowed Login Retries', 'youzify' ),
            'desc'  => __( 'Lock out after this many tries', 'youzify' ),
            'id'    => 'youzify_membership_allowed_retries',
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Short Lockouts Number', 'youzify' ),
            'desc'  => __( 'Apply long lockout after this many lockouts', 'youzify' ),
            'id'    => 'youzify_membership_allowed_lockouts',
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Retries Duration', 'youzify' ),
            'desc'  => __( 'Reset retries after this many seconds', 'youzify' ),
            'id'    => 'youzify_membership_retries_duration',
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Short Lockouts Duration', 'youzify' ),
            'desc'  => __( 'Short lockout for this many seconds', 'youzify' ),
            'id'    => 'youzify_membership_short_lockout_duration',
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Long Lockouts Duration', 'youzify' ),
            'desc'  => __( 'Long lockout for this many seconds', 'youzify' ),
            'id'    => 'youzify_membership_long_lockout_duration',
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}