<?php

/**
 * Services Settings.
 */
function youzify_services_widget_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Display Title', 'youzify' ),
            'id'    => 'youzify_wg_services_display_title',
            'desc'  => __( 'Show services title', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzify' ),
            'id'    => 'youzify_wg_services_title',
            'desc'  => __( 'Type widget title', 'youzify' ),
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the widget to be loaded?', 'youzify' ),
            'id'    => 'youzify_services_load_effect',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Allowed Services Number', 'youzify' ),
            'desc'  => __( 'Maximum allowed services number', 'youzify' ),
            'id'    => 'youzify_wg_max_services',
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Services Box Layouts', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'    => 'youzify_wg_services_layout',
            'desc'  => __( 'Services Widget Layouts', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'services_layout' ),
            'type'  => 'imgSelect',
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Widget Visibility Setting', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Service Icon', 'youzify' ),
            'desc'  => __( 'Show services icon', 'youzify' ),
            'id'    => 'youzify_display_service_icon',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Service Title', 'youzify' ),
            'desc'  => __( 'Show services title', 'youzify' ),
            'id'    => 'youzify_display_service_title',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Service Description', 'youzify' ),
            'id'    => 'youzify_display_service_text',
            'desc'  => __( 'Show services description', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Widget Styling Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Service Icon', 'youzify' ),
            'id'    => 'youzify_wg_service_icon_color',
            'desc'  => __( 'Service icon color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Service Icon Background', 'youzify' ),
            'id'    => 'youzify_wg_service_icon_bg_color',
            'desc'  => __( 'Service icon background', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Service Title', 'youzify' ),
            'id'    => 'youzify_wg_service_title_color',
            'desc'  => __( 'Service title color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Service Description', 'youzify' ),
            'desc'  => __( 'Service description color', 'youzify' ),
            'id'    => 'youzify_wg_service_text_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}