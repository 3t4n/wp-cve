<?php

/**
 * Admin Settings.
 */
function youzify_membership_login_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Ajax Login', 'youzify' ),
            'desc'  => __( 'Enable ajax in login forms?', 'youzify' ),
            'id'    => 'youzify_enable_ajax_login',
            'type'  => 'checkbox',
            'is_premium'  => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Login Popup', 'youzify' ),
            'desc'  => __( 'Enable popup login form?', 'youzify' ),
            'id'    => 'youzify_enable_login_popup',
            'type'  => 'checkbox',
            'is_premium'  => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Login Button Title', 'youzify' ),
            'desc'  => __( 'Type login button title', 'youzify' ),
            'id'    => 'youzify_login_signin_btn_title',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Register Button Title', 'youzify' ),
            'desc'  => __( 'Type register button title', 'youzify' ),
            'id'    => 'youzify_login_register_btn_title',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Lost Password Title', 'youzify' ),
            'desc'  => __( 'Type lost password title', 'youzify' ),
            'id'    => 'youzify_login_lostpswd_title',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Custom Registration Link', 'youzify' ),
            'desc'  => __( 'Paste a custom registration page link', 'youzify' ),
            'id'    => 'youzify_login_custom_register_link',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Redirect Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    // Get Website Pages.
    $website_pages = youzify_get_panel_pages();

    // Get User Default Redirect Options
    $default_user_redirect_options = $Youzify_Settings->get_field_options( 'user_login_redirect_pages' );

    // Get All Redirect Options.
    $user_login_redirect_pages = $default_user_redirect_options + $website_pages;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'After login redirect user to?', 'youzify' ),
            'desc'  => __( 'After user login redirect to which page?', 'youzify' ),
            'id'    => 'youzify_user_after_login_redirect',
           'opts'  => $user_login_redirect_pages,
            'type'  => 'select'
        )
    );

    // Get Admin Default Redirect Options
    $default_admin_redirect_options = $Youzify_Settings->get_field_options( 'admin_login_redirect_pages' );

    // Get All Redirect Options.
    $admin_login_redirect_pages = $default_admin_redirect_options + $website_pages;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'After login redirect admin to?', 'youzify' ),
            'desc'  => __( 'After admin login redirect to which page?', 'youzify' ),
            'id'    => 'youzify_admin_after_login_redirect',
            'opts'  => $admin_login_redirect_pages,
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'After logout redirect user to?', 'youzify' ),
            'desc'  => __( 'After user logout redirect to which page?', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'logout_redirect_pages' ),
            'id'    => 'youzify_after_logout_redirect',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    // Get Header Settings
    youzify_membership_login_header_settings();

    // Get Fields Settings
    youzify_membership_login_fields_settings();

    // Get Buttons Settings
    youzify_membership_login_buttons_settings();

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Login Widget Margin Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Box Margin Top', 'youzify' ),
            'id'    => 'youzify_login_wg_margin_top',
            'desc'  => __( 'Specify box top margin', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Box Margin Bottom', 'youzify' ),
            'id'    => 'youzify_login_wg_margin_bottom',
            'desc'  => __( 'Specify box bottom margin', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Header Settings
 */
function youzify_membership_login_header_settings() {

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
            'id'    => 'youzify_login_form_enable_header',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Form Title', 'youzify' ),
            'desc'  => __( 'Login form title', 'youzify' ),
            'id'    => 'youzify_login_form_title',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Form Subtitle', 'youzify' ),
            'desc'  => __( 'Login form subtitle', 'youzify' ),
            'id'    => 'youzify_login_form_subtitle',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Form Cover', 'youzify' ),
            'desc'  => __( 'Upload login form cover', 'youzify' ),
            'id'    => 'youzify_login_cover',
            'type'  => 'upload'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Fields Settings
 */
function youzify_membership_login_fields_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Form Fields Layouts', 'youzify' ),
            'class' => 'ukai-center-elements',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'    => 'youzify_login_form_layout',
            'type'  => 'imgSelect',
            'opts'  =>  array(
                'form-field-v1', 'form-field-v2', 'form-field-v3', 'form-field-v4', 'form-field-v5',
                'form-field-v6', 'form-field-v7', 'form-field-v8', 'form-field-v9', 'form-field-v10',
                'form-field-v11', 'form-field-v12'
            )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Fields Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Fields Icons Position', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'form_icons_position' ),
            'desc'  => __( 'Select fields icons position <br>( works only with layouts that support icons! )', 'youzify' ),
            'id'    => 'youzify_login_icons_position',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Fields Border Style', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'fields_format' ),
            'desc'  => __( 'Select fields border style', 'youzify' ),
            'id'    => 'youzify_login_fields_format',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Buttons Settings
 */
function youzify_membership_login_buttons_settings() {

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
            'id'    => 'youzify_login_actions_layout',
            'type'  => 'imgSelect',
            'opts'  =>  array(
                'form-actions-v1', 'form-actions-v2', 'form-actions-v3', 'form-actions-v4',
                'form-actions-v5', 'form-actions-v6', 'form-actions-v7', 'form-actions-v8',
                'form-actions-v9', 'form-actions-v10'
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
            'desc'  => __( 'Select buttons icons position <br>( works only with buttons that support icons! )', 'youzify' ),
            'id'    => 'youzify_login_btn_icons_position',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Buttons Border Style', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'fields_format' ),
            'desc'  => __( 'Select buttons border style', 'youzify' ),
            'id'    => 'youzify_login_btn_format',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Get Wordpress Pages
 */
function youzify_get_panel_pages() {

    // Set Up Variables
    $pages = array();

    foreach ( get_pages() as $page ) {
        $pages[ $page->ID ] = sprintf( __( '%1s ( ID : %2d )', 'youzify' ), $page->post_title, $page->ID );
    }

    return $pages;
}