<?php

/**
 * Styling Settings
 */
function youzify_membership_register_styling_settings() {

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
            'id'    => 'youzify_signup_title_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Form Subtitle', 'youzify' ),
            'desc'  => __( 'Form subtitle color', 'youzify' ),
            'id'    => 'youzify_signup_subtitle_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Cover Title Background', 'youzify' ),
            'desc'  => __( 'Cover title background color', 'youzify' ),
            'id'    => 'youzify_signup_cover_title_bg_color',
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
            'id'    => 'youzify_signup_label_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Placeholder', 'youzify' ),
            'desc'  => __( 'Form labels color', 'youzify' ),
            'id'    => 'youzify_signup_placeholder_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Inputs Text', 'youzify' ),
            'desc'  => __( 'Inputs text color', 'youzify' ),
            'id'    => 'youzify_signup_inputs_txt_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Inputs Background', 'youzify' ),
            'desc'  => __( 'Inputs background color', 'youzify' ),
            'id'    => 'youzify_signup_inputs_bg_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Inputs Border', 'youzify' ),
            'desc'  => __( 'Inputs border color', 'youzify' ),
            'id'    => 'youzify_signup_inputs_border_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Buttons Styling Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Register Button Color', 'youzify' ),
            'desc' => __( 'Submit button background', 'youzify' ),
            'id'    => 'youzify_signup_submit_bg_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Register Button Text', 'youzify' ),
            'desc'  => __( 'Register button text color', 'youzify' ),
            'id'    => 'youzify_signup_submit_txt_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Login Button Color', 'youzify' ),
            'desc'  => __( 'Register button background color', 'youzify' ),
            'id'    => 'youzify_signup_loginbutton_bg_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Login Button Text', 'youzify' ),
            'desc'  => __( 'Register button text color', 'youzify' ),
            'id'    => 'youzify_signup_loginbutton_txt_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}