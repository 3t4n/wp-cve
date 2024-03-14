<?php

/**
 * Styling Settings
 */
function youzify_membership_login_styling_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Styling Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Form Title', 'youzify' ),
            'desc'  => __( 'Form title color', 'youzify' ),
            'id'    => 'youzify_login_title_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Form Subtitle', 'youzify' ),
            'desc'  => __( 'Form subtitle color', 'youzify' ),
            'id'    => 'youzify_login_subtitle_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Cover Title Background', 'youzify' ),
            'desc'  => __( 'Cover title background color', 'youzify' ),
            'id'    => 'youzify_login_cover_title_bg_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Fields Styling Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Labels', 'youzify' ),
            'desc'  => __( 'Form labels color', 'youzify' ),
            'id'    => 'youzify_login_label_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Placeholder', 'youzify' ),
            'desc'  => __( 'Form labels color', 'youzify' ),
            'id'    => 'youzify_login_placeholder_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Inputs Text', 'youzify' ),
            'desc'  => __( 'Inputs text color', 'youzify' ),
            'id'    => 'youzify_login_inputs_txt_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Inputs Background', 'youzify' ),
            'desc'  => __( 'Inputs background color', 'youzify' ),
            'id'    => 'youzify_login_inputs_bg_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Inputs Border', 'youzify' ),
            'desc'  => __( 'Inputs border color', 'youzify' ),
            'id'    => 'youzify_login_inputs_border_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Icons', 'youzify' ),
            'desc'  => __( 'Fields icons color', 'youzify' ),
            'id'    => 'youzify_login_fields_icons_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Icons Background', 'youzify' ),
            'desc'  => __( 'Fields icons background color', 'youzify' ),
            'id'    => 'youzify_login_fields_icons_bg_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Remember Me Styling Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( '"Remember Me" Color', 'youzify' ),
            'desc'  => __( 'Form "remember me" color', 'youzify' ),
            'id'    => 'youzify_login_remember_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Checkbox Border', 'youzify' ),
            'desc'  => __( 'Form checkbox border color', 'youzify' ),
            'id'    => 'youzify_login_checkbox_border_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Checkbox Icon', 'youzify' ),
            'desc'  => __( 'Form checkbox icon color', 'youzify' ),
            'id'    => 'youzify_login_checkbox_icon_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Buttons Styling Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( '"Lost Password" Color', 'youzify' ),
            'desc'  => __( 'Form "lost password" color', 'youzify' ),
            'id'    => 'youzify_login_lostpswd_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Login Button Color', 'youzify' ),
            'desc'  => __( 'Login button background color', 'youzify' ),
            'id'    => 'youzify_login_submit_bg_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Login Button Text', 'youzify' ),
            'desc'  => __( 'Login button text color', 'youzify' ),
            'id'    => 'youzify_login_submit_txt_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Register Button Color', 'youzify' ),
            'desc'  => __( 'Register button background color', 'youzify' ),
            'id'    => 'youzify_login_regbutton_bg_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Register Button Text', 'youzify' ),
            'desc'  => __( 'Register button text color', 'youzify' ),
            'id'    => 'youzify_login_regbutton_txt_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}