<?php

/**
 * General Settings.
 */

function youzify_membership_general_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Pages Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Login Page', 'youzify' ),
            'desc'  => __( 'Choose login page', 'youzify' ),
            'std'   => youzify_membership_page_id( 'login' ),
            'id'    => 'login',
            'opts'  => youzify_get_pages(),
            'type'  => 'select'
        ),
        false,
        'youzify_membership_pages'
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Otp Login Page', 'youzify' ),
            'desc'  => __( 'Choose login page', 'youzify' ),
            'std'   => youzify_membership_page_id( 'otp_login' ),
            'id'    => 'otp_login',
            'opts'  => youzify_get_pages(),
            'type'  => 'select'
        ),
        false,
        'youzify_membership_pages'
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Lost Password Page', 'youzify' ),
            'desc'  => __( 'Choose lost password page', 'youzify' ),
            'std'   => youzify_membership_page_id( 'lost-password' ),
            'opts'  => youzify_get_pages(),
            'id'    => 'lost-password',
            'type'  => 'select'
        ),
        false,
        'youzify_membership_pages'
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Dashboard & Toolbar Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Hide Dashboard For Subscribers', 'youzify' ),
            'desc'  => __( 'Hide admin toolbar and dashborad for subscribers', 'youzify' ),
            'id'    => 'youzify_hide_subscribers_dash',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Margin Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Margin Top', 'youzify' ),
            'id'    => 'youzify_membership_forms_margin_top',
            'desc'  => __( 'Specify membership system page top margin', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Margin Bottom', 'youzify' ),
            'id'    => 'youzify_membership_forms_margin_bottom',
            'desc'  => __( 'Specify membership system page bottom margin', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}