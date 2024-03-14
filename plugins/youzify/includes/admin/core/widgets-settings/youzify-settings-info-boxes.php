<?php

/**
 * Info Boxes Settings.
 */
function youzify_info_boxes_widget_settings() {

    global $Youzify_Settings;


    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Email Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(

            'title' => __( 'E-mail Field', 'youzify' ),
            'desc'  => __( 'Select the email box field', 'youzify' ),
            'opts'  => youzify_get_panel_profile_fields(),
            'id'    => 'youzify_email_info_box_field',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Email Loading Effect', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'Email loading effect', 'youzify' ),
            'id'    => 'youzify_email_load_effect',
            'type'  => 'select'
        )
    );
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Background Left', 'youzify' ),
            'desc'  => __( 'gradient background color', 'youzify' ),
            'id'    => 'youzify_ibox_email_bg_left',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Background Right', 'youzify' ),
            'desc'  => __( 'Gradient background color', 'youzify' ),
            'id'    => 'youzify_ibox_email_bg_right',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Address Styling Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(

            'title' => __( 'Address Field', 'youzify' ),
            'desc'  => __( 'Select the address box field', 'youzify' ),
            'opts'  => youzify_get_panel_profile_fields(),
            'id'    => 'youzify_address_info_box_field',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Address Loading Effect', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'Address loading effect', 'youzify' ),
            'id'    => 'youzify_address_load_effect',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Background Left', 'youzify' ),
            'desc'  => __( 'Gradient background color', 'youzify' ),
            'id'    => 'youzify_ibox_address_bg_left',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Background Right', 'youzify' ),
            'desc'  => __( 'Gradient background color', 'youzify' ),
            'id'    => 'youzify_ibox_address_bg_right',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Website Styling Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(

            'title' => __( 'Website Field', 'youzify' ),
            'desc'  => __( 'Select the website box field', 'youzify' ),
            'opts'  => youzify_get_panel_profile_fields(),
            'id'    => 'youzify_website_info_box_field',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Website Loading Effect', 'youzify' ),
            'desc'  => __( 'Website loading effect?', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
            'id'    => 'youzify_website_load_effect',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Background Left', 'youzify' ),
            'desc'  => __( 'gradient background color', 'youzify' ),
            'id'    => 'youzify_ibox_website_bg_left',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Background Right', 'youzify' ),
            'desc'  => __( 'Gradient background color', 'youzify' ),
            'id'    => 'youzify_ibox_website_bg_right',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Phone Number Styling Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(

            'title' => __( 'Phone Field', 'youzify' ),
            'desc'  => __( 'Select the phone box field', 'youzify' ),
            'opts'  => youzify_get_panel_profile_fields(),
            'id'    => 'youzify_phone_info_box_field',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Phone Loading Effect', 'youzify' ),
            'desc'  => __( 'Phone number loading effect?', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
            'id'    => 'youzify_phone_load_effect',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Background Left', 'youzify' ),
            'desc'  => __( 'Gradient background color', 'youzify' ),
            'id'    => 'youzify_ibox_phone_bg_left',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Background Right', 'youzify' ),
            'desc'  => __( 'Gradient background color', 'youzify' ),
            'id'    => 'youzify_ibox_phone_bg_right',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}