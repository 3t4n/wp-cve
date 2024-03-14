<?php

/**
 * Admin Settings.
 */
function youzify_membership_register_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Registration', 'youzify' ),
            'desc'  => __( 'Enable users registration', 'youzify' ),
            'id'    => 'users_can_register',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Registration Access Protection', 'youzify' ),
            'id'    => 'youzify_membership_limit_domain',
            'desc'  => __( 'Only email addresses with the following domain(s) may register in your site.', 'youzify' ),
            'type'  => 'taxonomy'
        )
    );

    // Get Site Rules
    global $wp_roles;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'New User Default Role', 'youzify' ),
            'desc'  => __( 'Select new user default role', 'youzify' ),
            'opts'  => $wp_roles->get_names(),
            'id'    => 'default_role',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Register Button Title', 'youzify' ),
            'desc'  => __( 'Type register button title', 'youzify' ),
            'id'    => 'youzify_signup_register_btn_title',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Login Button Title', 'youzify' ),
            'desc'  => __( 'Type login button title', 'youzify' ),
            'id'    => 'youzify_signup_signin_btn_title',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Terms & Privacy Policy Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Display Note', 'youzify' ),
            'desc'  => __( 'Display terms and privacy policy note', 'youzify' ),
            'id'    => 'youzify_membership_show_terms_privacy_note',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Terms URL', 'youzify' ),
            'desc'  => __( 'Enter terms and conditions link', 'youzify' ),
            'id'    => 'youzify_membership_terms_url',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Privacy Policy URL', 'youzify' ),
            'desc'  => __( 'Enter privacy policy link', 'youzify' ),
            'id'    => 'youzify_membership_privacy_url',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    // Get Header Settings
    youzify_membership_register_header_settings();

    // Get Buttons Settings
    youzify_membership_register_buttons_settings();

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Register Widget Margin Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Box Margin Top', 'youzify' ),
            'id'    => 'youzify_register_wg_margin_top',
            'desc'  => __( 'Specify box top margin', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Box Margin Bottom', 'youzify' ),
            'id'    => 'youzify_register_wg_margin_bottom',
            'desc'  => __( 'Specify box bottom margin', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Header Settings
 */
function youzify_membership_register_header_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Form Cover', 'youzify' ),
            'desc'  => __( 'Enable form header cover?', 'youzify' ),
            'id'    => 'youzify_signup_form_enable_header',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Form Title', 'youzify' ),
            'desc'  => __( 'Registration form title', 'youzify' ),
            'id'    => 'youzify_signup_form_title',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'form Subtitle', 'youzify' ),
            'desc'  => __( 'Sign up form Subtitle', 'youzify' ),
            'id'    => 'youzify_signup_form_subtitle',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Upload Cover', 'youzify' ),
            'desc'  => __( 'Upload registration form cover', 'youzify' ),
            'id'    => 'youzify_signup_cover',
            'type'  => 'upload'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}

/**
 * Buttons Settings
 */
function youzify_membership_register_buttons_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Buttons Layout', 'youzify' ),
            'class' => 'ukai-center-elements',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'    => 'youzify_signup_actions_layout',
            'type'  => 'imgSelect',
            'opts'  =>  array(
                'form-regactions-v1', 'form-regactions-v2', 'form-regactions-v3', 'form-regactions-v4',
                'form-regactions-v5', 'form-regactions-v6'
            )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Buttons Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Buttons Icons Position', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'form_icons_position' ),
            'desc'  => __( 'Select buttons icons position <br>( works only with buttons that support icons ! )', 'youzify' ),
            'id'    => 'youzify_signup_btn_icons_position',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Buttons Border Style', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'fields_format' ),
            'desc'  => __( 'Select buttons border style', 'youzify' ),
            'id'    => 'youzify_signup_btn_format',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}